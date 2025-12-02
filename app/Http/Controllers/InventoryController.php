<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Department;
use App\Models\ResponsibilityCard;
use App\Models\Appraisal;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar listado de bienes del inventario
     */
    public function index(Request $request)
    {
        // Si el filtro es "retired" (dado de baja), mostrar solo los soft-deleted
        if ($request->filled('status') && $request->status === 'retired') {
            $query = Inventory::onlyTrashed()->with(['department', 'appraisals', 'transfers']);
        } else {
            // Por defecto, mostrar solo los activos (excluir soft-deleted automáticamente)
            $query = Inventory::with(['department', 'appraisals', 'transfers']);
        }

        // Búsqueda mejorada: descripción, número de bien, nombre, número de serie
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('asset_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filtros
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('model')) {
            $query->where('model', 'like', "%{$request->model}%");
        }

        // Si NO es "retired", aplicar filtro de status normal
        if ($request->filled('status') && $request->status !== 'retired') {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $perPage = $request->get('per_page', 25);
        $items = $query->latest()->paginate($perPage)->appends($request->query());

        $totalItems = Inventory::count();
        $availableItems = Inventory::where('status', 'available')->count();
        $assignedItems = Inventory::where('status', 'assigned')->count();
        $deletedItems = Inventory::onlyTrashed()->count();

        // Obtener valores únicos para los filtros
        $types = Inventory::whereNotNull('type')->distinct()->pluck('type');
        $brands = Inventory::whereNotNull('brand')->distinct()->pluck('brand');
        $conditions = Inventory::whereNotNull('condition')->distinct()->pluck('condition');
        $departments = Department::where('active', true)->get();

        // Preferencias de columnas por usuario
        $defaultColumns = [
            'description' => true,
            'asset' => true,
            'type' => true,
            'department' => true,
            'status' => true,
            'quantity' => true,
        ];

        $user = $request->user();
        $columnPrefs = $defaultColumns;

        if ($user && is_array($user->inventory_column_prefs)) {
            $columnPrefs = array_merge($columnPrefs, $user->inventory_column_prefs);
        }

        return view('modules.inventory.index', compact(
            'items',
            'totalItems',
            'availableItems',
            'assignedItems',
            'deletedItems',
            'types',
            'brands',
            'conditions',
            'departments',
            'columnPrefs'
        ));
    }

    /**
     * Mostrar formulario para crear nuevo bien
     */
    public function create()
    {
        $departments = Department::where('active', true)->get();
        
        return view('modules.inventory.create', compact('departments'));
    }

    /**
     * Guardar nuevo bien en el inventario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_number' => 'required|string|unique:inventory,asset_number',
            'sku' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'vendor' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'condition' => 'nullable|string',
            'notes' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Procesar imágenes si se suben
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('inventory/images', 'public');
                $images[] = $path;
                
                // Workaround para Windows: copiar también a public/storage
                $sourcePath = storage_path('app/public/' . $path);
                $destPath = public_path('storage/' . $path);
                $destDir = dirname($destPath);
                
                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (file_exists($sourcePath)) {
                    copy($sourcePath, $destPath);
                }
            }
        }
        
        // Guardar array de imágenes (puede ser vacío si no se subieron)
        $validated['images'] = $images;

        $item = Inventory::create($validated);

        // Generar QR code con información completa (URL, nombre, número de bien)
        $this->generateQRCode($item);

        return redirect()
            ->route('inventory.show', $item)
            ->with('success', 'Bien agregado al inventario exitosamente.');
    }

    /**
     * Generar QR code para un bien del inventario
     */
    private function generateQRCode(Inventory $inventory): string
    {
        // Obtener la URL del detalle
        $url = route('inventory.show', $inventory);
        
        // Crear contenido del QR con formato legible: URL + información adicional
        // Primero la URL para compatibilidad universal con escáneres
        // Luego información adicional en formato texto legible
        $qrContent = $url . "\n\n";
        $qrContent .= "Bien: " . $inventory->name . "\n";
        $qrContent .= "Número: " . $inventory->asset_number . "\n";
        
        // Si tiene número de serie, incluirlo
        if ($inventory->serial_number) {
            $qrContent .= "Serie: " . $inventory->serial_number . "\n";
        }

        // Verificar si Imagick está disponible para generar PNG
        // Si no está disponible, usar SVG que no requiere Imagick
        $usePng = extension_loaded('imagick');
        $extension = $usePng ? 'png' : 'svg';
        
        // Definir ruta del QR
        $qrPath = 'inventory/qr/' . $inventory->id . '.' . $extension;
        $qrFullPath = storage_path('app/public/' . $qrPath);
        
        // Crear directorio si no existe
        $qrDir = dirname($qrFullPath);
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }
        
        // Generar QR code con tamaño mayor para mejor calidad al imprimir
        QrCode::format($extension)
            ->size(400)
            ->margin(2)
            ->errorCorrection('H') // Mayor corrección de errores para mejor calidad
            ->generate($qrContent, $qrFullPath);
        
        // Copiar a public/storage para Windows
        $publicQrPath = public_path('storage/' . $qrPath);
        $publicQrDir = dirname($publicQrPath);
        if (!file_exists($publicQrDir)) {
            mkdir($publicQrDir, 0755, true);
        }
        if (file_exists($qrFullPath)) {
            copy($qrFullPath, $publicQrPath);
        }
        
        // Guardar o actualizar ruta del QR en el modelo
        if (!$inventory->qr_path || $inventory->qr_path !== $qrPath) {
            $inventory->update(['qr_path' => $qrPath]);
        }
        
        return $qrPath;
    }

    /**
     * Mostrar detalle de un bien específico
     */
    public function show(Inventory $inventory)
    {
        // Generar/actualizar QR code automáticamente al acceder al detalle
        $this->generateQRCode($inventory);
        
        // Recargar el modelo para obtener la ruta actualizada del QR
        $inventory->refresh();

        $inventory->load([
            'department',
            'appraisals.appraiser',
            'transfers.fromCard.department',
            'transfers.toCard.department',
            'transfers.deliveredBy',
            'transfers.receivedBy'
        ]);

        $departments = Department::where('active', true)->get();
        $responsibilityCards = ResponsibilityCard::with('department')->where('status', 'active')->get();

        return view('modules.inventory.show', compact('inventory', 'departments', 'responsibilityCards'));
    }

    /**
     * Mostrar formulario para editar un bien
     */
    public function edit(Inventory $inventory)
    {
        $departments = Department::where('active', true)->get();
        
        return view('modules.inventory.edit', compact('inventory', 'departments'));
    }

    /**
     * Actualizar un bien del inventario
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_number' => 'required|string|unique:inventory,asset_number,' . $inventory->id,
            'sku' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'vendor' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string',
            'status' => 'required|in:available,assigned,maintenance,retired',
            'condition' => 'nullable|string',
            'notes' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Procesar nuevas imágenes si se suben (MANTENER las existentes)
        $existingImages = $inventory->images ?? [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('inventory/images', 'public');
                $existingImages[] = $path;
                
                // Workaround para Windows: copiar también a public/storage
                $sourcePath = storage_path('app/public/' . $path);
                $destPath = public_path('storage/' . $path);
                $destDir = dirname($destPath);
                
                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (file_exists($sourcePath)) {
                    copy($sourcePath, $destPath);
                }
            }
            // Solo actualizar images si hay nuevas imágenes
            $validated['images'] = $existingImages;
        } else {
            // Si no se suben nuevas imágenes, NO actualizar el campo images
            unset($validated['images']);
        }

        $inventory->update($validated);

        // Regenerar QR code si cambió el nombre o número de bien (para actualizar la información del QR)
        if (isset($validated['name']) || isset($validated['asset_number'])) {
            $this->generateQRCode($inventory);
        }

        return redirect()
            ->route('inventory.show', $inventory)
            ->with('success', 'Bien actualizado exitosamente.');
    }

    /**
     * Eliminar una imagen individual del bien
     */
    public function deleteImage(Inventory $inventory, $imageIndex)
    {
        $images = $inventory->images ?? [];
        
        if (!isset($images[$imageIndex])) {
            return back()->with('error', 'Imagen no encontrada.');
        }

        $imagePath = $images[$imageIndex];
        
        // Eliminar archivos físicos
        Storage::disk('public')->delete($imagePath);
        
        $publicPath = public_path('storage/' . $imagePath);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        }

        // Remover la imagen del array y reindexar
        unset($images[$imageIndex]);
        $images = array_values($images);

        // IMPORTANTE: Usar DB directo para evitar soft delete accidental
        \DB::table('inventory')
            ->where('id', $inventory->id)
            ->update([
                'images' => json_encode($images),
                'updated_at' => now()
            ]);

        return redirect()
            ->route('inventory.edit', $inventory)
            ->with('success', 'Imagen eliminada exitosamente.');
    }

    /**
     * Eliminar un bien del inventario
     */
    public function destroy(Inventory $inventory)
    {
        // Eliminar imágenes asociadas
        if (!empty($inventory->images)) {
            foreach ($inventory->images as $image) {
                // Eliminar de storage/app/public
                Storage::disk('public')->delete($image);
                
                // Workaround para Windows: eliminar también de public/storage
                $publicPath = public_path('storage/' . $image);
                if (file_exists($publicPath)) {
                    unlink($publicPath);
                }
            }
        }

        // Soft delete
        $inventory->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Bien dado de baja exitosamente. Puede ser reactivado posteriormente.');
    }

    /**
     * Reactivar un bien dado de baja (soft delete)
     */
    public function restore($id)
    {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);
        $inventory->restore();

        return redirect()
            ->route('inventory.index', ['status' => 'retired'])
            ->with('success', 'Bien reactivado exitosamente.');
    }

    /**
     * Eliminar múltiples bienes (soft delete)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
        ]);

        $selectedIds = json_decode($request->selected_items, true);

        if (empty($selectedIds)) {
            return back()->with('error', 'No se seleccionaron bienes.');
        }

        $count = Inventory::whereIn('id', $selectedIds)->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', "{$count} bien(es) dado(s) de baja exitosamente.");
    }

    /**
     * Reactivar múltiples bienes dados de baja
     */
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
        ]);

        $selectedIds = json_decode($request->selected_items, true);

        if (empty($selectedIds)) {
            return back()->with('error', 'No se seleccionaron bienes.');
        }

        $count = Inventory::onlyTrashed()
            ->whereIn('id', $selectedIds)
            ->get()
            ->each(function ($item) {
                $item->restore();
            })
            ->count();

        return redirect()
            ->route('inventory.index', ['status' => 'retired'])
            ->with('success', "{$count} bien(es) reactivado(s) exitosamente.");
    }

    /**
     * Generar PDF con los bienes seleccionados
     */
    public function generatePdf(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|string',
        ]);

        $selectedIds = json_decode($request->selected_items, true);
        
        if (empty($selectedIds) || !is_array($selectedIds)) {
            return back()->with('error', 'Debe seleccionar al menos un bien.');
        }

        $items = Inventory::with('department')
            ->whereIn('id', $selectedIds)
            ->orderBy('asset_number')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'No se encontraron bienes seleccionados.');
        }

        // Generar PDF usando DomPDF
        $pdf = Pdf::loadView('modules.inventory.pdf.list', compact('items'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable-local-file-access', true);

        $filename = 'listado-bienes-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generar Excel (CSV) con los bienes seleccionados
     */
    public function generateExcel(Request $request)
    {
        $selectedIds = json_decode($request->selected_items, true);
        
        if (empty($selectedIds)) {
            return back()->with('error', 'No se seleccionaron bienes.');
        }

        $items = Inventory::whereIn('id', $selectedIds)
            ->with('department')
            ->orderBy('asset_number')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'No se encontraron bienes seleccionados.');
        }

        $filename = 'bienes-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel reconozca acentos
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($handle, [
                'Número de Bien',
                'Nombre del Bien',
                'Descripción',
                'Tipo',
                'Marca',
                'Modelo',
                'Departamento',
                'Estado',
                'Cantidad',
                'Unidad',
                'Condición',
            ], ';');

            // Filas de datos
            foreach ($items as $item) {
                $statusLabels = [
                    'available' => 'Disponible',
                    'assigned' => 'Asignado',
                    'maintenance' => 'Mantenimiento',
                    'retired' => 'Dado de baja'
                ];

                fputcsv($handle, [
                    $item->asset_number ?? '',
                    $item->name ?? '',
                    $item->description ?? '',
                    $item->type ?? '',
                    $item->brand ?? '',
                    $item->model ?? '',
                    $item->department ? $item->department->name : '',
                    $statusLabels[$item->status] ?? ucfirst($item->status),
                    $item->quantity ?? 0,
                    $item->unit ?? 'pza',
                    $item->condition ?? '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Descargar template vacío de Excel para importar
     */
    public function downloadTemplate()
    {
        $filename = 'plantilla-bienes-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 para que Excel reconozca acentos
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados (mismo formato que el método import espera)
            fputcsv($handle, [
                'Número de Bien',
                'Nombre del Bien',
                'Descripción',
                'Tipo',
                'Marca',
                'Modelo',
                'Departamento',
                'Estado',
                'Cantidad',
                'Unidad',
                'Condición',
            ], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Importar bienes desde un archivo CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        if (!file_exists($path)) {
            return back()->with('error', 'No se pudo leer el archivo.');
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->with('error', 'No se pudo abrir el archivo.');
        }

        // Leer encabezado (se espera mismo formato que el CSV exportado)
        $header = fgetcsv($handle, 0, ';');

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 11) {
                continue;
            }

            [
                $assetNumber,
                $name,
                $description,
                $type,
                $brand,
                $model,
                $departmentName,
                $status,
                $quantity,
                $unit,
                $condition,
            ] = $row;

            if (!$assetNumber) {
                continue;
            }

            $departmentId = null;
            if ($departmentName) {
                $department = Department::firstOrCreate(
                    ['name' => $departmentName],
                    [
                        'code' => Str::upper(Str::slug($departmentName)).'-AUTO',
                        'description' => null,
                        'location' => null,
                        'active' => true,
                    ]
                );
                $departmentId = $department->id;
            }

            $status = in_array($status, ['available', 'assigned', 'maintenance', 'retired'])
                ? $status
                : 'available';

            Inventory::updateOrCreate(
                ['asset_number' => $assetNumber],
                [
                    'name' => $name ?: $assetNumber,
                    'description' => $description ?: null,
                    'type' => $type ?: null,
                    'brand' => $brand ?: null,
                    'model' => $model ?: null,
                    'status' => $status,
                    'quantity' => is_numeric($quantity) ? (int) $quantity : 1,
                    'unit' => $unit ?: 'pza',
                    'condition' => $condition ?: null,
                    'department_id' => $departmentId,
                ]
            );
        }

        fclose($handle);

        return back()->with('success', 'Archivo importado correctamente.');
    }

    /**
     * Guardar preferencias de columnas del usuario
     */
    public function saveColumnPreferences(Request $request)
    {
        $data = $request->validate([
            'columns' => 'required|array',
            'columns.*' => 'boolean',
        ]);

        $user = $request->user();
        if ($user) {
            $user->inventory_column_prefs = $data['columns'];
            $user->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Obtener datos de bienes seleccionados para impresión/copia
     */
    public function getSelectedItems(Request $request)
    {
        $selectedIds = json_decode($request->selected_items, true);
        
        if (empty($selectedIds)) {
            return response()->json(['items' => []]);
        }

        $items = Inventory::whereIn('id', $selectedIds)
            ->with('department')
            ->orderBy('asset_number')
            ->get()
            ->map(function ($item) {
                $statusLabels = [
                    'available' => 'Disponible',
                    'assigned' => 'Asignado',
                    'maintenance' => 'Mantenimiento',
                    'retired' => 'Dado de baja'
                ];

                return [
                    'name' => $item->name ?? '',
                    'description' => $item->description ?? '',
                    'description_full' => ($item->name ?? '') . ' ' . ($item->description ?? ''),
                    'asset_number' => $item->asset_number ?? '',
                    'type' => $item->type ?? 'N/A',
                    'department' => $item->department ? $item->department->name : 'Sin asignar',
                    'status' => $statusLabels[$item->status] ?? ucfirst($item->status),
                    'quantity' => $item->quantity . ' ' . $item->unit,
                ];
            });

        return response()->json(['items' => $items]);
    }

    // ========================================
    // TARJETAS DE RESPONSABILIDAD
    // ========================================

    /**
     * Mostrar tarjeta de responsabilidad de un bien
     */
    public function showResponsibilityCard(Inventory $inventory)
    {
        if (!$inventory->department_id) {
            return back()->with('error', 'El bien no está asignado a ningún departamento.');
        }

        $card = ResponsibilityCard::where('department_id', $inventory->department_id)
            ->where('status', 'active')
            ->with(['department', 'activeUsers'])
            ->first();

        return view('modules.inventory.responsibility-card', compact('inventory', 'card'));
    }

    /**
     * Asignar bien a un departamento
     */
    public function assignDepartment(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $inventory->update([
            'department_id' => $validated['department_id'],
            'status' => 'assigned',
        ]);

        // Crear o buscar tarjeta de responsabilidad activa
        $card = ResponsibilityCard::firstOrCreate(
            [
                'department_id' => $validated['department_id'],
                'status' => 'active',
            ],
            [
                'card_number' => 'TR-' . strtoupper(Str::random(10)),
                'issue_date' => now(),
            ]
        );

        return back()->with('success', 'Bien asignado al departamento exitosamente.');
    }

    /**
     * Imprimir tarjeta de responsabilidad
     */
    public function printResponsibilityCard(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $card = ResponsibilityCard::where('department_id', $validated['department_id'])
            ->where('status', 'active')
            ->with(['department', 'activeUsers'])
            ->firstOrFail();

        $items = Inventory::where('department_id', $validated['department_id'])
            ->where('status', 'assigned')
            ->get();

        return view('modules.inventory.print.responsibility-card', compact('card', 'items'));
    }

    // ========================================
    // GESTIÓN DE USUARIOS EN TARJETA
    // ========================================

    /**
     * Asignar usuario a una tarjeta de responsabilidad
     */
    public function assignUser(Request $request, ResponsibilityCard $card)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $card->users()->attach($validated['user_id'], [
            'assigned_date' => now(),
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Usuario asignado a la tarjeta exitosamente.');
    }

    /**
     * Remover usuario de una tarjeta de responsabilidad
     */
    public function removeUser(ResponsibilityCard $card, User $user)
    {
        $card->users()->updateExistingPivot($user->id, [
            'removed_date' => now(),
            'status' => 'removed',
        ]);

        return back()->with('success', 'Usuario removido de la tarjeta exitosamente.');
    }

    // ========================================
    // DICTÁMENES
    // ========================================

    /**
     * Mostrar dictámenes pendientes
     */
    public function pendingAppraisals()
    {
        $appraisals = Appraisal::pending()
            ->with(['inventory', 'appraiser'])
            ->latest()
            ->paginate(20);

        return view('modules.inventory.appraisals.pending', compact('appraisals'));
    }

    /**
     * Mostrar dictámenes completados
     */
    public function completedAppraisals()
    {
        $appraisals = Appraisal::completed()
            ->with(['inventory', 'appraiser'])
            ->latest('completion_date')
            ->paginate(20);

        return view('modules.inventory.appraisals.completed', compact('appraisals'));
    }

    /**
     * Subir documento PDF de dictamen
     */
    public function uploadAppraisal(Request $request, Appraisal $appraisal)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Eliminar documento anterior si existe
        if ($appraisal->document_path) {
            Storage::disk('public')->delete($appraisal->document_path);
        }

        $path = $request->file('document')->store('appraisals', 'public');

        $appraisal->update([
            'document_path' => $path,
            'status' => 'completed',
            'completion_date' => now(),
        ]);

        return back()->with('success', 'Dictamen subido exitosamente.');
    }

    /**
     * Imprimir formato de dictamen
     */
    public function printAppraisal(Appraisal $appraisal)
    {
        $appraisal->load(['inventory', 'appraiser']);

        return view('modules.inventory.appraisals.print', compact('appraisal'));
    }

    /**
     * Ver dictamen almacenado
     */
    public function viewAppraisal(Appraisal $appraisal)
    {
        if (!$appraisal->document_path) {
            return back()->with('error', 'No hay documento disponible.');
        }

        return response()->file(storage_path('app/public/' . $appraisal->document_path));
    }

    // ========================================
    // TRASPASOS DE BIENES
    // ========================================

    /**
     * Mostrar listado de traspasos
     */
    public function transfersIndex()
    {
        $transfers = Transfer::with([
            'inventory',
            'fromCard.department',
            'toCard.department',
            'deliveredBy',
            'receivedBy'
        ])->latest()->paginate(20);

        return view('modules.inventory.transfers.index', compact('transfers'));
    }

    /**
     * Mostrar formulario para crear nuevo traspaso
     */
    public function createTransfer()
    {
        $cards = ResponsibilityCard::with('department')->where('status', 'active')->get();
        $users = User::all();

        return view('modules.inventory.transfers.create', compact('cards', 'users'));
    }

    /**
     * Guardar nuevo traspaso
     */
    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'from_card_id' => 'required|exists:responsibility_cards,id',
            'to_card_id' => 'required|exists:responsibility_cards,id|different:from_card_id',
            'delivered_by_user_id' => 'required|exists:users,id',
            'received_by_user_id' => 'required|exists:users,id',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['transfer_number'] = 'TP-' . strtoupper(Str::random(10));
        $validated['status'] = 'pending';

        $transfer = Transfer::create($validated);

        return redirect()
            ->route('inventory.transfers.show', $transfer)
            ->with('success', 'Traspaso registrado exitosamente.');
    }

    /**
     * Mostrar detalle de un traspaso
     */
    public function showTransfer(Transfer $transfer)
    {
        $transfer->load([
            'inventory',
            'fromCard.department',
            'toCard.department',
            'deliveredBy',
            'receivedBy'
        ]);

        return view('modules.inventory.transfers.show', compact('transfer'));
    }

    /**
     * Imprimir formato de traspaso para firma
     */
    public function printTransfer(Transfer $transfer)
    {
        $transfer->load([
            'inventory',
            'fromCard.department',
            'toCard.department',
            'deliveredBy',
            'receivedBy'
        ]);

        return view('modules.inventory.transfers.print', compact('transfer'));
    }

    /**
     * Subir documento firmado de traspaso
     */
    public function uploadSignedTransfer(Request $request, Transfer $transfer)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
        ]);

        // Eliminar documento anterior si existe
        if ($transfer->signed_document_path) {
            Storage::disk('public')->delete($transfer->signed_document_path);
        }

        $path = $request->file('document')->store('transfers', 'public');

        $transfer->update([
            'signed_document_path' => $path,
            'status' => 'completed',
        ]);

        // Actualizar departamento del bien
        $toCard = $transfer->toCard;
        $transfer->inventory->update([
            'department_id' => $toCard->department_id,
        ]);

        return back()->with('success', 'Documento firmado subido exitosamente. Traspaso completado.');
    }
}
