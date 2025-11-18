@php
use Illuminate\Support\Facades\Storage;
@endphp

<header id="header"
    class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <a class="navbar-brand" href="/" aria-label="Front">
            <img class="navbar-brand-logo" src="{{ asset('img/logotipo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
        </a>

        <div class="navbar-nav-wrap-content-start">
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>

            <div class="dropdown ms-2">
                <div class="d-none d-lg-block">
                    <div class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input type="search" class="js-form-search form-control" placeholder="Buscar" aria-label="Buscar" data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus" }'>
                        <a class="input-group-append input-group-text" href="javascript:;">
                            <i id="clearSearchResultsIcon" class="bi-x-lg" style="display: none;"></i>
                        </a>
                    </div>
                </div>

                <button
                    class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none"
                    type="button"
                    data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus"}'>
                    <i class="bi-search"></i>
                </button>

                <div id="searchDropdownMenu"
                    class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless">
                    <div class="card">
                        <div class="card-body-height">
                            <div class="d-lg-none">
                                <div class="input-group input-group-merge navbar-input-group mb-5">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Buscar" aria-label="Buscar">
                                    <a class="input-group-append input-group-text" href="javascript:;"> <i class="bi-x-lg"></i></a>
                                </div>
                            </div>

                            <span class="dropdown-header">Busquedas recientes</span>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Busqueda 1 <i class="bi-search ms-1"></i>
                                </a>
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Panel de notificaciones <i class="bi-search ms-1"></i>
                                </a>
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Busqueda 3 <i class="bi-search ms-1"></i>
                                </a>
                            </div>

                            <div class="dropdown-divider"></div>

                            <span class="dropdown-header">Tutoriales</span>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="icon icon-soft-dark icon-xs icon-circle">
                                            <i class="bi-sliders"></i>
                                        </span>
                                    </div>

                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Soporte técnico</span>
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-divider"></div>

                            <span class="dropdown-header">Miembros</span>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xs avatar-circle"
                                            src="{{ asset('img/160x160/img10.jpg') }}" alt="Image Description">
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Amanda Harvey <i class="tio-verified text-primary" data-toggle="tooltip"
                                                data-placement="top" title="Top endorsed"></i></span>
                                    </div>
                                </div>
                            </a>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xs avatar-circle"
                                            src="{{ asset('img/160x160/img3.jpg') }}" alt="Image Description">
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>David Harrison</span>
                                    </div>
                                </div>
                            </a>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-xs avatar-soft-info avatar-circle">
                                            <span class="avatar-initials">A</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Anne Richard</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <a class="card-footer text-center" href="/">
                            Ver todos los resultados <i class="bi-chevron-right small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>










        <div class="navbar-nav-wrap-content-end">
            <ul class="navbar-nav">
                <li class="nav-item d-none d-sm-inline-block">

                    <div class="dropdown">
                        <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                            id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
                            <i class="bi-bell"></i>
                            <span class="btn-status btn-sm-status btn-status-danger"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                            aria-labelledby="navbarNotificationsDropdown" style="width: 25rem;">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h4 class="card-title mb-0">Notificaciones</h4>

                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                                            id="navbarNotificationsDropdownSettings" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi-three-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                            aria-labelledby="navbarNotificationsDropdownSettings">
                                            <span class="dropdown-header">Configuraciones</span>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-archive dropdown-item-icon"></i> Archivar todas
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-check2-all dropdown-item-icon"></i> Marcar todas como leídas
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-toggle-off dropdown-item-icon"></i> Deshabilitar notificaciones
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-gift dropdown-item-icon"></i> Que hay de nuevo?
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <span class="dropdown-header">Retroalimentación</span>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-chat-left-dots dropdown-item-icon"></i> Reportar
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs nav-justified" id="notificationTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#notificationNavOne"
                                            id="notificationNavOne-tab" data-bs-toggle="tab"
                                            data-bs-target="#notificationNavOne" role="tab"
                                            aria-controls="notificationNavOne" aria-selected="true">Mensajes (3)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#notificationNavTwo" id="notificationNavTwo-tab"
                                            data-bs-toggle="tab" data-bs-target="#notificationNavTwo" role="tab"
                                            aria-controls="notificationNavTwo" aria-selected="false">Archivados</a>
                                    </li>
                                </ul>

                                <div class="card-body-height">
                                    <div class="tab-content" id="notificationTabContent">
                                        <div class="tab-pane fade show active" id="notificationNavOne"
                                            role="tabpanel" aria-labelledby="notificationNavOne-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck1"
                                                                        checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck1"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <img class="avatar avatar-sm avatar-circle"
                                                                    src="{{ asset('img/160x160/img3.jpg') }}"
                                                                    alt="Image Description">
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Brian Warner</h5>
                                                            <p class="text-body fs-5">changed an issue from "In
                                                                Progress" to <span
                                                                    class="badge bg-success">Review</span></p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2hr</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck2"
                                                                        checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck2"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">K</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Klara Hampton</h5>
                                                            <p class="text-body fs-5">mentioned you in a comment</p>
                                                            <blockquote class="blockquote blockquote-sm">
                                                                Nice work, love! You really nailed it. Keep it up!
                                                            </blockquote>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">10hr</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck3"
                                                                        checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck3"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img10.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Ruby Walter</h5>
                                                            <p class="text-body fs-5">joined the Slack group HS Team
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">3dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck4">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck4"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('svg/brands/google-icon.svg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">from Google</h5>
                                                            <p class="text-body fs-5">Start using forms to capture the
                                                                information of prospects visiting your Google website
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">17dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck5">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck5"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img7.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Sara Villar</h5>
                                                            <p class="text-body fs-5">completed <i
                                                                    class="bi-journal-bookmark-fill text-primary"></i>
                                                                FD-7 task</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2mn</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-pane fade" id="notificationNavTwo" role="tabpanel"
                                            aria-labelledby="notificationNavTwo-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck6">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck6"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">A</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Anne Richard</h5>
                                                            <p class="text-body fs-5">accepted your invitation to join
                                                                Notion</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">1dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck7">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck7"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img5.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Finch Hoot</h5>
                                                            <p class="text-body fs-5">left Slack group HS projects</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">1dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck8">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck8"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-dark avatar-circle">
                                                                    <span class="avatar-initials">HS</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Htmlstream</h5>
                                                            <p class="text-body fs-5">you earned a "Top endorsed" <i
                                                                    class="bi-patch-check-fill text-primary"></i> badge
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">6dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck9">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck9"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img8.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Linda Bates</h5>
                                                            <p class="text-body fs-5">Accepted your connection</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">17dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck10">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck10"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">L</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Lewis Clarke</h5>
                                                            <p class="text-body fs-5">completed <i
                                                                    class="bi-journal-bookmark-fill text-primary"></i>
                                                                FD-134 task</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2mts</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <a class="card-footer text-center" href="#">
                                    Ver todas las notificaciones <i class="bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>










                <li class="nav-item d-none d-sm-inline-block">
                    <div class="dropdown">
                        <button type="button" class="btn btn-icon btn-ghost-secondary rounded-circle"
                            id="navbarAppsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-dropdown-animation>
                            <i class="bi-app-indicator"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                            aria-labelledby="navbarAppsDropdown" style="width: 25rem;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Aplicaciones web &amp; servicios</h4>
                                </div>
                                <div class="card-body card-body-height">
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/atlassian-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Atlassian</h5>
                                                <p class="card-text text-body">Seguridad y control en la nube</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/slack-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Slack <span
                                                        class="badge bg-primary rounded-pill text-uppercase ms-1">Try</span>
                                                </h5>
                                                <p class="card-text text-body">Software de colaboración de email</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/google-webdev-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Google webdev</h5>
                                                <p class="card-text text-body">Trabajo involucrado en el desarrollo de un sitio web
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/frontapp-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Frontapp</h5>
                                                <p class="card-text text-body">El buzón para equipos</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/illustrations/review-rating-shield.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">HS Support</h5>
                                                <p class="card-text text-body">Servicio al cliente y soporte</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm avatar-soft-dark">
                                                    <span class="avatar-initials"><i class="bi-grid"></i></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Más productos Front</h5>
                                                <p class="card-text text-body">Revisa más productos HS</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a class="card-footer text-center" href="#">
                                    Ver todas las aplicaciones <i class="bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <button class="btn btn-ghost-secondary btn-icon rounded-circle" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasActivityStream"
                        aria-controls="offcanvasActivityStream">
                        <i class="bi-x-diamond"></i>
                    </button>
                </li>










                <li class="nav-item">
                    <div class="dropdown">
                        <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"
                            data-bs-dropdown-animation>
                            <div class="avatar avatar-sm avatar-circle">
                                @php
                                    $user = Auth::user();
                                    $estadoActual = $user->estado ?? 'disponible';
                                    $estadoAvatarColors = [
                                        'disponible' => 'success',
                                        'ocupado' => 'danger',
                                        'ausente' => 'warning',
                                        'privado' => 'secondary'
                                    ];
                                    $nombreCompleto = $user->name ?? 'Usuario';
                                    $nombres = explode(' ', $nombreCompleto);
                                    $primerNombre = $nombres[0] ?? '';
                                    $segundoApellido = isset($nombres[2]) ? $nombres[2] : (isset($nombres[1]) ? $nombres[1] : '');
                                    $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($segundoApellido, 0, 1));
                                @endphp
                                @if($user && ($user->avatar_url || $user->avatar))
                                    <img class="avatar-img" id="navbar-avatar-img" 
                                         src="{{ $user->avatar_url ?? asset('storage/avatars/' . basename($user->avatar)) }}" 
                                         alt="Image Description"
                                         onerror="this.onerror=null; retryNavbarImage(this);">
                                @else
                                    <div class="avatar-img avatar-soft-primary" id="navbar-avatar-initials">
                                        <span class="avatar-initials">{{ $iniciales }}</span>
                                    </div>
                                @endif
                                <span class="avatar-status avatar-sm-status avatar-status-{{ $estadoAvatarColors[$estadoActual] }}" id="avatar-status-indicator"></span>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account"
                            aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    @php
                                        $user = Auth::user();
                                        $nombreCompleto = $user->name ?? 'Usuario';
                                        $nombres = explode(' ', $nombreCompleto);
                                        $primerNombre = $nombres[0] ?? '';
                                        $segundoApellido = isset($nombres[2]) ? $nombres[2] : (isset($nombres[1]) ? $nombres[1] : '');
                                        $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($segundoApellido, 0, 1));
                                        $primerApellido = isset($nombres[1]) ? $nombres[1] : '';
                                        $nombreMostrar = trim($primerNombre . ' ' . $primerApellido) ?: $nombreCompleto;
                                    @endphp
                                    <div class="avatar avatar-sm avatar-circle">
                                        @if($user && ($user->avatar_url || $user->avatar))
                                            <img class="avatar-img" id="dropdown-avatar-img" 
                                                 src="{{ $user->avatar_url ?? asset('storage/avatars/' . basename($user->avatar)) }}" 
                                                 alt="Image Description"
                                                 onerror="this.onerror=null; retryNavbarImage(this);">
                                        @else
                                            <div class="avatar-img avatar-soft-primary" id="dropdown-avatar-initials">
                                                <span class="avatar-initials">{{ $iniciales }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ $nombreMostrar }}</h5>
                                        <p class="card-text text-body">{{ $user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <div class="dropdown">
                                <a class="navbar-dropdown-submenu-item dropdown-item dropdown-toggle"
                                    href="javascript:;" id="navSubmenuPagesAccountDropdown1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Estado
                                    @php
                                        $estadoActual = Auth::user()->estado ?? 'disponible';
                                        $estadoLabels = [
                                            'disponible' => 'Disponible',
                                            'ocupado' => 'Ocupado',
                                            'ausente' => 'Ausente',
                                            'privado' => 'Privado'
                                        ];
                                        $estadoColors = [
                                            'disponible' => 'success',
                                            'ocupado' => 'danger',
                                            'ausente' => 'warning',
                                            'privado' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="legend-indicator bg-{{ $estadoColors[$estadoActual] }} ms-2" 
                                          @if($estadoColors[$estadoActual] === 'warning') style="background-color: #ffc107 !important; border-color: #ffc107 !important;" @endif></span>
                                    <span class="ms-1">{{ $estadoLabels[$estadoActual] }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-sub-menu"
                                    aria-labelledby="navSubmenuPagesAccountDropdown1">
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'disponible' ? 'active' : '' }}" 
                                       href="javascript:;" 
                                       data-estado="disponible">
                                        <span class="legend-indicator bg-success me-1"></span> Disponible
                                        @if($estadoActual === 'disponible')
                                            <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'ocupado' ? 'active' : '' }}" 
                                       href="javascript:;" 
                                       data-estado="ocupado">
                                        <span class="legend-indicator bg-danger me-1"></span> Ocupado
                                        @if($estadoActual === 'ocupado')
                                            <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'ausente' ? 'active' : '' }}" 
                                       href="javascript:;" 
                                       data-estado="ausente">
                                        <span class="legend-indicator bg-warning me-1" style="background-color: #ffc107 !important; border-color: #ffc107 !important;"></span> Ausente
                                        @if($estadoActual === 'ausente')
                                            <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'privado' ? 'active' : '' }}" 
                                       href="javascript:;" 
                                       data-estado="privado">
                                        <span class="legend-indicator bg-secondary me-1"></span> Privado
                                        @if($estadoActual === 'privado')
                                            <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:;" id="restablecer-estado">
                                        Restablecer estado
                                    </a>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('profile.index') }}">Mi Perfil</a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem; cursor: pointer;">
                                    Cerrar sesión
                                </button>
                            </form>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
