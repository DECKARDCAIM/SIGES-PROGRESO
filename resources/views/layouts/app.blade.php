<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema de Gestión de Inventarios y Solicitudes de Mantenimiento') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">
    <link rel="preload" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl   footer-offset">
    <script src="{{ asset('js/hs.theme-appearance.js') }}"></script>
    <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

@include('includes.app.userOptions')

@include('includes.app.menu')
 
@yield('content')

@include('includes.app.activity')

  <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
  <script src="{{ asset('vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js') }}"></script>
  <script src="{{ asset('vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js') }}"></script>
  <script src="{{ asset('vendor/daterangepicker/moment.min.js') }}"></script>
  <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
  <script src="{{ asset('vendor/clipboard/dist/clipboard.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables.net.extensions/select/select.min.js') }}"></script>
  <script src="{{ asset('js/theme.min.js') }}"></script>
  <script src="{{ asset('js/hs.theme-appearance-charts.js') }}"></script>

  <script>
    $(document).on('ready', function () {
      $('.js-daterangepicker').daterangepicker();

      $('.js-daterangepicker-times').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'M/DD hh:mm A'
        }
      });

      var start = moment();
      var end = moment();

      function cb(start, end) {
        $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
      }

      $('#js-daterangepicker-predefined').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);

      cb(start, end);
    });


    // INITIALIZATION OF DATATABLES
    // =======================================================
    HSCore.components.HSDatatables.init($('#datatable'), {
      select: {
        style: 'multi',
        selector: 'td:first-child input[type="checkbox"]',
        classMap: {
          checkAll: '#datatableCheckAll',
          counter: '#datatableCounter',
          counterInfo: '#datatableCounterInfo'
        }
      },
      language: {
        zeroRecords: `<div class="text-center p-4">
              <img class="mb-3" src="{{ asset('svg/illustrations/oc-error.svg') }}" alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="default">
              <img class="mb-3" src="{{ asset('svg/illustrations-light/oc-error.svg') }}" alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="dark">
            <p class="mb-0">No data to show</p>
            </div>`
      }
    });

    const datatable = HSCore.components.HSDatatables.getItem(0)

    document.querySelectorAll('.js-datatable-filter').forEach(function (item) {
      item.addEventListener('change',function(e) {
        const elVal = e.target.value,
    targetColumnIndex = e.target.getAttribute('data-target-column-index'),
    targetTable = e.target.getAttribute('data-target-table');

    HSCore.components.HSDatatables.getItem(targetTable).column(targetColumnIndex).search(elVal !== 'null' ? elVal : '').draw()
      })
    })
  </script>

  <!-- JS Plugins Init. -->
  <script>
    (function() {
      // No remover el tema del localStorage si el usuario tiene una preferencia guardada
      @if(!Auth::check() || !Auth::user()->theme_preference)
        localStorage.removeItem('hs_theme')
      @endif

      window.onload = function () {
        

        // INITIALIZATION OF NAVBAR VERTICAL ASIDE
        // =======================================================
        new HSSideNav('.js-navbar-vertical-aside').init()


        // INITIALIZATION OF FORM SEARCH
        // =======================================================
        const HSFormSearchInstance = new HSFormSearch('.js-form-search')

        if (HSFormSearchInstance.collection.length) {
          HSFormSearchInstance.getItem(1).on('close', function (el) {
            el.classList.remove('top-0')
          })

          document.querySelector('.js-form-search-mobile-toggle').addEventListener('click', e => {
            let dataOptions = JSON.parse(e.currentTarget.getAttribute('data-hs-form-search-options')),
              $menu = document.querySelector(dataOptions.dropMenuElement)

            $menu.classList.add('top-0')
            $menu.style.left = 0
          })
        }


        // INITIALIZATION OF BOOTSTRAP DROPDOWN
        // =======================================================
        HSBsDropdown.init()


        // INITIALIZATION OF CHARTJS
        // =======================================================
        HSCore.components.HSChartJS.init('.js-chart')


        // INITIALIZATION OF CHARTJS
        // =======================================================
        HSCore.components.HSChartJS.init('#updatingBarChart')
        const updatingBarChart = HSCore.components.HSChartJS.getItem('updatingBarChart')

        // Call when tab is clicked
        document.querySelectorAll('[data-bs-toggle="chart-bar"]').forEach(item => {
          item.addEventListener('click', e => {
            let keyDataset = e.currentTarget.getAttribute('data-datasets')

            const styles = HSCore.components.HSChartJS.getTheme('updatingBarChart', HSThemeAppearance.getAppearance())

            if (keyDataset === 'lastWeek') {
              updatingBarChart.data.labels = ["Apr 22", "Apr 23", "Apr 24", "Apr 25", "Apr 26", "Apr 27", "Apr 28", "Apr 29", "Apr 30", "Apr 31"];
              updatingBarChart.data.datasets = [
                {
                  "data": [120, 250, 300, 200, 300, 290, 350, 100, 125, 320],
                  "backgroundColor": styles.data.datasets[0].backgroundColor,
                  "hoverBackgroundColor": styles.data.datasets[0].hoverBackgroundColor,
                  "borderColor": styles.data.datasets[0].borderColor,
                  "maxBarThickness": 10
                },
                {
                  "data": [250, 130, 322, 144, 129, 300, 260, 120, 260, 245, 110],
                  "backgroundColor": styles.data.datasets[1].backgroundColor,
                  "borderColor": styles.data.datasets[1].borderColor,
                  "maxBarThickness": 10
                }
              ];
              updatingBarChart.update();
            } else {
              updatingBarChart.data.labels = ["May 1", "May 2", "May 3", "May 4", "May 5", "May 6", "May 7", "May 8", "May 9", "May 10"];
              updatingBarChart.data.datasets = [
                {
                  "data": [200, 300, 290, 350, 150, 350, 300, 100, 125, 220],
                  "backgroundColor": styles.data.datasets[0].backgroundColor,
                  "hoverBackgroundColor": styles.data.datasets[0].hoverBackgroundColor,
                  "borderColor": styles.data.datasets[0].borderColor,
                  "maxBarThickness": 10
                },
                {
                  "data": [150, 230, 382, 204, 169, 290, 300, 100, 300, 225, 120],
                  "backgroundColor": styles.data.datasets[1].backgroundColor,
                  "borderColor": styles.data.datasets[1].borderColor,
                  "maxBarThickness": 10
                }
              ]
              updatingBarChart.update();
            }
          })
        })


        // INITIALIZATION OF CHARTJS
        // =======================================================
        HSCore.components.HSChartJS.init('.js-chart-datalabels', {
          plugins: [ChartDataLabels],
          options: {
            plugins: {
              datalabels: {
                anchor: function (context) {
                  var value = context.dataset.data[context.dataIndex];
                  return value.r < 20 ? 'end' : 'center';
                },
                align: function (context) {
                  var value = context.dataset.data[context.dataIndex];
                  return value.r < 20 ? 'end' : 'center';
                },
                color: function (context) {
                  var value = context.dataset.data[context.dataIndex];
                  return value.r < 20 ? context.dataset.backgroundColor : context.dataset.color;
                },
                font: function (context) {
                  var value = context.dataset.data[context.dataIndex],
                    fontSize = 25;

                  if (value.r > 50) {
                    fontSize = 35;
                  }

                  if (value.r > 70) {
                    fontSize = 55;
                  }

                  return {
                    weight: 'lighter',
                    size: fontSize
                  };
                },
                formatter: function (value) {
                  return value.r
                },
                offset: 2,
                padding: 0
              }
            },
          }
        })

        // INITIALIZATION OF SELECT
        // =======================================================
        HSCore.components.HSTomSelect.init('.js-select')


        // INITIALIZATION OF CLIPBOARD
        // =======================================================
        HSCore.components.HSClipboard.init('.js-clipboard')
      }
    })()
  </script>

  <!-- Style Switcher JS -->

  <script>
      (function () {
        // STYLE SWITCHER
        // =======================================================
        const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdown trigger
        const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

        // Función para guardar la preferencia de tema en el servidor
        const saveThemePreference = function (theme) {
          @if(Auth::check())
            fetch('{{ route("user.update-theme") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({ theme: theme })
            })
            .then(response => response.json())
            .then(data => {
              if (!data.success) {
                console.error('Error al guardar preferencia de tema:', data.message);
              }
            })
            .catch(error => {
              console.error('Error al guardar preferencia de tema:', error);
            });
          @endif
        }

        // Function to set active style in the dropdown menu and set icon for dropdown trigger
        const setActiveStyle = function () {
          $variants.forEach($item => {
            if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
              $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
              return $item.classList.add('active')
            }

            $item.classList.remove('active')
          })
        }

        // Función para cargar la preferencia de tema del usuario
        const loadUserThemePreference = function () {
          @if(Auth::check() && Auth::user()->theme_preference)
            const userTheme = '{{ Auth::user()->theme_preference }}';
            if (userTheme && typeof HSThemeAppearance !== 'undefined') {
              // Esperar a que HSThemeAppearance esté listo
              if (HSThemeAppearance.getOriginalAppearance) {
                HSThemeAppearance.setAppearance(userTheme);
                // Asegurarse de que el estilo activo se actualice después
                setTimeout(() => {
                  setActiveStyle();
                }, 100);
              } else {
                // Si no está listo, esperar un poco más
                setTimeout(loadUserThemePreference, 100);
              }
            }
          @endif
        }

        // Cargar preferencia de tema del usuario al iniciar (después de que el DOM esté listo)
        if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', function() {
            setTimeout(loadUserThemePreference, 200);
          });
        } else {
          setTimeout(loadUserThemePreference, 200);
        }

        // Add a click event to all items of the dropdown to set the style
        $variants.forEach(function ($item) {
          $item.addEventListener('click', function () {
            const themeValue = $item.getAttribute('data-value');
            HSThemeAppearance.setAppearance(themeValue);
            // Guardar preferencia en el servidor
            saveThemePreference(themeValue);
          })
        })

        // Call the setActiveStyle on load page
        setActiveStyle()

        // Add event listener on change style to call the setActiveStyle function
        window.addEventListener('on-hs-appearance-change', function () {
          setActiveStyle()
        })
      })()
    </script>

  <!-- End Style Switcher JS -->

  <!-- Script para manejar el estado del usuario -->
  <script>
    $(document).ready(function() {
        // Mapeo de estados a colores y etiquetas
        const estadoConfig = {
            'disponible': { color: 'success', label: 'Disponible' },
            'ocupado': { color: 'danger', label: 'Ocupado' },
            'ausente': { color: 'warning', label: 'Ausente' },
            'privado': { color: 'secondary', label: 'Privado' }
        };

        // Función para actualizar el estado visualmente
        function updateEstadoVisual(estado) {
            const config = estadoConfig[estado];
            const toggleLink = $('#navSubmenuPagesAccountDropdown1');
            
            // Actualizar el indicador y texto en el dropdown de estado
            const legendIndicator = toggleLink.find('.legend-indicator');
            legendIndicator.removeClass('bg-success bg-danger bg-warning bg-secondary')
                .addClass('bg-' + config.color);
            // Forzar color amarillo si es warning (ausente) para modo oscuro
            if (config.color === 'warning') {
                legendIndicator.attr('style', 'background-color: #ffc107 !important; border-color: #ffc107 !important;');
            } else {
                legendIndicator.removeAttr('style');
            }
            toggleLink.find('span:last').text(config.label);
            
            // Actualizar el círculo del avatar
            $('#avatar-status-indicator').removeClass('avatar-status-success avatar-status-danger avatar-status-warning avatar-status-secondary')
                .addClass('avatar-status-' + config.color);
            
            // Actualizar los checkmarks en el dropdown
            $('.estado-option').removeClass('active').find('i').remove();
            $('.estado-option[data-estado="' + estado + '"]').addClass('active')
                .append('<i class="bi-check-lg float-end"></i>');
        }

        // Manejar clic en opciones de estado
        $('.estado-option').on('click', function(e) {
            e.preventDefault();
            const estado = $(this).data('estado');
            
            // Enviar petición AJAX
            $.ajax({
                url: '{{ route("user.update-estado") }}',
                method: 'POST',
                data: {
                    estado: estado,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        updateEstadoVisual(response.estado);
                        // Cerrar el dropdown
                        $('.navbar-dropdown-sub-menu').removeClass('show');
                    }
                },
                error: function(xhr) {
                    console.error('Error al actualizar estado:', xhr.responseJSON);
                    alert('Error al actualizar el estado. Por favor, intenta nuevamente.');
                }
            });
        });

        // Manejar restablecer estado
        $('#restablecer-estado').on('click', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("user.update-estado") }}',
                method: 'POST',
                data: {
                    estado: 'disponible',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        updateEstadoVisual('disponible');
                        $('.navbar-dropdown-sub-menu').removeClass('show');
                    }
                },
                error: function(xhr) {
                    console.error('Error al restablecer estado:', xhr.responseJSON);
                    alert('Error al restablecer el estado. Por favor, intenta nuevamente.');
                }
            });
        });
    });

    // Escuchar evento de actualización de avatar
    $(document).on('avatar-updated', function(e, avatarUrl) {
        // Actualizar imágenes si existen
        if ($('#navbar-avatar-img').length) {
            $('#navbar-avatar-img').attr('src', avatarUrl);
            // Ocultar iniciales si existen
            $('#navbar-avatar-initials').hide();
        }
        if ($('#dropdown-avatar-img').length) {
            $('#dropdown-avatar-img').attr('src', avatarUrl);
            // Ocultar iniciales si existen
            $('#dropdown-avatar-initials').hide();
        }
    });
  </script>
</body>

</html>
