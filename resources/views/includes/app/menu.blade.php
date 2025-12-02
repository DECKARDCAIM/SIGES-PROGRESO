 <aside
     class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white  ">


     <div class="navbar-vertical-container">
         <div class="navbar-vertical-footer-offset">

            <a class="navbar-brand" href="/" aria-label="Front">
                <img class="navbar-brand-logo" src="{{ asset('img/logotipo.svg') }}" alt="Logo"
                    data-hs-theme-appearance="default">
                <img class="navbar-brand-logo" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo"
                    data-hs-theme-appearance="dark">
                <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo.svg') }}" alt="Logo"
                    data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo"
                    data-hs-theme-appearance="dark">
            </a>
             <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                 <i class="bi-arrow-bar-left navbar-toggler-short-align"
                     data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                     data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                 <i class="bi-arrow-bar-right navbar-toggler-full-align"
                     data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                     data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
             </button>


             <div class="navbar-vertical-content">
                 <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                     <span class="dropdown-header mt-4">Módulos</span>
                     <small class="bi-three-dots nav-subtitle-replacer"></small>




                     <!-- Módulo: Métricas -->
                     <div class="nav-item">
                         <a class="nav-link" href="/metricas" data-placement="left">
                             <i class="bi-graph-up nav-icon"></i>
                             <span class="nav-link-title">Métricas</span>
                         </a>
                     </div>











                    <!-- Módulo: Inventario -->
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuInventario" role="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuInventario"
                            aria-expanded="false" aria-controls="navbarVerticalMenuInventario">
                            <i class="bi-box-seam nav-icon"></i>
                            <span class="nav-link-title">Inventario</span>
                        </a>

                        <div id="navbarVerticalMenuInventario" class="nav-collapse collapse"
                            data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}" 
                               href="{{ route('inventory.index') }}">
                                <i class="bi-list-ul me-2"></i>Gestión de Bienes
                            </a>
                            <a class="nav-link {{ request()->routeIs('inventory.create') ? 'active' : '' }}" 
                               href="{{ route('inventory.create') }}">
                                <i class="bi-plus-circle me-2"></i>Nuevo Registro
                            </a>
                            <a class="nav-link {{ request()->routeIs('inventory.transfers.*') ? 'active' : '' }}" 
                               href="{{ route('inventory.transfers.index') }}">
                                <i class="bi-arrow-left-right me-2"></i>Traspasos
                            </a>
                            <a class="nav-link {{ request()->routeIs('inventory.appraisals.*') ? 'active' : '' }}" 
                               href="{{ route('inventory.appraisals.pending') }}">
                                <i class="bi-clipboard-check me-2"></i>Dictámenes
                            </a>
                        </div>
                    </div>










                     <!-- Módulo: Informática -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuInformatica" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuInformatica"
                             aria-expanded="false" aria-controls="navbarVerticalMenuInformatica">
                             <i class="bi-laptop nav-icon"></i>
                             <span class="nav-link-title">Informática</span>
                         </a>

                         <div id="navbarVerticalMenuInformatica" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/informatica/solicitudes">Solicitudes</a>
                             <a class="nav-link" href="/informatica/realizar-dictamen">Realizar dictamen</a>
                             <a class="nav-link" href="/informatica/material">Material</a>
                             <a class="nav-link" href="/informatica/proyectos">Ver proyectos</a>
                             <a class="nav-link" href="/informatica/registrar-usuario">Registrar nuevo usuario al
                                 sistema</a>
                             <a class="nav-link" href="/informatica/backups">Backups</a>
                         </div>
                     </div>










                     <!-- Módulo: Mantenimiento -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuMantenimiento" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMantenimiento"
                             aria-expanded="false" aria-controls="navbarVerticalMenuMantenimiento">
                             <i class="bi-tools nav-icon"></i>
                             <span class="nav-link-title">Mantenimiento</span>
                         </a>

                         <div id="navbarVerticalMenuMantenimiento" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/mantenimiento/solicitudes">Solicitudes</a>
                             <a class="nav-link" href="/mantenimiento/material">Material</a>
                             <a class="nav-link" href="/mantenimiento/dictamenes">Dictámenes</a>
                             <a class="nav-link" href="/mantenimiento/proyectos">Ver proyectos</a>
                         </div>
                     </div>




                        <div class="navbar-vertical-footer">
                            <ul class="navbar-vertical-footer-list">
                             <li class="navbar-vertical-footer-list-item">
                                 <div class="dropdown dropup">
                                     <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                         id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                         data-bs-dropdown-animation>

                                     </button>

                                     <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                         aria-labelledby="selectThemeDropdown">
                                         @php
                                             $userTheme =
                                                 Auth::check() && Auth::user()->theme_preference
                                                     ? Auth::user()->theme_preference
                                                     : 'auto';
                                         @endphp
                                         <a class="dropdown-item {{ $userTheme === 'auto' ? 'active' : '' }}"
                                             href="#" data-icon="bi-moon-stars" data-value="auto">
                                             <i class="bi-moon-stars me-2"></i>
                                             <span class="text-truncate" title="Automático (Sistema Predeterminado)">Automático (Sistema Predeterminado)
                                             </span>
                                         </a>
                                         <a class="dropdown-item {{ $userTheme === 'default' ? 'active' : '' }}"
                                             href="#" data-icon="bi-brightness-high" data-value="default">
                                             <i class="bi-brightness-high me-2"></i>
                                             <span class="text-truncate" title="Claro (Modo Light)">Claro (Modo Light)
                                             </span>
                                         </a>
                                         <a class="dropdown-item {{ $userTheme === 'dark' ? 'active' : '' }}"
                                             href="#" data-icon="bi-moon" data-value="dark">
                                             <i class="bi-moon me-2"></i>
                                             <span class="text-truncate" title="Oscuro (Modo Dark)">Oscuro (Modo Dark)
                                             </span>
                                         </a>
                                     </div>
                                 </div>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </aside>