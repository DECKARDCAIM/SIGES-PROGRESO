@extends('layouts.app')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center mb-3">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title">
                            Gestión de Bienes
                            <span class="badge bg-soft-dark text-dark ms-2">{{ $totalItems }}</span>
                        </h1>

                        <div class="mt-2">
                            <a class="text-body me-3" href="javascript:;" id="exportHeaderDummy">
                                <i class="bi-download me-1"></i> Exportar
                            </a>
                            <a class="text-body" href="javascript:;" id="importTrigger">
                                <i class="bi-upload me-1"></i> Importar
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('inventory.create') }}">
                            <i class="bi-plus me-1"></i> Agregar Bien
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row mb-3">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center gx-2 mb-1">
                                    <div class="col-6">
                                        <h2 class="card-title text-inherit">{{ $totalItems }}</h2>
                                    </div>
                                    <div class="col-6">
                                        <div class="chartjs-custom" style="height: 3rem;">
                                            <i class="bi-box-seam text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block fs-6">Total de Bienes</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center gx-2 mb-1">
                                    <div class="col-6">
                                        <h2 class="card-title text-inherit">{{ $availableItems }}</h2>
                                    </div>
                                    <div class="col-6">
                                        <div class="chartjs-custom" style="height: 3rem;">
                                            <i class="bi-check-circle text-success" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block fs-6">Disponibles</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center gx-2 mb-1">
                                    <div class="col-6">
                                        <h2 class="card-title text-inherit">{{ $assignedItems }}</h2>
                                    </div>
                                    <div class="col-6">
                                        <div class="chartjs-custom" style="height: 3rem;">
                                            <i class="bi-clipboard-check text-info" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block fs-6">Asignados</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center gx-2 mb-1">
                                    <div class="col-6">
                                        <h2 class="card-title text-inherit">{{ $deletedItems ?? 0 }}</h2>
                                    </div>
                                    <div class="col-6">
                                        <div class="chartjs-custom" style="height: 3rem;">
                                            <i class="bi-trash text-danger" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block fs-6">Dados de Baja</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Card -->
            <div class="card">
                <div class="card-header card-header-content-md-between">
                    <div class="mb-2 mb-md-0">
                        <form method="GET" action="{{ route('inventory.index') }}" id="searchForm">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input type="search" name="search" class="form-control"
                                    placeholder="Buscar por descripción, número de bien, nombre o número de serie..."
                                    value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi-search me-1"></i> Buscar
                                </button>
                            </div>
                            <!-- Mantener filtros en la URL -->
                            @if (request()->hasAny(['type', 'brand', 'model', 'status', 'department_id', 'condition', 'per_page']))
                                @foreach (request()->only(['type', 'brand', 'model', 'status', 'department_id', 'condition', 'per_page']) as $key => $value)
                                    @if ($value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                            @endif
                        </form>
                    </div>

                    <div class="d-grid d-sm-flex gap-2 align-items-center">
                        <!-- Filtros -->
                        <button class="btn btn-white btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filtersCollapse">
                            <i class="bi-filter me-1"></i> Filtros
                            @if (request()->hasAny(['type', 'brand', 'model', 'status', 'department_id', 'condition']))
                                <span
                                    class="badge bg-primary ms-1">{{ collect(request()->only(['type', 'brand', 'model', 'status', 'department_id', 'condition']))->filter()->count() }}</span>
                            @endif
                        </button>

                        <!-- Columnas -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="columnsDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-table me-1"></i> Columnas
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-card" aria-labelledby="columnsDropdown" style="width: 20rem;">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-3">
                                            <label class="row form-check form-switch" for="toggleColumn_description">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Descripción del bien</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_description" data-column="1" checked>
                                                </span>
                                            </label>
                                            <label class="row form-check form-switch" for="toggleColumn_asset">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Número de bien</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_asset" data-column="2" checked>
                                                </span>
                                            </label>
                                            <label class="row form-check form-switch" for="toggleColumn_type">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Tipo</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_type" data-column="3" checked>
                                                </span>
                                            </label>
                                            <label class="row form-check form-switch" for="toggleColumn_department">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Departamento</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_department" data-column="4" checked>
                                                </span>
                                            </label>
                                            <label class="row form-check form-switch" for="toggleColumn_status">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Estado</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_status" data-column="5" checked>
                                                </span>
                                            </label>
                                            <label class="row form-check form-switch" for="toggleColumn_quantity">
                                                <span class="col-8 col-sm-9 ms-0">
                                                    <span class="me-2">Cantidad</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type="checkbox" class="form-check-input js-column-toggle"
                                                        id="toggleColumn_quantity" data-column="6" checked>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exportar y Acciones (solo cuando haya seleccionados) -->
                        <div id="exportWrapper" class="d-flex align-items-center gap-2" style="display: none;">
                            <div class="dropdown">
                                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-download me-2"></i> Exportar
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown">
                                    <span class="dropdown-header">Opciones</span>
                                    <a id="export-copy" class="dropdown-item" href="javascript:;">
                                        <i class="bi-clipboard me-2"></i> Copiar
                                    </a>
                                    <a id="export-print" class="dropdown-item" href="javascript:;">
                                        <i class="bi-printer me-2"></i> Imprimir
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-header">Descargar</span>
                                    <a id="export-excel" class="dropdown-item" href="javascript:;">
                                        <i class="bi-file-earmark-excel me-2 text-success"></i> Excel (plantilla)
                                    </a>
                                    <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                        <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF (seleccionados)
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Acciones (solo cuando haya seleccionados) -->
                            <div class="dropdown" id="actionsDropdownWrapper" style="display: none;">
                                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="actionsDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-gear me-2"></i> Acciones
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="actionsDropdown">
                                    <span class="dropdown-header">Acciones masivas</span>
                                    @if (request('status') === 'retired')
                                        <a id="bulk-restore" class="dropdown-item" href="javascript:;">
                                            <i class="bi-arrow-clockwise me-2"></i> Reactivar
                                        </a>
                                    @else
                                        <a id="bulk-delete" class="dropdown-item text-danger" href="javascript:;">
                                            <i class="bi-trash me-2"></i> Eliminar
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Contador seleccionados -->
                            <span id="selectedCountWrapper" class="text-body small d-flex align-items-center gap-1" style="display: none;">
                                (<span id="selectedCount">0</span>)
                                <button type="button" id="clearSelection" class="btn btn-link btn-sm p-0 text-muted" style="line-height: 1; font-size: 0.75rem; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'" title="Limpiar selección">
                                    <i class="bi-x-lg"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Filtros Colapsables -->
                <div class="collapse" id="filtersCollapse">
                    <div class="card-body border-bottom">
                        <form method="GET" action="{{ route('inventory.index') }}" id="filtersForm">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if (request('per_page'))
                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                            @endif
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Tipo</label>
                                    <select name="type" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}"
                                                {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Marca</label>
                                    <select name="brand" class="form-select">
                                        <option value="">Todas</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand }}"
                                                {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" name="model" class="form-control"
                                        value="{{ request('model') }}" placeholder="Buscar modelo...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estado</label>
                                    <select name="status" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="available"
                                            {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>
                                            Asignado</option>
                                        <option value="maintenance"
                                            {{ request('status') == 'maintenance' ? 'selected' : '' }}>Mantenimiento
                                        </option>
                                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>
                                            Dado de baja</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Departamento</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Condición</label>
                                    <select name="condition" class="form-select">
                                        <option value="">Todas</option>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition }}"
                                                {{ request('condition') == $condition ? 'selected' : '' }}>
                                                {{ $condition }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi-search me-1"></i> Aplicar Filtros
                                        </button>
                                        <a href="{{ route('inventory.index') }}" class="btn btn-white">
                                            <i class="bi-x-lg me-1"></i> Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="table-column-pe-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="selectAll">
                                        <label class="form-check-label" for="selectAll"></label>
                                    </div>
                                </th>
                                <th data-col-index="1">Descripción del Bien</th>
                                <th data-col-index="2">Número de Bien</th>
                                <th data-col-index="3">Tipo</th>
                                <th data-col-index="4">Departamento</th>
                                <th data-col-index="5">Estado</th>
                                <th data-col-index="6">Cantidad</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="table-column-pe-0">
                                        <div class="form-check">
                                            <input class="form-check-input item-checkbox" type="checkbox"
                                                value="{{ $item->id }}" name="selected_items[]"
                                                id="itemCheck{{ $item->id }}">
                                            <label class="form-check-label" for="itemCheck{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td data-col-index="1" class="table-column-ps-0">
                                        <a class="d-flex align-items-center" href="{{ route('inventory.show', $item) }}">
                                            <div class="flex-shrink-0">
                                                @if (!empty($item->images) && isset($item->images[0]))
                                                    <img class="avatar avatar-lg"
                                                        src="{{ asset('storage/' . $item->images[0]) }}"
                                                        alt="{{ $item->name }}" style="object-fit: cover;">
                                                @else
                                                    <div class="avatar avatar-lg avatar-soft-primary">
                                                        <span class="avatar-initials">
                                                            <i class="bi-box-seam"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="text-hover-primary mb-0">{{ $item->name }}</h5>
                                                <span
                                                    class="text-body fs-6">{{ Str::limit($item->description, 50) }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td data-col-index="2">
                                        <span class="text-body fw-semibold">{{ $item->asset_number }}</span>
                                    </td>
                                    <td data-col-index="3">{{ $item->type ?? 'N/A' }}</td>
                                    <td data-col-index="4">
                                        @if ($item->department)
                                            <span class="text-body">{{ $item->department->name }}</span>
                                        @else
                                            <span class="text-muted">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td data-col-index="5">
                                        @php
                                            // Si estamos viendo bienes dados de baja (soft-deleted), mostrar "Dado de baja"
                                            if (request('status') === 'retired' || $item->trashed()) {
                                                $displayStatus = 'Dado de baja';
                                            } else {
                                                $statusLabels = [
                                                    'available' => 'Disponible',
                                                    'assigned' => 'Asignado',
                                                    'maintenance' => 'Mantenimiento',
                                                    'retired' => 'Dado de baja',
                                                ];
                                                $displayStatus = $statusLabels[$item->status] ?? ucfirst($item->status);
                                            }
                                        @endphp
                                        <span class="text-body fw-semibold">
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                    <td data-col-index="6">{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            @if (request('status') === 'retired' || $item->trashed())
                                                {{-- Botón para reactivar bien dado de baja --}}
                                                <button type="button" class="btn btn-white btn-sm"
                                                    onclick="if(confirm('¿Está seguro de reactivar este bien?')) { document.getElementById('restore-form-{{ $item->id }}').submit(); }"
                                                    title="Reactivar">
                                                    <i class="bi-arrow-clockwise"></i>
                                                </button>
                                                <form id="restore-form-{{ $item->id }}"
                                                    action="{{ route('inventory.restore', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('POST')
                                                </form>
                                            @else
                                                <a class="btn btn-white btn-sm" href="{{ route('inventory.show', $item) }}">
                                                    <i class="bi-eye"></i>
                                                </a>
                                                <a class="btn btn-white btn-sm" href="{{ route('inventory.edit', $item) }}">
                                                    <i class="bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-white btn-sm"
                                                    onclick="if(confirm('¿Está seguro de dar de baja este bien?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }">
                                                    <i class="bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('inventory.destroy', $item) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-center">
                                            <i class="bi-box-seam" style="font-size: 3rem; color: #ccc;"></i>
                                            <p class="mt-3 text-muted">No hay bienes registrados</p>
                                            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                                <i class="bi-plus me-1"></i> Agregar Primer Bien
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- End Table -->

                <!-- Footer -->
                <div class="card-footer">
                    <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                        <div class="col-sm mb-2 mb-sm-0">
                            <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                <span class="me-2">Mostrando:</span>

                                <!-- Select -->
                                <div class="tom-select-custom">
                                    <select id="perPageSelect" class="js-select form-select form-select-borderless w-auto"
                                        autocomplete="off"
                                        data-hs-tom-select-options='{
                            "searchInDropdown": false,
                            "hideSearch": true
                          }'>
                                        <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25
                                        </option>
                                        <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50
                                        </option>
                                        <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                                <!-- End Select -->

                                <span class="text-secondary me-2">de</span>

                                <!-- Pagination Quantity -->
                                <span>
                                    @if ($items->total() > 0)
                                        {{ $items->firstItem() }} a {{ $items->lastItem() }} de {{ $items->total() }}
                                        resultados
                                    @else
                                        0 resultados
                                    @endif
                                </span>
                            </div>
                        </div>
                        <!-- End Col -->

                        <div class="col-sm-auto">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                <!-- Pagination -->
                                {{ $items->links() }}
                            </div>
                        </div>
                        <!-- End Col -->
                    </div>
                    <!-- End Row -->
                </div>
                <!-- End Footer -->
            </div>
            <!-- End Card -->
        </div>
    </main>

    <form id="pdfForm" action="{{ route('inventory.generate-pdf') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="selected_items" id="selectedItemsInput">
    </form>
    <form id="excelForm" action="{{ route('inventory.generate-excel') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="selected_items" id="selectedItemsExcel">
    </form>
    <form id="importForm" action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data"
        style="display: none;">
        @csrf
        <input type="file" name="file" id="importFile" accept=".csv">
    </form>
    
    <!-- Formulario para eliminar múltiples bienes -->
    <form id="bulkDeleteForm" action="{{ route('inventory.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="selected_items" id="bulkDeleteItems">
    </form>
    
    <!-- Formulario para reactivar múltiples bienes -->
    <form id="bulkRestoreForm" action="{{ route('inventory.bulk-restore') }}" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="selected_items" id="bulkRestoreItems">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const exportPdfLink = document.getElementById('export-pdf');
            const exportExcelLink = document.getElementById('export-excel');
            const exportCopyLink = document.getElementById('export-copy');
            const exportPrintLink = document.getElementById('export-print');
            const selectedCount = document.getElementById('selectedCount');
            const pdfForm = document.getElementById('pdfForm');
            const selectedItemsInput = document.getElementById('selectedItemsInput');
            const excelForm = document.getElementById('excelForm');
            const selectedItemsExcel = document.getElementById('selectedItemsExcel');
            const selectedCountWrapper = document.getElementById('selectedCountWrapper');
            const clearSelectionBtn = document.getElementById('clearSelection');
            const importTrigger = document.getElementById('importTrigger');
            const importFile = document.getElementById('importFile');
            const importForm = document.getElementById('importForm');
            const columnToggles = document.querySelectorAll('.js-column-toggle');
            const searchForm = document.getElementById('searchForm');
            const filtersForm = document.getElementById('filtersForm');
            const searchInput = searchForm.querySelector('input[name="search"]');
            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
            const exportHeaderDummy = document.getElementById('exportHeaderDummy');
            
            // Guardar el valor anterior para detectar cuando se limpia
            let previousSearchValue = searchInput.value;
            
            // Guardar selección antes de enviar formulario de filtros
            if (filtersForm) {
                filtersForm.addEventListener('submit', function(e) {
                    try {
                        localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                    } catch (e) {}
                });
            }

            // Descargar template vacío desde el header
            if (exportHeaderDummy) {
                exportHeaderDummy.addEventListener('click', function() {
                    window.location.href = '{{ route("inventory.download-template") }}';
                });
            }

            // Guardar selección antes de enviar formulario de búsqueda
            searchForm.addEventListener('submit', function(e) {
                try {
                    localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                } catch (e) {}
            });

            // Búsqueda solo al presionar Enter o botón de búsqueda
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    try {
                        localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                    } catch (e) {}
                    searchForm.submit();
                }
            });

            // Cuando se limpia el campo de búsqueda (clic en la X), enviar automáticamente
            searchInput.addEventListener('search', function() {
                if (this.value === '') {
                    try {
                        localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                    } catch (e) {}
                    searchForm.submit();
                }
            });

            // Detectar cuando se limpia el campo
            searchInput.addEventListener('input', function() {
                if (previousSearchValue && this.value === '') {
                    setTimeout(function() {
                        if (searchInput.value === '') {
                            try {
                                localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                            } catch (e) {}
                            searchForm.submit();
                        }
                    }, 100);
                }
                previousSearchValue = this.value;
            });

            // Cargar IDs seleccionados desde localStorage
            let selectedIds = new Set();
            try {
                const saved = localStorage.getItem('inventory_selected_ids');
                if (saved) {
                    const savedIds = JSON.parse(saved);
                    savedIds.forEach(id => {
                        selectedIds.add(String(id));
                    });
                }
            } catch (e) {}

            // Marcar checkboxes según lo guardado
            checkboxes.forEach(cb => {
                if (selectedIds.has(cb.value)) {
                    cb.checked = true;
                }
            });

            // Restaurar estado de "seleccionar todo" si todos los checkboxes de esta página están marcados
            if (selectAll && checkboxes.length > 0) {
                const allChecked = Array.from(checkboxes).every(cb => selectedIds.has(cb.value));
                selectAll.checked = allChecked;
            }

            // Seleccionar/deseleccionar todos
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const existingIds = Array.from(checkboxes).map(cb => cb.value);
                    
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                        if (this.checked) {
                            selectedIds.add(checkbox.value);
                        } else {
                            if (existingIds.includes(checkbox.value)) {
                                selectedIds.delete(checkbox.value);
                            }
                        }
                    });
                    
                    updateSelectedCount();
                });
            }

            // Actualizar contador cuando se selecciona un checkbox individual
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedIds.add(this.value);
                    } else {
                        selectedIds.delete(this.value);
                    }
                    updateSelectAllState();
                    updateSelectedCount();
                });
            });

            function updateSelectAllState() {
                if (!selectAll || checkboxes.length === 0) return;
                const allChecked = Array.from(checkboxes).every(cb => selectedIds.has(cb.value));
                selectAll.checked = allChecked;
                selectAll.indeterminate = false;
            }

            function updateSelectedCount() {
                const count = selectedIds.size;

                selectedCount.textContent = count;

                if (count > 0) {
                    if (selectedCountWrapper) selectedCountWrapper.style.display = 'flex';
                } else {
                    if (selectedCountWrapper) selectedCountWrapper.style.display = 'none';
                }

                const exportWrapper = document.getElementById('exportWrapper');
                if (exportWrapper) {
                    exportWrapper.style.display = count > 0 ? 'flex' : 'none';
                }
                
                // Mostrar/ocultar dropdown de acciones
                const actionsDropdownWrapper = document.getElementById('actionsDropdownWrapper');
                if (actionsDropdownWrapper) {
                    actionsDropdownWrapper.style.display = count > 0 ? 'block' : 'none';
                }

                try {
                    localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                } catch (e) {}
            }

            // Limpiar toda la selección
            if (clearSelectionBtn) {
                clearSelectionBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    
                    if (selectAll) {
                        selectAll.checked = false;
                    }
                    
                    selectedIds.clear();
                    updateSelectedCount();
                });
            }

            function getSelectedIds() {
                return Array.from(selectedIds);
            }

            // Generar PDF
            if (exportPdfLink) {
                exportPdfLink.addEventListener('click', function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Por favor, seleccione al menos un bien.');
                        return;
                    }
                    selectedItemsInput.value = JSON.stringify(selected);
                    pdfForm.submit();
                });
            }

            if (exportExcelLink) {
                exportExcelLink.addEventListener('click', function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Por favor, seleccione al menos un bien.');
                        return;
                    }
                    selectedItemsExcel.value = JSON.stringify(selected);
                    excelForm.submit();
                });
            }

            // Copiar al portapapeles
            if (exportCopyLink) {
                exportCopyLink.addEventListener('click', async function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Seleccione al menos un bien para copiar.');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('inventory.get-selected-items') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                selected_items: JSON.stringify(selected)
                            })
                        });

                        const data = await response.json();
                        if (!data.items || data.items.length === 0) {
                            alert('No se encontraron bienes seleccionados.');
                            return;
                        }

                        // Obtener encabezados visibles de la tabla
                        const headerCells = document.querySelectorAll('thead th[data-col-index]');
                        const headers = Array.from(headerCells).map(th => th.innerText.trim());

                        // Construir texto tabulado
                        const lines = [
                            headers.join('\\t'),
                            ...data.items.map(item => [
                                item.description_full || item.name || item.description || '',
                                item.asset_number || '',
                                item.type || '',
                                item.department || '',
                                item.status || '',
                                item.quantity || ''
                            ].join('\\t'))
                        ];

                        const text = lines.join('\\n');

                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(text);
                        } else {
                            const textarea = document.createElement('textarea');
                            textarea.value = text;
                            document.body.appendChild(textarea);
                            textarea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textarea);
                        }
                    } catch (error) {
                        alert('Error al copiar los datos.');
                    }
                });
            }

            // Imprimir seleccionados
            if (exportPrintLink) {
                exportPrintLink.addEventListener('click', async function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Seleccione al menos un bien para imprimir.');
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('inventory.get-selected-items') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                selected_items: JSON.stringify(selected)
                            })
                        });

                        const data = await response.json();
                        if (!data.items || data.items.length === 0) {
                            alert('No se encontraron bienes seleccionados.');
                            return;
                        }

                        // Obtener encabezados visibles de la tabla
                        const headerCells = document.querySelectorAll('thead th[data-col-index]');
                        const headers = Array.from(headerCells).map(th => th.innerText.trim());

                        const printWindow = window.open('', '_blank');
                        let html = '<html><head><title>Listado de bienes</title>';
                        html +=
                            '<style>table{width:100%;border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;}th,td{border:1px solid #ddd;padding:4px;text-align:left;}th{background:#f2f2f2;}</style>';
                        html += '</head><body>';
                        html += '<h3>Listado de bienes seleccionados</h3>';
                        html += '<table><thead><tr>';

                        headers.forEach(header => {
                            html += '<th>' + header + '</th>';
                        });
                        html += '</tr></thead><tbody>';

                        data.items.forEach(item => {
                            html += '<tr>';
                            html += '<td>' + (item.description_full || item.name || item
                                .description || '') + '</td>';
                            html += '<td>' + (item.asset_number || '') + '</td>';
                            html += '<td>' + (item.type || '') + '</td>';
                            html += '<td>' + (item.department || '') + '</td>';
                            html += '<td>' + (item.status || '') + '</td>';
                            html += '<td>' + (item.quantity || '') + '</td>';
                            html += '</tr>';
                        });

                        html += '</tbody></table></body></html>';
                        printWindow.document.open();
                        printWindow.document.write(html);
                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
                    } catch (error) {
                        alert('Error al imprimir los datos.');
                    }
                });
            }

            // Importar CSV
            if (importTrigger && importFile && importForm) {
                importTrigger.addEventListener('click', function() {
                    importFile.click();
                });

                importFile.addEventListener('change', function() {
                    if (importFile.files.length > 0) {
                        importForm.submit();
                    }
                });
            }

            // Mostrar/ocultar columnas + guardar prefs
            if (columnToggles.length) {
                columnToggles.forEach(toggle => {
                    toggle.addEventListener('change', function() {
                        const column = this.getAttribute('data-column');
                        const visible = this.checked;
                        document.querySelectorAll('[data-col-index=\"' + column + '\"]').forEach(
                            cell => {
                                cell.style.display = visible ? '' : 'none';
                            });

                        const prefs = {};
                        columnToggles.forEach(t => {
                            const key = t.id.replace('toggleColumn_', '');
                            prefs[key] = t.checked;
                        });

                        fetch('{{ route('inventory.columns-preferences') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                columns: prefs
                            }),
                        }).catch(() => {});
                    });
                });
            }

            // Aplicar preferencias iniciales de columnas al cargar
            columnToggles.forEach(toggle => {
                const column = toggle.getAttribute('data-column');
                const visible = toggle.checked;
                document.querySelectorAll('[data-col-index=\"' + column + '\"]').forEach(cell => {
                    cell.style.display = visible ? '' : 'none';
                });
            });

            // Acciones masivas
            const bulkDeleteBtn = document.getElementById('bulk-delete');
            const bulkRestoreBtn = document.getElementById('bulk-restore');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkRestoreForm = document.getElementById('bulkRestoreForm');
            const bulkDeleteItems = document.getElementById('bulkDeleteItems');
            const bulkRestoreItems = document.getElementById('bulkRestoreItems');

            // Eliminar múltiples bienes
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Por favor, seleccione al menos un bien.');
                        return;
                    }
                    
                    if (confirm(`¿Está seguro de dar de baja ${selected.length} bien(es) seleccionado(s)?`)) {
                        bulkDeleteItems.value = JSON.stringify(selected);
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        if (selectAll) {
                            selectAll.checked = false;
                        }
                        selectedIds.clear();
                        try {
                            localStorage.removeItem('inventory_selected_ids');
                        } catch (e) {}
                        updateSelectedCount();
                        bulkDeleteForm.submit();
                    }
                });
            }

            // Reactivar múltiples bienes
            if (bulkRestoreBtn) {
                bulkRestoreBtn.addEventListener('click', function() {
                    const selected = getSelectedIds();
                    if (selected.length === 0) {
                        alert('Por favor, seleccione al menos un bien.');
                        return;
                    }
                    
                    if (confirm(`¿Está seguro de reactivar ${selected.length} bien(es) seleccionado(s)?`)) {
                        bulkRestoreItems.value = JSON.stringify(selected);
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        if (selectAll) {
                            selectAll.checked = false;
                        }
                        selectedIds.clear();
                        try {
                            localStorage.removeItem('inventory_selected_ids');
                        } catch (e) {}
                        updateSelectedCount();
                        bulkRestoreForm.submit();
                    }
                });
            }

            // Inicializar export/contador con lo que haya guardado
            updateSelectedCount();
            
            // Guardar selección antes de navegar por paginación
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.pagination a, .pagination button, [href*="page="]');
                if (paginationLink && paginationLink.href) {
                    try {
                        localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                    } catch (e) {}
                }
            }, true);

            // Selector de cantidad de registros por página
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    try {
                        localStorage.setItem('inventory_selected_ids', JSON.stringify(Array.from(selectedIds)));
                    } catch (e) {}
                    const perPage = this.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });
            }
        });
    </script>
@endsection
