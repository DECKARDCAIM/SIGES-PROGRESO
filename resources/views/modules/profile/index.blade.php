@extends('layouts.app')

@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">
      <div class="row justify-content-lg-center">
        <div class="col-lg-10">
          <div class="profile-cover">
            <div class="profile-cover-img-wrapper" style="position: relative;">
              <img id="profileCoverImg" class="profile-cover-img" 
                   src="{{ $user->banner_url ?? ($user->banner ? asset('storage/banners/' . basename($user->banner)) : asset('img/1920x400/img2.jpg')) }}"
                   data-src="{{ $user->banner_url ?? ($user->banner ? asset('storage/banners/' . basename($user->banner)) : asset('img/1920x400/img2.jpg')) }}"
                   alt="Image Description"
                   onerror="this.onerror=null; retryImageLoad(this);">
              <div class="image-loading-placeholder" id="banner-loading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; background: #f8f9fa; z-index: 1;">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Cargando...</span>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mb-5">
            @php
              $nombreCompleto = $user->name ?? 'Usuario';
              $nombres = explode(' ', $nombreCompleto);
              $primerNombre = $nombres[0] ?? '';
              $segundoApellido = isset($nombres[2]) ? $nombres[2] : (isset($nombres[1]) ? $nombres[1] : '');
              $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($segundoApellido, 0, 1));
            @endphp
            @if($user->avatar_url || $user->avatar)
              <div class="avatar avatar-xxl avatar-circle profile-cover-avatar" style="position: relative;">
                <img class="avatar-img" id="profile-avatar-img" 
                     src="{{ $user->avatar_url ?? asset('storage/avatars/' . basename($user->avatar)) }}"
                     data-src="{{ $user->avatar_url ?? asset('storage/avatars/' . basename($user->avatar)) }}"
                     alt="Image Description"
                     onerror="this.onerror=null; retryImageLoad(this);"
                     onload="hideAvatarLoading();">
                <div class="image-loading-placeholder" id="avatar-loading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 50%; z-index: 1;">
                  <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                </div>
              </div>
            @else
              <div class="avatar avatar-xxl avatar-circle avatar-soft-primary profile-cover-avatar">
                <span class="avatar-initials">{{ $iniciales }}</span>
              </div>
            @endif

            <h1 class="page-header-title">{{ $user->name }} <i class="bi-patch-check-fill fs-2 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Administrador"></i></h1>

            <ul class="list-inline list-px-2">
              @if($user->company)
              <li class="list-inline-item">
                <i class="bi-building me-1"></i>
                <span>{{ $user->company }}</span>
              </li>
              @endif

              @if($user->location)
              <li class="list-inline-item">
                <i class="bi-geo-alt me-1"></i>
                <span>{{ $user->location }}</span>
              </li>
              @endif

              <li class="list-inline-item">
                <i class="bi-calendar-week me-1"></i>
                @php
                  $meses = [
                    1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
                    5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
                    9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
                  ];
                  $mes = $meses[$user->created_at->format('n')];
                  $ano = $user->created_at->format('Y');
                @endphp
                <span>Se unió {{ ucfirst($mes) . ' ' . $ano }}</span>
              </li>
            </ul>
          </div>

          <div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
            <span class="hs-nav-scroller-arrow-prev" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="bi-chevron-left"></i>
              </a>
            </span>

            <span class="hs-nav-scroller-arrow-next" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="bi-chevron-right"></i>
              </a>
            </span>

            <ul class="nav nav-tabs align-items-center">
              <li class="nav-item">
                <a class="nav-link active disabled" href="#">Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#">Teams</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#">Projects <span class="badge bg-soft-dark text-dark rounded-circle ms-1">3</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#">Connections</a>
              </li>

              <li class="nav-item ms-auto">
                <div class="d-flex gap-2">
                  <a class="btn btn-white btn-sm" href="{{ route('profile.edit') }}">
                    <i class="bi-person-plus-fill me-1"></i> Editar perfil
                  </a>

                  <a class="btn btn-white btn-icon btn-sm" href="#">
                    <i class="bi-list-ul me-1"></i>
                  </a>

                  <!-- Dropdown -->
                  <div class="dropdown nav-scroller-dropdown">
                    <button type="button" class="btn btn-white btn-icon btn-sm" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi-three-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="profileDropdown">
                      <span class="dropdown-header">Settings</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-share-fill dropdown-item-icon"></i> Share profile
                      </a>
                      <a class="dropdown-item" href="#">
                        <i class="bi-slash-circle dropdown-item-icon"></i> Block page and profile
                      </a>
                      <a class="dropdown-item" href="#">
                        <i class="bi-info-circle dropdown-item-icon"></i> Suggest edits
                      </a>

                      <div class="dropdown-divider"></div>

                      <span class="dropdown-header">Feedback</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-flag dropdown-item-icon"></i> Report
                      </a>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <div class="row">
            <div class="col-lg-4">
              <div class="card card-body mb-3 mb-lg-5">
                <h5>Complete your profile</h5>

                <div class="d-flex justify-content-between align-items-center">
                  <div class="progress flex-grow-1">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <span class="ms-4">15%</span>
                </div>
              </div>
              <div class="card mb-3 mb-lg-5">
                <div class="card-header card-header-content-between">
                  <h4 class="card-header-title">Profile</h4>
                </div>
                <div class="card-body">
                  <ul class="list-unstyled list-py-2 text-dark mb-0">
                    <li class="pb-0"><span class="card-subtitle">Acerca de</span></li>
                    <li><i class="bi-person dropdown-item-icon"></i> {{ $user->name }}</li>
                    <li><i class="bi-briefcase dropdown-item-icon"></i> {{ $user->department ?? 'Sin departamento' }}</li>
                    @if($user->company)
                    <li><i class="bi-building dropdown-item-icon"></i> {{ $user->company }}</li>
                    @endif

                    <li class="pt-4 pb-0"><span class="card-subtitle">Contacto</span></li>
                    <li><i class="bi-at dropdown-item-icon"></i> {{ $user->email }}</li>
                    @if($user->phone)
                    <li><i class="bi-phone dropdown-item-icon"></i> {{ $user->phone }}</li>
                    @endif

                    <li class="pt-4 pb-0"><span class="card-subtitle">Teams</span></li>
                    <li class="fs-6 text-body"><i class="bi-people dropdown-item-icon"></i> You are not a member of any teams</li>
                    <li class="fs-6 text-body"><i class="bi-stickies dropdown-item-icon"></i> You are not working on any projects</li>
                  </ul>
                </div>
              </div>
              <div class="card card-lg mb-3 mb-lg-5">
                <div class="card-body text-center">
                  <div class="mb-4">
                    <img class="avatar avatar-xl avatar-4x3" src="{{ asset('svg/illustrations/oc-unlock.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl avatar-4x3" src="{{ asset('svg/illustrations-light/oc-unlock.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                  </div>

                  <div class="mb-3">
                    <h3>2-step verification</h3>
                    <p>Protect your account now and enable 2-step verification in the settings.</p>
                  </div>

                  <a class="btn btn-primary" href="./account-settings.html#twoStepVerificationSection">Enable now</a>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="card card-centered mb-3 mb-lg-5">
                <div class="card-header card-header-content-between">
                  <h4 class="card-header-title">Activity stream</h4>

                  <div class="dropdown">
                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="contentActivityStreamDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi-three-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="contentActivityStreamDropdown">
                      <span class="dropdown-header">Settings</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-share-fill dropdown-item-icon"></i> Share connections
                      </a>
                      <a class="dropdown-item" href="#">
                        <i class="bi-info-circle dropdown-item-icon"></i> Suggest edits
                      </a>

                      <div class="dropdown-divider"></div>

                      <span class="dropdown-header">Feedback</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-chat-left-dots dropdown-item-icon"></i> Report
                      </a>
                    </div>
                  </div>
                </div>
                <div class="card-body card-body-height">
                  <img class="avatar avatar-xxl mb-3" src="{{ asset('svg/illustrations/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                  <img class="avatar avatar-xxl mb-3" src="{{ asset('svg/illustrations-light/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                  <p class="card-text">No data to show</p>
                  <a class="btn btn-white btn-sm" href="./#">Start your Activity</a>
                </div>
              </div>
              <div class="card card-centered mb-3 mb-lg-5">
                <div class="card-header card-header-content-between">
                  <h4 class="card-header-title">Projects</h4>

                  <div class="dropdown">
                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="projectReportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi-three-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="projectReportDropdown">
                      <span class="dropdown-header">Settings</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-share-fill dropdown-item-icon"></i> Share connections
                      </a>
                      <a class="dropdown-item" href="#">
                        <i class="bi-info-circle dropdown-item-icon"></i> Suggest edits
                      </a>

                      <div class="dropdown-divider"></div>

                      <span class="dropdown-header">Feedback</span>

                      <a class="dropdown-item" href="#">
                        <i class="bi-chat-left-dots dropdown-item-icon"></i> Report
                      </a>
                    </div>
                  </div>
                </div>
                <div class="card-body card-body-height card-body-centered">
                  <img class="avatar avatar-xxl mb-3" src="{{ asset('svg/illustrations/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                  <img class="avatar avatar-xxl mb-3" src="{{ asset('svg/illustrations-light/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                  <p class="card-text">No data to show</p>
                  <a class="btn btn-white btn-sm" href="./projects.html">Start your Projects</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar imágenes con múltiples intentos
    function loadImageWithRetry(imgElement, maxRetries = 3, retryDelay = 1000) {
        let retryCount = 0;
        const originalSrc = imgElement.getAttribute('data-src');
        
        if (!originalSrc) {
            hideLoadingPlaceholder(imgElement);
            return;
        }

        function tryLoad() {
            // Generar URL basada en la URL actual
            let imageUrl = originalSrc;
            
            // Normalizar URL basada en la URL actual
            const currentHost = window.location.hostname;
            const currentOrigin = window.location.origin;
            
            // Si la URL original contiene localhost o 127.0.0.1, o no coincide con el host actual
            if (originalSrc.includes('localhost') || originalSrc.includes('127.0.0.1') || 
                !originalSrc.includes(currentHost)) {
                
                // Extraer el nombre del archivo
                const pathMatch = originalSrc.match(/\/storage\/([^\/]+\/[^\/]+)$/);
                if (pathMatch) {
                    // Intentar diferentes variaciones según el hostname
                    if (currentHost === '192.168.1.219') {
                        // Primero intentar sin subdirectorio
                        imageUrl = currentOrigin + '/storage/' + pathMatch[1];
                    } else if (currentHost === 'hospro-workstation.test') {
                        imageUrl = currentOrigin + '/storage/' + pathMatch[1];
                    } else {
                        imageUrl = currentOrigin + '/storage/' + pathMatch[1];
                    }
                }
            }
            
            // Si la URL no es absoluta, hacerla absoluta
            if (!imageUrl.startsWith('http://') && !imageUrl.startsWith('https://')) {
                imageUrl = currentOrigin + '/' + imageUrl.replace(/^\//, '');
            }

            const testImg = new Image();
            
            testImg.onload = function() {
                imgElement.src = imageUrl;
                hideLoadingPlaceholder(imgElement);
            };
            
            testImg.onerror = function() {
                retryCount++;
                
                if (retryCount < maxRetries) {
                    // Intentar con diferentes variaciones de URL
                    setTimeout(() => {
                        // Intentar con diferentes variaciones de URL
                        const pathMatch = originalSrc.match(/\/storage\/([^\/]+\/[^\/]+)$/);
                        if (pathMatch) {
                            const fallbackUrls = [];
                            const hostname = window.location.hostname;
                            
                            if (hostname === '192.168.1.219') {
                                // Para IP, intentar ambas variaciones
                                fallbackUrls.push(
                                    window.location.origin + '/storage/' + pathMatch[1],
                                    window.location.origin + '/HOSPRO-WorkStation/public/storage/' + pathMatch[1]
                                );
                            } else if (hostname === 'hospro-workstation.test') {
                                fallbackUrls.push(
                                    window.location.origin + '/storage/' + pathMatch[1]
                                );
                            } else {
                                // Para otros dominios, intentar con el path actual
                                fallbackUrls.push(window.location.origin + '/storage/' + pathMatch[1]);
                            }
                            
                            const fallbackIndex = retryCount - 1;
                            if (fallbackIndex < fallbackUrls.length) {
                                testImg.src = fallbackUrls[fallbackIndex];
                            } else {
                                // Si se agotaron las URLs de fallback, reintentar con delay
                                setTimeout(() => tryLoad(), retryDelay * retryCount);
                            }
                        } else {
                            setTimeout(() => tryLoad(), retryDelay * retryCount);
                        }
                    }, retryDelay * retryCount);
                } else {
                    // Máximo de intentos alcanzado, mostrar placeholder o iniciales
                    hideLoadingPlaceholder(imgElement);
                    showImageError(imgElement);
                }
            };
            
            testImg.src = imageUrl;
        }
        
        tryLoad();
    }

    function hideLoadingPlaceholder(imgElement) {
        const loadingId = imgElement.id === 'profileCoverImg' ? 'banner-loading' : 'avatar-loading';
        const loadingPlaceholder = document.getElementById(loadingId);
        if (loadingPlaceholder) {
            loadingPlaceholder.style.display = 'none';
        }
    }

    function showImageError(imgElement) {
        // Si es el avatar, mostrar iniciales en su lugar
        if (imgElement.classList.contains('avatar-img')) {
            const avatarContainer = imgElement.closest('.avatar');
            if (avatarContainer) {
                imgElement.style.display = 'none';
            }
        }
    }

    // Función para ocultar el loading del banner cuando la imagen carga
    window.hideBannerLoading = function() {
        const bannerLoading = document.getElementById('banner-loading');
        if (bannerLoading) {
            bannerLoading.style.display = 'none';
        }
    };

    // Función para ocultar el loading del avatar cuando la imagen carga
    window.hideAvatarLoading = function() {
        const avatarLoading = document.getElementById('avatar-loading');
        if (avatarLoading) {
            avatarLoading.style.display = 'none';
        }
    };

    // Función global para reintentar carga de imágenes
    window.retryImageLoad = function(imgElement) {
        if (imgElement.dataset.retryCount) {
            imgElement.dataset.retryCount = parseInt(imgElement.dataset.retryCount) + 1;
        } else {
            imgElement.dataset.retryCount = '1';
        }

        if (parseInt(imgElement.dataset.retryCount) <= 3) {
            setTimeout(() => {
                loadImageWithRetry(imgElement);
            }, 1000 * parseInt(imgElement.dataset.retryCount));
        } else {
            showImageError(imgElement);
        }
    };

    // Cargar banner - verificar si ya cargó o necesita retry
    const bannerImg = document.getElementById('profileCoverImg');
    if (bannerImg) {
        // Si la imagen ya tiene src y está cargada, ocultar loading
        if (bannerImg.complete && bannerImg.naturalHeight !== 0) {
            hideBannerLoading();
        } else {
            // Si no está cargada, intentar cargar con retry
            bannerImg.addEventListener('load', hideBannerLoading);
            loadImageWithRetry(bannerImg);
        }
    }

    // Cargar avatar - verificar si ya cargó o necesita retry
    const avatarImg = document.getElementById('profile-avatar-img');
    if (avatarImg) {
        // Si la imagen ya tiene src y está cargada, ocultar loading
        if (avatarImg.complete && avatarImg.naturalHeight !== 0) {
            hideAvatarLoading();
        } else {
            // Si no está cargada, intentar cargar con retry
            avatarImg.addEventListener('load', hideAvatarLoading);
            loadImageWithRetry(avatarImg);
        }
    }
});
</script>
@endsection