<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Inventory;
use App\Models\User;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Tecnologías de la Información',
                'code' => 'TI-001',
                'description' => 'Departamento encargado de la infraestructura tecnológica',
                'location' => 'Edificio A, Piso 3',
                'active' => true,
            ],
            [
                'name' => 'Recursos Humanos',
                'code' => 'RH-001',
                'description' => 'Departamento de gestión del personal',
                'location' => 'Edificio B, Piso 2',
                'active' => true,
            ],
            [
                'name' => 'Administración',
                'code' => 'ADM-001',
                'description' => 'Departamento administrativo',
                'location' => 'Edificio A, Piso 1',
                'active' => true,
            ],
            [
                'name' => 'Mantenimiento',
                'code' => 'MAN-001',
                'description' => 'Departamento de mantenimiento general',
                'location' => 'Edificio C',
                'active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Obtener departamentos creados
        $tiDept = Department::where('code', 'TI-001')->first();
        $rhDept = Department::where('code', 'RH-001')->first();
        $admDept = Department::where('code', 'ADM-001')->first();

        // Crear bienes de ejemplo
        $items = [
            [
                'name' => 'Laptop Dell Latitude 5520',
                'asset_number' => 'LAP-2024-001',
                'sku' => 'DELL-LAT-5520',
                'description' => 'Laptop empresarial con procesador Intel Core i7, 16GB RAM, SSD 512GB',
                'type' => 'Equipo de Cómputo',
                'brand' => 'Dell',
                'model' => 'Latitude 5520',
                'serial_number' => 'SN-DELL-2024-001',
                'purchase_price' => 25000.00,
                'purchase_date' => '2024-01-15',
                'vendor' => 'Distribuidora Dell México',
                'quantity' => 1,
                'unit' => 'pza',
                'status' => 'assigned',
                'condition' => 'Nuevo',
                'notes' => 'Asignado a gerente de TI',
                'department_id' => $tiDept->id,
            ],
            [
                'name' => 'Monitor LG UltraWide 34"',
                'asset_number' => 'MON-2024-001',
                'sku' => 'LG-UW-34',
                'description' => 'Monitor ultrawide de 34 pulgadas, resolución 2560x1080',
                'type' => 'Equipo de Cómputo',
                'brand' => 'LG',
                'model' => '34WN80C-B',
                'serial_number' => 'SN-LG-2024-001',
                'purchase_price' => 8500.00,
                'purchase_date' => '2024-02-10',
                'vendor' => 'Electrónica Total',
                'quantity' => 5,
                'unit' => 'pza',
                'status' => 'available',
                'condition' => 'Nuevo',
                'notes' => 'Stock para área de desarrollo',
                'department_id' => $tiDept->id,
            ],
            [
                'name' => 'Escritorio Ejecutivo',
                'asset_number' => 'MUE-2024-001',
                'sku' => 'ESC-EJEC-180',
                'description' => 'Escritorio ejecutivo de madera color nogal, 180cm x 80cm',
                'type' => 'Mobiliario',
                'brand' => 'Officeline',
                'model' => 'Ejecutivo Premium',
                'serial_number' => null,
                'purchase_price' => 12000.00,
                'purchase_date' => '2024-01-20',
                'vendor' => 'Muebles para Oficina SA',
                'quantity' => 10,
                'unit' => 'pza',
                'status' => 'assigned',
                'condition' => 'Nuevo',
                'notes' => 'Distribuidos en diferentes áreas',
                'department_id' => $admDept->id,
            ],
            [
                'name' => 'Impresora HP LaserJet Pro',
                'asset_number' => 'IMP-2024-001',
                'sku' => 'HP-LJ-M404',
                'description' => 'Impresora láser monocromática, red ethernet, dúplex automático',
                'type' => 'Equipo de Oficina',
                'brand' => 'HP',
                'model' => 'LaserJet Pro M404dn',
                'serial_number' => 'SN-HP-2024-001',
                'purchase_price' => 6500.00,
                'purchase_date' => '2024-03-05',
                'vendor' => 'HP Store México',
                'quantity' => 3,
                'unit' => 'pza',
                'status' => 'assigned',
                'condition' => 'Nuevo',
                'notes' => 'Una por piso',
                'department_id' => $admDept->id,
            ],
            [
                'name' => 'Silla Ergonómica',
                'asset_number' => 'MUE-2024-002',
                'sku' => 'SIL-ERG-MESH',
                'description' => 'Silla ergonómica con respaldo de malla, apoyabrazos ajustables',
                'type' => 'Mobiliario',
                'brand' => 'ErgoMax',
                'model' => 'Comfort Pro',
                'serial_number' => null,
                'purchase_price' => 4500.00,
                'purchase_date' => '2024-02-15',
                'vendor' => 'Ergonomía Total',
                'quantity' => 25,
                'unit' => 'pza',
                'status' => 'assigned',
                'condition' => 'Nuevo',
                'notes' => 'Distribuidas en todas las áreas',
                'department_id' => $rhDept->id,
            ],
            [
                'name' => 'Proyector Epson',
                'asset_number' => 'PRO-2024-001',
                'sku' => 'EPS-X06',
                'description' => 'Proyector multimedia 3600 lúmenes, HDMI, USB',
                'type' => 'Equipo Audiovisual',
                'brand' => 'Epson',
                'model' => 'PowerLite X06+',
                'serial_number' => 'SN-EPS-2024-001',
                'purchase_price' => 15000.00,
                'purchase_date' => '2024-01-25',
                'vendor' => 'Audiovisual Profesional',
                'quantity' => 2,
                'unit' => 'pza',
                'status' => 'available',
                'condition' => 'Nuevo',
                'notes' => 'Para salas de juntas',
                'department_id' => $admDept->id,
            ],
            [
                'name' => 'Switch de Red 24 Puertos',
                'asset_number' => 'RED-2024-001',
                'sku' => 'SW-24P-GIG',
                'description' => 'Switch gigabit 24 puertos, administrable, PoE+',
                'type' => 'Equipo de Red',
                'brand' => 'Cisco',
                'model' => 'SG350-24P',
                'serial_number' => 'SN-CIS-2024-001',
                'purchase_price' => 18000.00,
                'purchase_date' => '2024-02-01',
                'vendor' => 'Cisco Partner México',
                'quantity' => 3,
                'unit' => 'pza',
                'status' => 'assigned',
                'condition' => 'Nuevo',
                'notes' => 'Instalados en cuartos de telecomunicaciones',
                'department_id' => $tiDept->id,
            ],
            [
                'name' => 'Aire Acondicionado',
                'asset_number' => 'CLI-2024-001',
                'sku' => 'AC-INV-12K',
                'description' => 'Aire acondicionado tipo mini split, 12000 BTU, inverter',
                'type' => 'Equipo de Climatización',
                'brand' => 'LG',
                'model' => 'Dual Inverter',
                'serial_number' => 'SN-AC-2024-001',
                'purchase_price' => 12000.00,
                'purchase_date' => '2024-03-01',
                'vendor' => 'Clima Confort',
                'quantity' => 8,
                'unit' => 'pza',
                'status' => 'maintenance',
                'condition' => 'Usado',
                'notes' => 'Requiere mantenimiento preventivo anual',
                'department_id' => null,
            ],
        ];

        foreach ($items as $item) {
            Inventory::create($item);
        }

        // Crear bienes adicionales genéricos para probar paginación
        for ($i = 1; $i <= 50; $i++) {
            Inventory::create([
                'name' => 'Bien genérico '.$i,
                'asset_number' => 'GEN-'.str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'sku' => null,
                'description' => 'Bien genérico de prueba para paginación (registro '.$i.').',
                'type' => 'Genérico',
                'brand' => 'Sin marca',
                'model' => 'Modelo '.$i,
                'serial_number' => null,
                'purchase_price' => 0,
                'purchase_date' => null,
                'vendor' => null,
                'quantity' => 1,
                'unit' => 'pza',
                'status' => 'available',
                'condition' => 'Nuevo',
                'notes' => null,
                'department_id' => null,
                'images' => [],
            ]);
        }

        $this->command->info('✅ Departamentos y bienes de inventario creados exitosamente!');
        $this->command->info('📦 Total de departamentos: ' . Department::count());
        $this->command->info('📦 Total de bienes: ' . Inventory::count());
    }
}