// Función para reintentar carga de imágenes del navbar
function retryNavbarImage(imgElement) {
    if (!imgElement.dataset.retryCount) {
        imgElement.dataset.retryCount = '0';
    }
    
    imgElement.dataset.retryCount = parseInt(imgElement.dataset.retryCount) + 1;
    
    if (parseInt(imgElement.dataset.retryCount) <= 3) {
        const originalSrc = imgElement.src;
        let newSrc = originalSrc;
        
        // Intentar con diferentes variaciones de URL
        if (window.location.hostname === '192.168.1.219') {
            const pathMatch = originalSrc.match(/\/storage\/avatars\/(.+)$/);
            if (pathMatch) {
                const fallbackUrls = [
                    window.location.origin + '/storage/avatars/' + pathMatch[1],
                    window.location.origin + '/HOSPRO-WorkStation/public/storage/avatars/' + pathMatch[1],
                ];
                newSrc = fallbackUrls[parseInt(imgElement.dataset.retryCount) - 1] || fallbackUrls[0];
            }
        } else if (window.location.hostname === 'hospro-workstation.test') {
            const pathMatch = originalSrc.match(/\/storage\/avatars\/(.+)$/);
            if (pathMatch) {
                newSrc = window.location.origin + '/storage/avatars/' + pathMatch[1];
            }
        } else {
            // Reemplazar localhost o 127.0.0.1 con el hostname actual
            newSrc = originalSrc.replace(/https?:\/\/[^\/]+/, window.location.origin);
        }
        
        const testImg = new Image();
        testImg.onload = function() {
            imgElement.src = newSrc;
        };
        testImg.onerror = function() {
            if (parseInt(imgElement.dataset.retryCount) < 3) {
                setTimeout(() => retryNavbarImage(imgElement), 1000 * parseInt(imgElement.dataset.retryCount));
            } else {
                // Ocultar imagen y mostrar iniciales si es posible
                imgElement.style.display = 'none';
                const container = imgElement.closest('.avatar');
                if (container) {
                    const initials = container.querySelector('.avatar-initials');
                    if (!initials) {
                        const initialsDiv = document.createElement('div');
                        initialsDiv.className = 'avatar-img avatar-soft-primary';
                        initialsDiv.innerHTML = '<span class="avatar-initials">' + (imgElement.alt.charAt(0) || 'U') + '</span>';
                        container.appendChild(initialsDiv);
                    }
                }
            }
        };
        testImg.src = newSrc;
    }
}
</script>