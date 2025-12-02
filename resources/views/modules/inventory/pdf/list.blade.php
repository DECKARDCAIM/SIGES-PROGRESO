<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Bienes</title>
    <style>
        @page {
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 18pt;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 9pt;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8pt;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8pt;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        td {
            word-wrap: break-word;
            max-width: 100px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Listado de Bienes del Inventario</h1>
        <p><strong>Generado el:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Total de bienes:</strong> {{ $items->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 100px;">Número de Bien</th>
                <th style="width: 150px;">Nombre</th>
                <th style="width: 100px;">Tipo</th>
                <th style="width: 80px;">Marca</th>
                <th style="width: 100px;">Modelo</th>
                <th style="width: 120px;">Departamento</th>
                <th style="width: 80px;">Estado</th>
                <th style="width: 70px;">Cantidad</th>
                <th style="width: 80px;">Condición</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->asset_number }}</td>
                <td>{{ mb_strlen($item->name) > 30 ? mb_substr($item->name, 0, 30) . '...' : $item->name }}</td>
                <td>{{ $item->type ?? 'N/A' }}</td>
                <td>{{ $item->brand ?? 'N/A' }}</td>
                <td>{{ $item->model ?? 'N/A' }}</td>
                <td>{{ $item->department->name ?? 'Sin asignar' }}</td>
                <td>
                    @php
                        $statusLabels = [
                            'available' => 'Disponible',
                            'assigned' => 'Asignado',
                            'maintenance' => 'Mantenimiento',
                            'retired' => 'Dado de baja'
                        ];
                    @endphp
                    {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                </td>
                <td>{{ $item->quantity }} {{ $item->unit }}</td>
                <td>{{ $item->condition ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Inventarios y Solicitudes de Mantenimiento</p>
    </div>
</body>
</html>

