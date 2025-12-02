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
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('inventory.show', $inventory) }}">{{ $inventory->name }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>

                    <h1 class="page-header-title">Editar Bien</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <form action="{{ route('inventory.update', $inventory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <!-- Card -->
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-header-title">Información del Bien</h4>
                        </div>

                        <div class="card-body">
                            <!-- Nombre -->
                            <div class="mb-4">
                                <label for="name" class="form-label">
                                    Nombre del Bien <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $inventory->name) }}" 
                                       placeholder="Ej: Laptop HP Pavilion" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Número de Bien -->
                                    <div class="mb-4">
                                        <label for="asset_number" class="form-label">
                                            Número de Bien <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('asset_number') is-invalid @enderror" 
                                               id="asset_number" name="asset_number" value="{{ old('asset_number', $inventory->asset_number) }}" 
                                               placeholder="Ej: LAP-2024-001" required>
                                        @error('asset_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- SKU -->
                                    <div class="mb-4">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                               id="sku" name="sku" value="{{ old('sku', $inventory->sku) }}" 
                                               placeholder="Ej: 348121032">
                                        @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-4">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="Descripción detallada del bien...">{{ old('description', $inventory->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Tipo -->
                                    <div class="mb-4">
                                        <label for="type" class="form-label">Tipo</label>
                                        <input type="text" class="form-control @error('type') is-invalid @enderror" 
                                               id="type" name="type" value="{{ old('type', $inventory->type) }}" 
                                               placeholder="Ej: Equipo de cómputo">
                                        @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- Marca -->
                                    <div class="mb-4">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                               id="brand" name="brand" value="{{ old('brand', $inventory->brand) }}" 
                                               placeholder="Ej: HP">
                                        @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Modelo -->
                                    <div class="mb-4">
                                        <label for="model" class="form-label">Modelo</label>
                                        <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                               id="model" name="model" value="{{ old('model', $inventory->model) }}" 
                                               placeholder="Ej: Pavilion 15">
                                        @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- Número de Serie -->
                                    <div class="mb-4">
                                        <label for="serial_number" class="form-label">Número de Serie</label>
                                        <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                               id="serial_number" name="serial_number" value="{{ old('serial_number', $inventory->serial_number) }}" 
                                               placeholder="Ej: SN123456789">
                                        @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->

                    <!-- Card Imágenes -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-header-title">Imágenes</h4>
                        </div>

                        <div class="card-body">
                            @if(!empty($inventory->images))
                            <div class="row mb-3">
                                @foreach($inventory->images as $index => $image)
                                <div class="col-md-3 mb-3">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image) }}" 
                                             class="img-thumbnail rounded-3" 
                                             style="width: 100%; height: 180px; object-fit: cover;" 
                                             alt="Imagen del bien">
                                        <button type="button"
                                                class="btn btn-danger btn-sm shadow-sm position-absolute top-0 end-0 m-2"
                                                style="border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                                title="Eliminar imagen"
                                                onclick="if(confirm('¿Está seguro de eliminar esta imagen?')) document.getElementById('delete-image-form-{{ $index }}').submit();">
                                            <i class="bi-trash" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-soft-secondary mb-3" role="alert">
                                <i class="bi-image me-1"></i> No hay imágenes disponibles
                            </div>
                            @endif

                            <div class="mb-2">
                                <label class="form-label">Agregar más imágenes</label>
                                <input type="file" class="form-control" name="images[]" 
                                       accept="image/*" multiple>
                                <small class="form-text text-muted">Puede agregar más imágenes (máx. 2MB cada una)</small>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>

                <div class="col-lg-4">
                    <!-- Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-header-title">Estado y Ubicación</h4>
                        </div>

                        <div class="card-body">
                            <!-- Estado -->
                            <div class="mb-4">
                                <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="available" {{ old('status', $inventory->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                                    <option value="assigned" {{ old('status', $inventory->status) == 'assigned' ? 'selected' : '' }}>Asignado</option>
                                    <option value="maintenance" {{ old('status', $inventory->status) == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                                    <option value="retired" {{ old('status', $inventory->status) == 'retired' ? 'selected' : '' }}>Dado de Baja</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Departamento -->
                            <div class="mb-4">
                                <label for="department_id" class="form-label">Departamento</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $inventory->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Condición -->
                            <div class="mb-4">
                                <label for="condition" class="form-label">Condición</label>
                                <input type="text" class="form-control @error('condition') is-invalid @enderror" 
                                       id="condition" name="condition" value="{{ old('condition', $inventory->condition) }}" 
                                       placeholder="Ej: Nuevo, Usado, Reacondicionado">
                                @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4 class="card-header-title">Información de Compra</h4>
                        </div>

                        <div class="card-body">
                            <!-- Precio -->
                            <div class="mb-4">
                                <label for="purchase_price" class="form-label">Precio de Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q.</span>
                                    <input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" 
                                           id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $inventory->purchase_price) }}" 
                                           placeholder="0.00">
                                </div>
                                @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Compra -->
                            <div class="mb-4">
                                <label for="purchase_date" class="form-label">Fecha de Compra</label>
                                <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       id="purchase_date" name="purchase_date" 
                                       value="{{ old('purchase_date', $inventory->purchase_date ? $inventory->purchase_date->format('Y-m-d') : '') }}">
                                @error('purchase_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Proveedor -->
                            <div class="mb-4">
                                <label for="vendor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control @error('vendor') is-invalid @enderror" 
                                       id="vendor" name="vendor" value="{{ old('vendor', $inventory->vendor) }}" 
                                       placeholder="Nombre del proveedor">
                                @error('vendor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->

                    <!-- Card -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-header-title">Cantidad</h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <!-- Cantidad -->
                                    <div class="mb-4">
                                        <label for="quantity" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                               id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" 
                                               min="1" required>
                                        @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <!-- Unidad -->
                                    <div class="mb-4">
                                        <label for="unit" class="form-label">Unidad <span class="text-danger">*</span></label>
                                        <select class="form-select @error('unit') is-invalid @enderror" 
                                                id="unit" name="unit" required>
                                            <option value="pza" {{ old('unit', $inventory->unit) == 'pza' ? 'selected' : '' }}>Pieza</option>
                                            <option value="kg" {{ old('unit', $inventory->unit) == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                                            <option value="lt" {{ old('unit', $inventory->unit) == 'lt' ? 'selected' : '' }}>Litro</option>
                                            <option value="m" {{ old('unit', $inventory->unit) == 'm' ? 'selected' : '' }}>Metro</option>
                                            <option value="caja" {{ old('unit', $inventory->unit) == 'caja' ? 'selected' : '' }}>Caja</option>
                                        </select>
                                        @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Notas -->
                            <div class="mb-0">
                                <label for="notes" class="form-label">Notas Adicionales</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="Notas u observaciones...">{{ old('notes', $inventory->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <a href="{{ route('inventory.show', $inventory) }}" class="btn btn-white">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi-check-lg me-1"></i> Actualizar Bien
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if(!empty($inventory->images))
            @foreach($inventory->images as $index => $image)
                <form id="delete-image-form-{{ $index }}" action="{{ route('inventory.delete-image', [$inventory, $index]) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>
</main>
@endsection
