@extends('layouts.app')

@section('content')

<main id="content" role="main" class="main">
  <!-- Content -->
  <div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="page-header-title">Métricas</h1>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Stats Cards -->
    <div class="row">
      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <div class="card card-hover-shadow h-100">
          <div class="card-body">
            <h6 class="card-subtitle">Total Inventario</h6>
            <h2 class="card-title text-inherit">{{ number_format($totalInventory) }}</h2>
            <span class="text-body fs-6">Bienes registrados</span>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <div class="card card-hover-shadow h-100">
          <div class="card-body">
            <h6 class="card-subtitle">Valor Total</h6>
            <h2 class="card-title text-inherit">Q.{{ number_format($totalValue, 2) }}</h2>
            <span class="text-body fs-6">Valor de compra</span>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <div class="card card-hover-shadow h-100">
          <div class="card-body">
            <h6 class="card-subtitle">Departamentos</h6>
            <h2 class="card-title text-inherit">{{ $totalDepartments }}</h2>
            <span class="text-body fs-6">Departamentos activos</span>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <div class="card card-hover-shadow h-100">
          <div class="card-body">
            <h6 class="card-subtitle">Usuarios</h6>
            <h2 class="card-title text-inherit">{{ $totalUsers }}</h2>
            <span class="text-body fs-6">Usuarios registrados</span>
          </div>
        </div>
      </div>
    </div>
    <!-- End Stats Cards -->

    <!-- Gráfica 1: Inventario por Departamento -->
    <div class="row mb-3 mb-lg-5">
      <div class="col-12">
        <div class="card">
          <div class="card-header card-header-content-between">
            <h4 class="card-header-title">Inventario por Departamento</h4>
            <div class="d-flex gap-2 align-items-center">
              <button class="btn btn-white btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse1">
                <i class="bi-filter me-1"></i> Filtros
                @if($filtersCount > 0)
                <span class="badge bg-soft-primary text-primary ms-1">{{ $filtersCount }}</span>
                @endif
              </button>
              <div class="dropdown">
                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi-download me-2"></i> Exportar
                </button>
                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown1">
                  <span class="dropdown-header">Opciones</span>
                  <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="chart1">
                    <i class="bi-printer me-2"></i> Imprimir
                  </a>
                  <div class="dropdown-divider"></div>
                  <span class="dropdown-header">Descargar</span>
                  <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="chart1">
                    <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF
                  </a>
                  <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="chart1">
                    <i class="bi-file-earmark-image me-2 text-success"></i> SVG
                  </a>
                </div>
              </div>
              <a href="{{ route('metrics.expand', 'chart2') . '?' . http_build_query(request()->query()) }}" class="btn btn-white btn-sm">
                <i class="bi-arrows-fullscreen me-1"></i> Expandir
              </a>
            </div>
          </div>
          <!-- Filtros Colapsables -->
          <div class="collapse" id="filtersCollapse1">
            <div class="card-body border-bottom">
              <form method="GET" action="{{ route('metrics.index') }}" id="filterForm1">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <select class="form-select" name="department_id" id="department1">
                      <option value="">Todos</option>
                      @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                          {{ $dept->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="type" id="type1">
                      <option value="">Todos</option>
                      @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                          {{ $type }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Marca</label>
                    <select class="form-select" name="brand" id="brand1">
                      <option value="">Todas</option>
                      @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                          {{ $brand }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-12">
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi-search me-1"></i> Aplicar Filtros
                      </button>
                      <a href="{{ route('metrics.index') }}" class="btn btn-white">
                        <i class="bi-x-lg me-1"></i> Limpiar
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="position: relative; height: 400px;">
              <canvas id="chart1"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfica 2: Inventario por Tipo -->
    <div class="row mb-3 mb-lg-5">
      <div class="col-12">
        <div class="card">
          <div class="card-header card-header-content-between">
            <h4 class="card-header-title">Inventario por Tipo</h4>
            <div class="d-flex gap-2 align-items-center">
              <button class="btn btn-white btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse2">
                <i class="bi-filter me-1"></i> Filtros
                @if($filtersCount > 0)
                <span class="badge bg-soft-primary text-primary ms-1">{{ $filtersCount }}</span>
                @endif
              </button>
              <div class="dropdown">
                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi-download me-2"></i> Exportar
                </button>
                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown2">
                  <span class="dropdown-header">Opciones</span>
                  <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="chart2">
                    <i class="bi-printer me-2"></i> Imprimir
                  </a>
                  <div class="dropdown-divider"></div>
                  <span class="dropdown-header">Descargar</span>
                  <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="chart2">
                    <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF
                  </a>
                  <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="chart2">
                    <i class="bi-file-earmark-image me-2 text-success"></i> SVG
                  </a>
                </div>
              </div>
              <a href="{{ route('metrics.expand', 'chart3') . '?' . http_build_query(request()->query()) }}" class="btn btn-white btn-sm">
                <i class="bi-arrows-fullscreen me-1"></i> Expandir
              </a>
            </div>
          </div>
          <!-- Filtros Colapsables -->
          <div class="collapse" id="filtersCollapse2">
            <div class="card-body border-bottom">
              <form method="GET" action="{{ route('metrics.index') }}" id="filterForm2">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <select class="form-select" name="department_id" id="department2">
                      <option value="">Todos</option>
                      @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                          {{ $dept->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="type" id="type2">
                      <option value="">Todos</option>
                      @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                          {{ $type }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Marca</label>
                    <select class="form-select" name="brand" id="brand2">
                      <option value="">Todas</option>
                      @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                          {{ $brand }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-12">
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi-search me-1"></i> Aplicar Filtros
                      </button>
                      <a href="{{ route('metrics.index') }}" class="btn btn-white">
                        <i class="bi-x-lg me-1"></i> Limpiar
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="position: relative; height: 400px;">
              <canvas id="chart2"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfica 3: Inventario por Marca -->
    <div class="row mb-3 mb-lg-5">
      <div class="col-12">
        <div class="card">
          <div class="card-header card-header-content-between">
            <h4 class="card-header-title">Inventario por Marca</h4>
            <div class="d-flex gap-2 align-items-center">
              <button class="btn btn-white btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse3">
                <i class="bi-filter me-1"></i> Filtros
                @if($filtersCount > 0)
                <span class="badge bg-soft-primary text-primary ms-1">{{ $filtersCount }}</span>
                @endif
              </button>
              <div class="dropdown">
                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown3" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi-download me-2"></i> Exportar
                </button>
                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown3">
                  <span class="dropdown-header">Opciones</span>
                  <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="chart3">
                    <i class="bi-printer me-2"></i> Imprimir
                  </a>
                  <div class="dropdown-divider"></div>
                  <span class="dropdown-header">Descargar</span>
                  <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="chart3">
                    <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF
                  </a>
                  <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="chart3">
                    <i class="bi-file-earmark-image me-2 text-success"></i> SVG
                  </a>
                </div>
              </div>
              <a href="{{ route('metrics.expand', 'chart4') . '?' . http_build_query(request()->query()) }}" class="btn btn-white btn-sm">
                <i class="bi-arrows-fullscreen me-1"></i> Expandir
              </a>
            </div>
          </div>
          <!-- Filtros Colapsables -->
          <div class="collapse" id="filtersCollapse3">
            <div class="card-body border-bottom">
              <form method="GET" action="{{ route('metrics.index') }}" id="filterForm3">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <select class="form-select" name="department_id" id="department3">
                      <option value="">Todos</option>
                      @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                          {{ $dept->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="type" id="type3">
                      <option value="">Todos</option>
                      @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                          {{ $type }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Marca</label>
                    <select class="form-select" name="brand" id="brand3">
                      <option value="">Todas</option>
                      @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                          {{ $brand }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-12">
                    <div class="d-flex gap-2">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi-search me-1"></i> Aplicar Filtros
                      </button>
                      <a href="{{ route('metrics.index') }}" class="btn btn-white">
                        <i class="bi-x-lg me-1"></i> Limpiar
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="position: relative; height: 400px;">
              <canvas id="chart3"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Content -->
</main>

<!-- Scripts adicionales -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof Chart === 'undefined') {
    return;
  }
  
  try {
    if (typeof zoomPlugin !== 'undefined') {
      Chart.register(zoomPlugin);
    } else if (window.zoomPlugin) {
      Chart.register(window.zoomPlugin);
    }
  } catch(e) {}


  const inventoryByDepartment = @json($filteredInventoryByDepartment);
  const inventoryByType = @json($filteredInventoryByType);
  const inventoryByBrand = @json($filteredInventoryByBrand);

  // Gráfica 1: Inventario por Departamento
  const ctx1 = document.getElementById('chart1');
  if (ctx1) {
    const chart1 = new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: inventoryByDepartment.map(item => item.name || 'Sin Departamento'),
        datasets: [{
          label: 'Cantidad',
          data: inventoryByDepartment.map(item => item.total),
          backgroundColor: 'rgba(55, 125, 255, 0.8)',
          borderColor: 'rgba(55, 125, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    // Guardar referencia
    window.charts = window.charts || {};
    window.charts.chart1 = chart1;
  }

  // Gráfica 2: Inventario por Tipo
  const ctx2 = document.getElementById('chart2');
  if (ctx2) {
    const chart2 = new Chart(ctx2, {
      type: 'pie',
      data: {
        labels: inventoryByType.map(item => item.type),
        datasets: [{
          data: inventoryByType.map(item => item.total),
          backgroundColor: [
            'rgba(55, 125, 255, 0.8)',
            'rgba(255, 193, 7, 0.8)',
            'rgba(40, 167, 69, 0.8)',
            'rgba(220, 53, 69, 0.8)',
            'rgba(108, 117, 125, 0.8)',
            'rgba(23, 162, 184, 0.8)',
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'right'
          }
        }
      }
    });

    window.charts = window.charts || {};
    window.charts.chart2 = chart2;
  }

  // Gráfica 3: Inventario por Marca
  const ctx3 = document.getElementById('chart3');
  if (ctx3) {
    const chart3 = new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: inventoryByBrand.map(item => item.brand),
        datasets: [{
          label: 'Cantidad',
          data: inventoryByBrand.map(item => item.total),
          backgroundColor: 'rgba(55, 125, 255, 0.8)',
          borderColor: 'rgba(55, 125, 255, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          },
          x: {
            ticks: {
              maxRotation: 45,
              minRotation: 45
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    window.charts = window.charts || {};
    window.charts.chart3 = chart3;
  }

  // Manejar impresión
  document.querySelectorAll('.print-chart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const chartId = this.getAttribute('data-chart-id');
      const chart = window.charts[chartId];
      
      if (!chart) return;

      const canvas = chart.canvas;
      const url = canvas.toDataURL('image/png');
      const printWindow = window.open('', '_blank');
      printWindow.document.write(`
        <html>
          <head><title>Imprimir Gráfica</title></head>
          <body style="margin:0;padding:20px;text-align:center;">
            <img src="${url}" style="max-width:100%;height:auto;" />
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.print();
    });
  });

  // Manejar exportación PDF
  document.querySelectorAll('.export-pdf-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const chartId = this.getAttribute('data-chart-id');
      const chart = window.charts[chartId];
      
      if (!chart) return;

      const canvas = chart.canvas;
      const imgData = canvas.toDataURL('image/png');
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF('landscape', 'mm', 'a4');
      const imgWidth = 297;
      const imgHeight = (canvas.height * imgWidth) / canvas.width;
      pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
      pdf.save('grafica-' + chartId + '.pdf');
    });
  });

  // Manejar exportación SVG
  document.querySelectorAll('.export-svg-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const chartId = this.getAttribute('data-chart-id');
      const chart = window.charts[chartId];
      
      if (!chart) return;

      const canvas = chart.canvas;
      const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
      svg.setAttribute('width', canvas.width);
      svg.setAttribute('height', canvas.height);
      
      const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
      image.setAttribute('href', canvas.toDataURL('image/png'));
      image.setAttribute('width', canvas.width);
      image.setAttribute('height', canvas.height);
      svg.appendChild(image);
      
      const svgBlob = new Blob([new XMLSerializer().serializeToString(svg)], {type: 'image/svg+xml;charset=utf-8'});
      const svgUrl = URL.createObjectURL(svgBlob);
      const link = document.createElement('a');
      link.download = 'grafica-' + chartId + '.svg';
      link.href = svgUrl;
      link.click();
      URL.revokeObjectURL(svgUrl);
    });
  });

});
</script>

@endsection

