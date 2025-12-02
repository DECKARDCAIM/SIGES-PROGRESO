@extends('layouts.app')

@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('inventory.index') }}">Inventario</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $inventory->name }}</li>
                        </ol>
                    </nav>

                    <h1 class="page-header-title">{{ $inventory->name }}</h1>
                </div>

                <div class="col-sm-auto">
                    <div class="d-flex gap-2">
                        <a class="btn btn-white" href="{{ route('inventory.index') }}">
                            <i class="bi-arrow-left me-1"></i> Regresar
                        </a>
                        <a class="btn btn-white" href="{{ route('inventory.edit', $inventory) }}">
                            <i class="bi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs page-header-tabs mt-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#detalles" role="tab">
                        <i class="bi-info-circle me-1"></i> Detalles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#dictamenes-pendientes" role="tab">
                        <i class="bi-hourglass-split me-1"></i> Dictámenes Pendientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#dictamenes-completados" role="tab">
                        <i class="bi-clipboard-check me-1"></i> Dictámenes Completados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#traspasos" role="tab">
                        <i class="bi-arrow-left-right me-1"></i> Traspasos
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Page Header -->

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Detalles -->
            <div class="tab-pane fade show active" id="detalles" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <!-- Card Información -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-header-title">Información General</h4>
                            </div>

                            <div class="card-body">
                                <!-- Imágenes -->
                                @if(!empty($inventory->images))
                                <div class="row mb-4">
                                    @foreach($inventory->images as $index => $image)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="img-thumbnail rounded" 
                                             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;" 
                                             alt="Imagen del bien"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal{{ $index }}">
                                    </div>

                                    <!-- Modal para ver imagen en tamaño completo -->
                                    <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Imagen del Bien</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid" alt="Imagen del bien">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="alert alert-soft-secondary" role="alert">
                                    <i class="bi-image me-1"></i> No hay imágenes disponibles
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Número de Bien:</span><br>
                                            <span class="text-body fw-semibold">{{ $inventory->asset_number }}</span>
                                        </p>
                                    </div>

                                    @if($inventory->sku)
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">SKU:</span><br>
                                            <span class="text-body">{{ $inventory->sku }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    @if($inventory->description)
                                    <div class="col-12 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Descripción:</span><br>
                                            <span class="text-body">{{ $inventory->description }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    <div class="col-md-6 mb-3">
                                        @if($inventory->type)
                                        <p class="mb-1">
                                            <span class="text-muted">Tipo:</span><br>
                                            <span class="text-body">{{ $inventory->type }}</span>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        @if($inventory->condition)
                                        <p class="mb-1">
                                            <span class="text-muted">Condición:</span><br>
                                            <span class="text-body">{{ $inventory->condition }}</span>
                                        </p>
                                        @endif
                                    </div>

                                    @if($inventory->brand)
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Marca:</span><br>
                                            <span class="text-body">{{ $inventory->brand }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    @if($inventory->model)
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Modelo:</span><br>
                                            <span class="text-body">{{ $inventory->model }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    @if($inventory->serial_number)
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Número de Serie:</span><br>
                                            <span class="text-body">{{ $inventory->serial_number }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    <div class="col-md-6 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Cantidad:</span><br>
                                            <span class="text-body">{{ $inventory->quantity }} {{ $inventory->unit }}</span>
                                        </p>
                                    </div>

                                    @if($inventory->notes)
                                    <div class="col-12 mb-3">
                                        <p class="mb-1">
                                            <span class="text-muted">Notas:</span><br>
                                            <span class="text-body">{{ $inventory->notes }}</span>
                                        </p>
                                    </div>
                                    @endif

                                    @if($inventory->qr_path)
                                    <div class="col-12 mb-3">
                                        <p class="mb-2">
                                            <span class="text-muted">Código QR:</span>
                                        </p>
                                        <div class="text-center mb-2">
                                            <img src="{{ asset('storage/' . $inventory->qr_path) }}" 
                                                 alt="QR Code" 
                                                 class="img-thumbnail"
                                                 style="max-width: 250px; height: auto; border: 2px solid #e0e0e0;">
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center mb-2">
                                            <a href="{{ asset('storage/' . $inventory->qr_path) }}" 
                                               download="QR-{{ $inventory->asset_number }}{{ pathinfo($inventory->qr_path, PATHINFO_EXTENSION) ? '.' . pathinfo($inventory->qr_path, PATHINFO_EXTENSION) : '.png' }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="bi-download me-1"></i> Descargar QR
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-white"
                                                    onclick="window.print()">
                                                <i class="bi-printer me-1"></i> Imprimir
                                            </button>
                                        </div>
                                        <small class="text-muted d-block text-center">
                                            <i class="bi-info-circle me-1"></i>
                                            Escanea este código para acceder a los detalles del bien o busca por número: <strong>{{ $inventory->asset_number }}</strong>
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Compra -->
                        @if($inventory->purchase_price || $inventory->purchase_date || $inventory->vendor)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-header-title">Información de Compra</h4>
                            </div>

                            <div class="card-body">
                                <dl class="row">
                                    @if($inventory->purchase_price)
                                    <dt class="col-sm-3">Precio:</dt>
                                    <dd class="col-sm-9">Q.{{ number_format($inventory->purchase_price, 2) }}</dd>
                                    @endif

                                    @if($inventory->purchase_date)
                                    <dt class="col-sm-3">Fecha:</dt>
                                    <dd class="col-sm-9">{{ $inventory->purchase_date->format('d/m/Y') }}</dd>
                                    @endif

                                    @if($inventory->vendor)
                                    <dt class="col-sm-3">Proveedor:</dt>
                                    <dd class="col-sm-9">{{ $inventory->vendor }}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-4">
                        <!-- Card Estado -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-header-title">Estado</h4>
                            </div>

                            <div class="card-body">
                                @php
                                    $statusLabels = [
                                        'available' => 'Disponible',
                                        'assigned' => 'Asignado',
                                        'maintenance' => 'Mantenimiento',
                                        'retired' => 'Dado de baja'
                                    ];
                                @endphp
                                <p class="text-body fs-5 mb-0">
                                    {{ $statusLabels[$inventory->status] ?? $inventory->status }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Departamento -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="card-header-title">Asignación</h4>
                            </div>

                            <div class="card-body">
                                @if($inventory->department)
                                <p class="text-body mb-2"><strong>Departamento:</strong></p>
                                <p class="text-body fs-6 mb-3">{{ $inventory->department->name }}</p>

                                <a href="{{ route('inventory.responsibility-card.show', $inventory) }}" class="btn btn-white btn-sm w-100">
                                    <i class="bi-card-list me-1"></i> Ver Tarjeta de Responsabilidad
                                </a>
                                @else
                                <p class="text-muted mb-3">No asignado a ningún departamento</p>

                                <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#assignDepartmentModal">
                                    <i class="bi-plus-circle me-1"></i> Asignar Departamento
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Card Fechas -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-header-title">Fechas</h4>
                            </div>

                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-5">Creado:</dt>
                                    <dd class="col-sm-7">{{ $inventory->created_at->format('d/m/Y H:i') }}</dd>

                                    <dt class="col-sm-5">Actualizado:</dt>
                                    <dd class="col-sm-7">{{ $inventory->updated_at->format('d/m/Y H:i') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dictámenes Pendientes -->
            <div class="tab-pane fade" id="dictamenes-pendientes" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-header-title">Dictámenes Pendientes</h4>
                    </div>

                    <div class="card-body">
                        @php
                            $pendingAppraisals = $inventory->appraisals->where('status', 'pending');
                        @endphp

                        @if($pendingAppraisals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Número</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Perito</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingAppraisals as $appraisal)
                                    <tr>
                                        <td>{{ $appraisal->appraisal_number }}</td>
                                        <td><span class="badge bg-soft-warning">Pendiente</span></td>
                                        <td>{{ $appraisal->appraisal_date ? $appraisal->appraisal_date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $appraisal->appraiser->name ?? 'Sin asignar' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-white">
                                                <i class="bi-eye"></i> Ver
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi-clipboard-x" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">No hay dictámenes pendientes</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dictámenes Completados -->
            <div class="tab-pane fade" id="dictamenes-completados" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-header-title">Dictámenes Completados</h4>
                    </div>

                    <div class="card-body">
                        @php
                            $completedAppraisals = $inventory->appraisals->where('status', 'completed');
                        @endphp

                        @if($completedAppraisals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Número</th>
                                        <th>Fecha Completado</th>
                                        <th>Perito</th>
                                        <th>Valor Estimado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedAppraisals as $appraisal)
                                    <tr>
                                        <td>{{ $appraisal->appraisal_number }}</td>
                                        <td>{{ $appraisal->completion_date ? $appraisal->completion_date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $appraisal->appraiser->name ?? 'N/A' }}</td>
                                        <td>Q.{{ number_format($appraisal->estimated_value ?? 0, 2) }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('inventory.appraisals.print', $appraisal) }}" class="btn btn-white" target="_blank">
                                                    <i class="bi-printer"></i>
                                                </a>
                                                @if($appraisal->document_path)
                                                <a href="{{ route('inventory.appraisals.view', $appraisal) }}" class="btn btn-white" target="_blank">
                                                    <i class="bi-file-pdf"></i>
                                                </a>
                                                @endif
                                                <button type="button" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#uploadAppraisalModal{{ $appraisal->id }}">
                                                    <i class="bi-upload"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi-clipboard-check" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">No hay dictámenes completados</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Traspasos -->
            <div class="tab-pane fade" id="traspasos" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-header-title mb-0">Historial de Traspasos</h4>
                        <a href="{{ route('inventory.transfers.create') }}?inventory_id={{ $inventory->id }}" class="btn btn-primary btn-sm">
                            <i class="bi-plus me-1"></i> Nuevo Traspaso
                        </a>
                    </div>

                    <div class="card-body">
                        @if($inventory->transfers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-thead-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Número</th>
                                        <th>Desde</th>
                                        <th>Hacia</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventory->transfers as $transfer)
                                    <tr>
                                        <td>{{ $transfer->transfer_number }}</td>
                                        <td>{{ $transfer->fromCard->department->name ?? 'N/A' }}</td>
                                        <td>{{ $transfer->toCard->department->name ?? 'N/A' }}</td>
                                        <td>{{ $transfer->transfer_date->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $transferStatusColors = [
                                                    'pending' => 'warning',
                                                    'in_transit' => 'info',
                                                    'completed' => 'success'
                                                ];
                                                $transferStatusLabels = [
                                                    'pending' => 'Pendiente',
                                                    'in_transit' => 'En tránsito',
                                                    'completed' => 'Completado'
                                                ];
                                            @endphp
                                            <span class="badge bg-soft-{{ $transferStatusColors[$transfer->status] ?? 'secondary' }}">
                                                {{ $transferStatusLabels[$transfer->status] ?? $transfer->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('inventory.transfers.show', $transfer) }}" class="btn btn-white">
                                                    <i class="bi-eye"></i>
                                                </a>
                                                <a href="{{ route('inventory.transfers.print', $transfer) }}" class="btn btn-white" target="_blank">
                                                    <i class="bi-printer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi-arrow-left-right" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">No hay traspasos registrados</p>
                            <a href="{{ route('inventory.transfers.create') }}?inventory_id={{ $inventory->id }}" class="btn btn-primary">
                                <i class="bi-plus me-1"></i> Crear Primer Traspaso
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Asignar Departamento -->
<div class="modal fade" id="assignDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.assign-department', $inventory) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Seleccione el departamento</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Seleccione...</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
