<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MetricsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the metrics dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Obtener datos para filtros
        $departments = Department::where('active', true)->orderBy('name')->get();
        $types = Inventory::distinct()->whereNotNull('type')->pluck('type')->sort()->values();
        $brands = Inventory::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        
        // Aplicar filtros
        $query = Inventory::query();
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        
        // Métricas generales
        $totalInventory = Inventory::count();
        $totalValue = Inventory::sum('purchase_price');
        $totalDepartments = Department::where('active', true)->count();
        $totalUsers = User::count();
        
        // Datos para gráficas
        $inventoryByDepartment = Inventory::select('departments.name', DB::raw('count(*) as total'))
            ->leftJoin('departments', 'inventory.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->get();
        
        $inventoryByType = Inventory::select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->get();
        
        $inventoryByBrand = Inventory::select('brand', DB::raw('count(*) as total'))
            ->whereNotNull('brand')
            ->groupBy('brand')
            ->get();
        
        $inventoryByStatus = Inventory::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        // Datos filtrados para gráficas
        $filteredInventoryByDepartment = clone $query;
        $filteredInventoryByDepartment = $filteredInventoryByDepartment
            ->select('departments.name', DB::raw('count(*) as total'))
            ->leftJoin('departments', 'inventory.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->get();
        
        $filteredInventoryByType = clone $query;
        $filteredInventoryByType = $filteredInventoryByType
            ->select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->get();
        
        $filteredInventoryByBrand = clone $query;
        $filteredInventoryByBrand = $filteredInventoryByBrand
            ->select('brand', DB::raw('count(*) as total'))
            ->whereNotNull('brand')
            ->groupBy('brand')
            ->get();
        
        // Contar filtros aplicados (sin incluir fechas)
        $filtersCount = collect($request->only(['department_id', 'type', 'brand']))->filter()->count();
        
        return view('Metrics.index', compact(
            'departments',
            'types',
            'brands',
            'totalInventory',
            'totalValue',
            'totalDepartments',
            'totalUsers',
            'inventoryByDepartment',
            'inventoryByType',
            'inventoryByBrand',
            'inventoryByStatus',
            'filteredInventoryByDepartment',
            'filteredInventoryByType',
            'filteredInventoryByBrand',
            'user',
            'filtersCount'
        ));
    }

    /**
     * Show expanded chart view.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function expand(Request $request, $chartId)
    {
        $user = auth()->user();
        
        // Obtener datos para filtros
        $departments = Department::where('active', true)->orderBy('name')->get();
        $types = Inventory::distinct()->whereNotNull('type')->pluck('type')->sort()->values();
        $brands = Inventory::distinct()->whereNotNull('brand')->pluck('brand')->sort()->values();
        
        // Aplicar filtros
        $query = Inventory::query();
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }
        
        // Datos filtrados para gráficas
        $filteredInventoryByDepartment = clone $query;
        $filteredInventoryByDepartment = $filteredInventoryByDepartment
            ->select('departments.name', DB::raw('count(*) as total'))
            ->leftJoin('departments', 'inventory.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->get();
        
        $filteredInventoryByType = clone $query;
        $filteredInventoryByType = $filteredInventoryByType
            ->select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->get();
        
        $filteredInventoryByBrand = clone $query;
        $filteredInventoryByBrand = $filteredInventoryByBrand
            ->select('brand', DB::raw('count(*) as total'))
            ->whereNotNull('brand')
            ->groupBy('brand')
            ->get();
        
        // Nombres de gráficas
        $chartNames = [
            'chart1' => 'Inventario por Departamento',
            'chart2' => 'Inventario por Tipo',
            'chart3' => 'Inventario por Marca',
        ];
        
        // Contar filtros aplicados (sin incluir fechas)
        $filtersCount = collect($request->only(['department_id', 'type', 'brand']))->filter()->count();
        
        return view('Metrics.expand', compact(
            'chartId',
            'departments',
            'types',
            'brands',
            'filteredInventoryByDepartment',
            'filteredInventoryByType',
            'filteredInventoryByBrand',
            'chartNames',
            'user',
            'filtersCount'
        ));
    }
}

