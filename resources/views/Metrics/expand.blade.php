@extends('layouts.app')

@section('content')

<main id="content" role="main" class="main">
  <!-- Content -->
  <div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="page-header-title">{{ $chartNames[$chartId] ?? 'Gráfica Expandida' }}</h1>
        </div>
        <div class="col-auto">
          <a href="{{ route('metrics.index') . '?' . http_build_query(request()->query()) }}" class="btn btn-primary">
            <i class="bi-arrow-left"></i> Regresar
          </a>
        </div>
      </div>
    </div>
    <!-- End Page Header -->

    <!-- Gráfica Expandida -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header card-header-content-between">
            <h4 class="card-header-title">{{ $chartNames[$chartId] ?? 'Gráfica Expandida' }}</h4>
            <div class="d-flex gap-2 align-items-center">
              <button class="btn btn-white btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapseExpand">
                <i class="bi-filter me-1"></i> Filtros
                @if($filtersCount > 0)
                <span class="badge bg-primary ms-1">{{ $filtersCount }}</span>
                @endif
              </button>
              <div class="dropdown">
                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdownExpand" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi-download me-2"></i> Exportar
                </button>
                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdownExpand">
                  <span class="dropdown-header">Opciones</span>
                  <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="expandedChart">
                    <i class="bi-printer me-2"></i> Imprimir
                  </a>
                  <div class="dropdown-divider"></div>
                  <span class="dropdown-header">Descargar</span>
                  <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="expandedChart">
                    <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF
                  </a>
                  <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="expandedChart">
                    <i class="bi-file-earmark-image me-2 text-success"></i> SVG
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- Filtros Colapsables -->
          <div class="collapse" id="filtersCollapseExpand">
            <div class="card-body border-bottom">
              <form method="GET" action="{{ route('metrics.expand', $chartId) }}" id="filterFormExpand">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <select class="form-select" name="department_id" id="departmentExpand">
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
                    <select class="form-select" name="type" id="typeExpand">
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
                    <select class="form-select" name="brand" id="brandExpand">
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
                      <a href="{{ route('metrics.expand', $chartId) }}" class="btn btn-white">
                        <i class="bi-x-lg me-1"></i> Limpiar
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container-expanded" style="position: relative; width: 100%; height: calc(100vh - 300px); overflow: hidden;">
              <canvas id="expandedChart" style="display: block; cursor: crosshair !important;"></canvas>
              <!-- Botones de Zoom (solo para gráficas de barras) -->
              <div class="chart-zoom-controls" id="zoomControls" style="position: absolute; top: 10px; right: 10px; z-index: 10; display: none; flex-direction: column; gap: 5px;">
                <button type="button" class="btn btn-white btn-sm shadow-sm" id="zoomInBtn" title="Zoom In">
                  <i class="bi-zoom-in"></i>
                </button>
                <button type="button" class="btn btn-white btn-sm shadow-sm" id="zoomOutBtn" title="Zoom Out">
                  <i class="bi-zoom-out"></i>
                </button>
                <button type="button" class="btn btn-white btn-sm shadow-sm" id="resetZoomBtn" title="Restablecer Zoom">
                  <i class="bi-arrow-counterclockwise"></i>
                </button>
              </div>
              <!-- Botones de Navegación (solo cuando hay zoom) -->
              <div class="chart-nav-controls" id="navControls" style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%); z-index: 10; display: none; gap: 5px; flex-direction: row;">
                <button type="button" class="btn btn-white btn-sm shadow-sm" id="navLeftBtn" title="Anterior">
                  <i class="bi-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-white btn-sm shadow-sm" id="navRightBtn" title="Siguiente">
                  <i class="bi-chevron-right"></i>
                </button>
              </div>
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

  // Datos de las gráficas
  const inventoryByDepartment = @json($filteredInventoryByDepartment);
  const inventoryByType = @json($filteredInventoryByType);
  const inventoryByBrand = @json($filteredInventoryByBrand);
  const chartId = @json($chartId);

  // Configuración común para zoom y pan
  const zoomPanConfig = {
    zoom: {
      wheel: {
        enabled: true,
        speed: 0.1,
      },
      pinch: {
        enabled: true
      },
      drag: {
        enabled: false
      },
      mode: 'xy',
    },
    pan: {
      enabled: true,
      mode: 'xy',
      modifierKey: null,
    }
  };

  let expandedChartInstance = null;
  let originalScaleLimits = {};

  // Crear gráfica según el ID
  const expandedCtx = document.getElementById('expandedChart');
  let chartConfig = {};

  if (chartId === 'chart2') {
    // Inventario por Departamento
    chartConfig = {
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
        animation: {
          duration: 150, // Duración de animación más rápida para responsividad
          easing: 'easeOutQuad' // Easing suave
        },
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false
          },
          zoom: {
            wheel: {
              enabled: true,
              speed: 0.1,
            },
            pinch: {
              enabled: true
            },
            drag: {
              enabled: false
            },
            mode: 'xy',
          },
          pan: {
            enabled: true,
            mode: 'xy',
            modifierKey: null,
          }
        },
        interaction: {
          mode: 'nearest',
          intersect: false
        }
      }
    };
  } else if (chartId === 'chart3') {
    // Inventario por Tipo
    chartConfig = {
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
        animation: {
          duration: 150, // Duración de animación más rápida para responsividad
          easing: 'easeOutQuad' // Easing suave
        },
        plugins: {
          legend: {
            display: true,
            position: 'right'
          },
          zoom: {
            wheel: {
              enabled: true,
              speed: 0.1,
            },
            pinch: {
              enabled: true
            },
            drag: {
              enabled: false
            },
            mode: 'xy',
          },
          pan: {
            enabled: true,
            mode: 'xy',
            modifierKey: null,
          }
        },
        interaction: {
          mode: 'nearest',
          intersect: false
        }
      }
    };
  } else if (chartId === 'chart4') {
    // Inventario por Marca
    chartConfig = {
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
        animation: {
          duration: 150, // Duración de animación más rápida para responsividad
          easing: 'easeOutQuad' // Easing suave
        },
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
          },
          zoom: {
            zoom: {
              wheel: {
                enabled: true,
                speed: 0.1,
              },
              pinch: {
                enabled: true
              },
              drag: {
                enabled: false
              },
              mode: 'xy',
            },
            pan: {
              enabled: true,
              mode: 'xy',
              modifierKey: null,
            }
          }
        },
        interaction: {
          mode: 'nearest',
          intersect: false
        }
      }
    };
  }

  if (expandedCtx && chartConfig.type) {
    expandedChartInstance = new Chart(expandedCtx, chartConfig);
    
    // Guardar valores originales de las escalas para resetear
    if (expandedChartInstance && expandedChartInstance.scales) {
      Object.keys(expandedChartInstance.scales).forEach(scaleId => {
        const scale = expandedChartInstance.scales[scaleId];
        if (scale) {
          originalScaleLimits[scaleId] = {
            min: scale.min !== undefined ? scale.min : null,
            max: scale.max !== undefined ? scale.max : null
          };
        }
      });
    }
    
    // Ajustar tamaño cuando se carga
    setTimeout(() => {
      const container = document.querySelector('.chart-container-expanded');
      if (container && expandedChartInstance.canvas) {
        const containerRect = container.getBoundingClientRect();
        expandedChartInstance.canvas.style.width = containerRect.width + 'px';
        expandedChartInstance.canvas.style.height = containerRect.height + 'px';
        expandedChartInstance.resize();
        
        const canvas = expandedChartInstance.canvas;
        let isMouseInside = false;
        let isDragging = false;
        let panStart = null;
        
        // Detectar cuando el mouse entra/sale del canvas
        canvas.addEventListener('mouseenter', function() {
          isMouseInside = true;
          canvas.style.cursor = 'crosshair';
          canvas.style.setProperty('cursor', 'crosshair', 'important');
        });
        
        canvas.addEventListener('mouseleave', function() {
          isMouseInside = false;
          canvas.style.cursor = 'default';
          canvas.style.removeProperty('cursor');
          isDragging = false;
          panStart = null;
        });
        
        // Asegurar que el cursor crosshair se mantenga al mover el mouse sobre el canvas
        canvas.addEventListener('mousemove', function() {
          if (isMouseInside && !isDragging) {
            canvas.style.cursor = 'crosshair';
            canvas.style.setProperty('cursor', 'crosshair', 'important');
          }
        });
        
        // Zoom con scroll solo cuando el mouse está dentro del canvas
        canvas.addEventListener('wheel', function(e) {
          if (!isMouseInside) return;
          
          // Prevenir scroll del contenedor pero permitir que el plugin maneje el zoom
          e.preventDefault();
          e.stopPropagation();
          
          // Disparar el zoom manualmente si el plugin no lo captura
          const rect = canvas.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          
          const deltaY = e.deltaY;
          const zoomFactor = deltaY > 0 ? 0.9 : 1.1;
          
          if (expandedChartInstance && expandedChartInstance.scales) {
            const scales = expandedChartInstance.scales;
            
            if (scales.x && scales.y) {
              const xValue = scales.x.getValueForPixel(x);
              const yValue = scales.y.getValueForPixel(y);
              
              if (xValue !== null && yValue !== null) {
                const xRange = scales.x.max - scales.x.min;
                const yRange = scales.y.max - scales.y.min;
                
                const newXRange = xRange * zoomFactor;
                const newYRange = yRange * zoomFactor;
                
                const xCenter = (scales.x.min + scales.x.max) / 2;
                const yCenter = (scales.y.min + scales.y.max) / 2;
                
                scales.x.min = xValue - (xValue - scales.x.min) * zoomFactor;
                scales.x.max = xValue + (scales.x.max - xValue) * zoomFactor;
                scales.y.min = yValue - (yValue - scales.y.min) * zoomFactor;
                scales.y.max = yValue + (scales.y.max - yValue) * zoomFactor;
                
                expandedChartInstance.update('none');
              }
            }
          }
        }, { passive: false });
        
        // Pan con click izquierdo y arrastrar (con transiciones suaves)
        let panAnimationFrame = null;
        let lastUpdateTime = 0;
        const UPDATE_THROTTLE = 16; // ~60fps
        
        canvas.addEventListener('mousedown', function(e) {
          if (!isMouseInside || e.button !== 0) return;
          
          isDragging = true;
          panStart = { x: e.clientX, y: e.clientY };
          canvas.style.cursor = 'grabbing';
          lastUpdateTime = performance.now();
          e.preventDefault();
        });
        
        document.addEventListener('mousemove', function(e) {
          if (!isDragging || !isMouseInside || !panStart) return;
          
          const currentTime = performance.now();
          if (currentTime - lastUpdateTime < UPDATE_THROTTLE) {
            // Throttle: acumular cambios pero actualizar solo cada 16ms
            return;
          }
          
          const deltaX = e.clientX - panStart.x;
          const deltaY = e.clientY - panStart.y;
          
          // Cancelar animación anterior si existe
          if (panAnimationFrame) {
            cancelAnimationFrame(panAnimationFrame);
          }
          
          // Usar requestAnimationFrame para transición suave
          panAnimationFrame = requestAnimationFrame(() => {
            if (expandedChartInstance && expandedChartInstance.scales) {
              const scales = expandedChartInstance.scales;
              
              if (scales.x) {
                const xRange = scales.x.max - scales.x.min;
                const xStep = (deltaX / canvas.width) * xRange;
                if (scales.x.min !== undefined && scales.x.max !== undefined) {
                  scales.x.min -= xStep;
                  scales.x.max -= xStep;
                }
              }
              
              if (scales.y) {
                const yRange = scales.y.max - scales.y.min;
                const yStep = (deltaY / canvas.height) * yRange;
                if (scales.y.min !== undefined && scales.y.max !== undefined) {
                  scales.y.min += yStep;
                  scales.y.max += yStep;
                }
              }
              
              // Usar 'active' en lugar de 'none' para animación suave
              expandedChartInstance.update('active');
              lastUpdateTime = currentTime;
            }
          });
          
          panStart = { x: e.clientX, y: e.clientY };
        });
        
        document.addEventListener('mouseup', function() {
          if (isDragging) {
            if (panAnimationFrame) {
              cancelAnimationFrame(panAnimationFrame);
              panAnimationFrame = null;
            }
            isDragging = false;
            panStart = null;
            if (isMouseInside) {
              canvas.style.cursor = 'crosshair';
              canvas.style.setProperty('cursor', 'crosshair', 'important');
            } else {
              canvas.style.cursor = 'default';
              canvas.style.removeProperty('cursor');
            }
          }
        });
        
        // Solo para gráficas de barras
        if (expandedChartInstance.config.type === 'bar') {
          // Mostrar controles de zoom
          document.getElementById('zoomControls').style.display = 'flex';
          
          // Variables para controlar el zoom y pan
          let visibleMin = 0;
          let visibleMax = expandedChartInstance.data.labels.length;
          const totalLabels = expandedChartInstance.data.labels.length;
          let mousePosition = 0.5; // Posición del mouse (0 a 1)
          
          // Función para aplicar zoom con animación suave
          function applyZoom(animate = true) {
            expandedChartInstance.options.scales.x.min = visibleMin;
            expandedChartInstance.options.scales.x.max = visibleMax;
            // Usar 'active' para animación suave en lugar de 'none'
            expandedChartInstance.update(animate ? 'active' : 'none');
            
            // Mostrar/ocultar botones de navegación
            const navControls = document.getElementById('navControls');
            const isZoomed = (visibleMax - visibleMin) < totalLabels;
            if (navControls) {
              navControls.style.display = isZoomed ? 'flex' : 'none';
            }
          }
          
          // Rastrear posición del mouse sobre el canvas
          canvas.addEventListener('mousemove', function(e) {
            const rect = canvas.getBoundingClientRect();
            mousePosition = (e.clientX - rect.left) / rect.width;
          });
          
          // Configurar botones de zoom
          const zoomInBtn = document.getElementById('zoomInBtn');
          const zoomOutBtn = document.getElementById('zoomOutBtn');
          const resetZoomBtn = document.getElementById('resetZoomBtn');
          const navLeftBtn = document.getElementById('navLeftBtn');
          const navRightBtn = document.getElementById('navRightBtn');
          
          if (zoomInBtn) {
            zoomInBtn.onclick = function() {
              try {
                const currentRange = visibleMax - visibleMin;
                let newRange = Math.max(Math.floor(currentRange * 0.5), 1);
                
                const focusPoint = visibleMin + (currentRange * mousePosition);
                
                if (newRange === 1) {
                  visibleMin = Math.max(0, Math.min(Math.floor(focusPoint), totalLabels - 1));
                  visibleMax = visibleMin + 1;
                } else {
                  visibleMin = Math.max(0, Math.floor(focusPoint - newRange * mousePosition));
                  visibleMax = Math.min(totalLabels, Math.ceil(visibleMin + newRange));
                  
                  if (visibleMax > totalLabels) {
                    visibleMax = totalLabels;
                    visibleMin = totalLabels - newRange;
                  }
                }
                
                applyZoom();
              } catch(e) {}
            };
          }
          
          if (zoomOutBtn) {
            zoomOutBtn.onclick = function() {
              try {
                const currentRange = visibleMax - visibleMin;
                const newRange = Math.min(Math.ceil(currentRange * 2), totalLabels);
                const focusPoint = visibleMin + (currentRange * mousePosition);
                
                visibleMin = Math.max(0, Math.floor(focusPoint - newRange * mousePosition));
                visibleMax = Math.min(totalLabels, Math.ceil(visibleMin + newRange));
                
                if (visibleMin <= 0 && visibleMax >= totalLabels) {
                  visibleMin = 0;
                  visibleMax = totalLabels;
                  delete expandedChartInstance.options.scales.x.min;
                  delete expandedChartInstance.options.scales.x.max;
                  // Usar 'active' para animación suave al hacer zoom out completo
                  expandedChartInstance.update('active');
                  document.getElementById('navControls').style.display = 'none';
                } else {
                  applyZoom();
                }
              } catch(e) {}
            };
          }
          
          if (resetZoomBtn) {
            resetZoomBtn.onclick = function() {
              try {
                visibleMin = 0;
                visibleMax = totalLabels;
                delete expandedChartInstance.options.scales.x.min;
                delete expandedChartInstance.options.scales.x.max;
                delete expandedChartInstance.options.scales.y.min;
                delete expandedChartInstance.options.scales.y.max;
                // Usar 'active' para animación suave al resetear
                expandedChartInstance.update('active');
                document.getElementById('navControls').style.display = 'none';
              } catch(e) {}
            };
          }
          
          // Botones de navegación
          if (navLeftBtn) {
            navLeftBtn.onclick = function() {
              try {
                const currentRange = visibleMax - visibleMin;
                const step = Math.max(1, Math.floor(currentRange * 0.3));
                
                if (visibleMin > 0) {
                  visibleMin = Math.max(0, visibleMin - step);
                  visibleMax = visibleMin + currentRange;
                  applyZoom();
                }
              } catch(e) {}
            };
          }
          
          if (navRightBtn) {
            navRightBtn.onclick = function() {
              try {
                const currentRange = visibleMax - visibleMin;
                const step = Math.max(1, Math.floor(currentRange * 0.3));
                
                if (visibleMax < totalLabels) {
                  visibleMax = Math.min(totalLabels, visibleMax + step);
                  visibleMin = visibleMax - currentRange;
                  applyZoom();
                }
              } catch(e) {}
            };
          }
          
          // Zoom con scroll del mouse
          canvas.addEventListener('wheel', function(e) {
            e.preventDefault();
            
            const rect = canvas.getBoundingClientRect();
            mousePosition = (e.clientX - rect.left) / rect.width;
            
            const currentRange = visibleMax - visibleMin;
            let newRange;
            
            if (e.deltaY < 0) {
              newRange = Math.max(Math.floor(currentRange * 0.7), 1);
            } else {
              newRange = Math.min(Math.ceil(currentRange * 1.5), totalLabels);
            }
            
            const focusPoint = visibleMin + (currentRange * mousePosition);
            
            if (newRange === 1) {
              visibleMin = Math.max(0, Math.min(Math.floor(focusPoint), totalLabels - 1));
              visibleMax = visibleMin + 1;
            } else {
              visibleMin = Math.max(0, Math.floor(focusPoint - newRange * mousePosition));
              visibleMax = Math.min(totalLabels, Math.ceil(visibleMin + newRange));
              
              if (visibleMax > totalLabels) {
                visibleMax = totalLabels;
                visibleMin = Math.max(0, totalLabels - newRange);
              }
            }
            
            if (visibleMin <= 0 && visibleMax >= totalLabels) {
              delete expandedChartInstance.options.scales.x.min;
              delete expandedChartInstance.options.scales.x.max;
              document.getElementById('navControls').style.display = 'none';
            } else {
              expandedChartInstance.options.scales.x.min = visibleMin;
              expandedChartInstance.options.scales.x.max = visibleMax;
              document.getElementById('navControls').style.display = 'flex';
            }
            
            // Usar 'active' para animación suave en el zoom con scroll
            expandedChartInstance.update('active');
          }, { passive: false });
          
          // Pan (arrastrar) con click izquierdo (con transiciones suaves)
          let isPanning = false;
          let panStartX = 0;
          let accumulatedDelta = 0;
          let barPanAnimationFrame = null;
          let barLastUpdateTime = 0;
          const BAR_UPDATE_THROTTLE = 16; // ~60fps para suavidad
          
          canvas.addEventListener('mousedown', function(e) {
            if (e.button === 0) {
              const isZoomed = (visibleMax - visibleMin) < totalLabels;
              if (isZoomed) {
                // Cancelar cualquier animación pendiente
                if (barPanAnimationFrame) {
                  cancelAnimationFrame(barPanAnimationFrame);
                }
                isPanning = true;
                panStartX = e.clientX;
                accumulatedDelta = 0;
                barLastUpdateTime = performance.now();
                canvas.style.cursor = 'grabbing';
              } else {
                // Si no hay zoom, mantener crosshair
                canvas.style.cursor = 'crosshair';
                canvas.style.setProperty('cursor', 'crosshair', 'important');
              }
            }
          });
          
          canvas.addEventListener('mousemove', function(e) {
            if (!isPanning) {
              // Mostrar crosshair cuando el mouse está sobre la gráfica
              canvas.style.cursor = 'crosshair';
              canvas.style.setProperty('cursor', 'crosshair', 'important');
              
              const rect = canvas.getBoundingClientRect();
              mousePosition = (e.clientX - rect.left) / rect.width;
              return;
            }
            
            // Cuando está haciendo pan, mantener grabbing
            canvas.style.cursor = 'grabbing';
            
            const currentTime = performance.now();
            const deltaX = e.clientX - panStartX;
            const currentRange = visibleMax - visibleMin;
            
            // Acumular cambios continuamente
            const sensitivity = currentRange <= 5 ? 0.3 : 0.5;
            accumulatedDelta += (deltaX / canvas.width) * currentRange * sensitivity * -1;
            
            // Actualizar solo cada 16ms (60fps) para suavidad
            if (currentTime - barLastUpdateTime >= BAR_UPDATE_THROTTLE) {
              // Cancelar animación anterior si existe
              if (barPanAnimationFrame) {
                cancelAnimationFrame(barPanAnimationFrame);
              }
              
              // Usar requestAnimationFrame para actualización suave
              barPanAnimationFrame = requestAnimationFrame(() => {
                if (Math.abs(accumulatedDelta) >= 0.1) {
                  const step = accumulatedDelta;
                  accumulatedDelta = 0;
                  
                  let newMin = visibleMin + step;
                  let newMax = visibleMax + step;
                  
                  if (newMin < 0) {
                    newMin = 0;
                    newMax = Math.min(currentRange, totalLabels);
                  }
                  if (newMax > totalLabels) {
                    newMax = totalLabels;
                    newMin = Math.max(0, totalLabels - currentRange);
                  }
                  
                  visibleMin = newMin;
                  visibleMax = newMax;
                  
                  expandedChartInstance.options.scales.x.min = visibleMin;
                  expandedChartInstance.options.scales.x.max = visibleMax;
                  // Usar 'active' para animación suave entre frames
                  expandedChartInstance.update('active');
                  
                  barLastUpdateTime = currentTime;
                }
              });
            }
            
            panStartX = e.clientX;
          });
        
          canvas.addEventListener('mouseup', function() {
            if (isPanning) {
              isPanning = false;
              canvas.style.cursor = 'crosshair';
              canvas.style.setProperty('cursor', 'crosshair', 'important');
            }
          });
          
          canvas.addEventListener('mouseleave', function() {
            if (isPanning) {
              isPanning = false;
              if (barPanAnimationFrame) {
                cancelAnimationFrame(barPanAnimationFrame);
              }
              canvas.style.cursor = 'default';
            }
          });
        }
      }
    }, 200);
  }

  // Almacenar referencia
  const charts = {
    expandedChart: expandedChartInstance
  };

  // Manejar impresión
  document.querySelectorAll('.print-chart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const chartId = this.getAttribute('data-chart-id');
      const chart = charts[chartId];
      
      if (!chart) return;

      const canvas = chart.canvas;
      const url = canvas.toDataURL('image/png', 1.0);
      const printWindow = window.open('', '_blank');
      const chartTitle = '{{ $chartNames[$chartId] ?? "Gráfica" }}';
      
      printWindow.document.write(`
        <html>
          <head>
            <title>Imprimir Gráfica - ${chartTitle}</title>
            <style>
              @page {
                size: landscape;
                margin: 1cm;
              }
              @media print {
                body {
                  -webkit-print-color-adjust: exact;
                  print-color-adjust: exact;
                }
              }
              body {
                margin: 0;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-family: Arial, sans-serif;
              }
              h1 {
                margin-bottom: 20px;
                color: #333;
              }
              img {
                max-width: 100%;
                width: 1200px;
                height: auto;
                page-break-inside: avoid;
              }
            </style>
          </head>
          <body>
            <h1>${chartTitle}</h1>
            <img src="${url}" onload="window.print();" />
          </body>
        </html>
      `);
      printWindow.document.close();
    });
  });

  // Manejar exportación PDF
  document.querySelectorAll('.export-pdf-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const chartId = this.getAttribute('data-chart-id');
      const chart = charts[chartId];
      
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
      const chart = charts[chartId];
      
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

