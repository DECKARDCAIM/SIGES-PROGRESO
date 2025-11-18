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
                   onerror="this.onerror=null; retryImageLoad(this);"
                   onload="hideBannerLoading();">
              <div class="image-loading-placeholder" id="banner-loading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; background: #f8f9fa; z-index: 1;">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Cargando...</span>
                </div>
              </div>

              <div class="profile-cover-content profile-cover-uploader p-3">
                <input type="file" class="profile-cover-uploader-input" id="banner-photo-input" name="banner_photo" accept="image/png,image/jpeg,image/jpg" style="display: none;">
                @if(!$user->banner_url && !$user->banner)
                <label class="profile-cover-uploader-label btn btn-sm btn-white" id="upload-banner-label" for="banner-photo-input">
                  <i class="bi-camera-fill"></i>
                  <span class="d-none d-sm-inline-block ms-1">Subir banner</span>
                </label>
                @endif
                @if($user->banner_url || $user->banner)
                <button type="button" class="btn btn-sm btn-danger" id="delete-banner-btn" onclick="deleteBanner()">
                  <i class="bi-trash"></i>
                  <span class="d-none d-sm-inline-block ms-1">Eliminar</span>
                </button>
                @endif
              </div>
            </div>
          </div>

          <div class="text-center mb-5">
            <div style="position: relative; display: inline-block; margin-bottom: 10px;">
              @php
                $nombreCompleto = $user->name ?? 'Usuario';
                $nombres = explode(' ', $nombreCompleto);
                $primerNombre = $nombres[0] ?? '';
                $segundoApellido = isset($nombres[2]) ? $nombres[2] : (isset($nombres[1]) ? $nombres[1] : '');
                $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($segundoApellido, 0, 1));
              @endphp
              @if($user->avatar_url || $user->avatar)
                <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar" for="profile-photo-input" style="position: relative;">
                  <img id="editAvatarImgModal" class="avatar-img" 
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
                  <input type="file" class="avatar-uploader-input" id="profile-photo-input" name="profile_photo" accept="image/png,image/jpeg,image/jpg" style="display: none;">
                  <span class="avatar-uploader-trigger" id="upload-avatar-trigger" style="position: absolute; bottom: 0; right: 0; z-index: 10; display: none;">
                    <i class="bi-camera-fill avatar-uploader-icon shadow-sm"></i>
                  </span>
                  <button type="button" class="btn btn-sm btn-danger" id="delete-avatar-btn" onclick="deleteAvatar()" style="position: absolute; bottom: 0; right: 0; border-radius: 50%; width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="bi-trash"></i>
                  </button>
                </label>
              @else
                <label class="avatar avatar-xxl avatar-circle avatar-soft-primary avatar-uploader profile-cover-avatar" for="profile-photo-input" style="position: relative;">
                  <span class="avatar-initials" id="avatar-initials-container">{{ $iniciales }}</span>
                  <input type="file" class="avatar-uploader-input" id="profile-photo-input" name="profile_photo" accept="image/png,image/jpeg,image/jpg" style="display: none;">
                  <span class="avatar-uploader-trigger" id="upload-avatar-trigger" style="position: absolute; bottom: 0; right: 0; z-index: 10;">
                    <i class="bi-camera-fill avatar-uploader-icon shadow-sm"></i>
                  </span>
                  <button type="button" class="btn btn-sm btn-danger" id="delete-avatar-btn" onclick="deleteAvatar()" style="position: absolute; bottom: 0; right: 0; border-radius: 50%; width: 36px; height: 36px; padding: 0; display: none; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="bi-trash"></i>
                  </button>
                </label>
              @endif
            </div>

            <h1 class="page-header-title">{{ $user->name }} <i class="bi-patch-check-fill fs-2 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Verificado"></i></h1>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-header-title">Editar Perfil</h4>
                </div>
                <div class="card-body">
                  <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                      <div class="col-md-6">
                        <label class="form-label" for="name">Nombre completo</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                      </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-md-6">
                        <label class="form-label" for="phone">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="department">Departamento</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $user->department) }}">
                      </div>
                    </div>

                    <div class="row mb-4">
                      <div class="col-md-6">
                        <label class="form-label" for="company">Empresa</label>
                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $user->company) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="location">Ubicación</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $user->location) }}">
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label" for="about">Acerca de</label>
                      <textarea class="form-control" id="about" name="about" rows="4">{{ old('about', $user->about) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                      <a href="{{ route('profile.index') }}" class="btn btn-white">Cancelar</a>
                      <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // SUBIR AVATAR INMEDIATAMENTE AL SELECCIONAR
    const profilePhotoInput = document.getElementById('profile-photo-input');
    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Solo se permiten archivos JPG, PNG.');
                e.target.value = '';
                return;
            }

            // Mostrar preview inmediatamente
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarImg = document.getElementById('editAvatarImgModal');
                const initialsContainer = document.getElementById('avatar-initials-container');
                
                if (avatarImg) {
                    avatarImg.src = e.target.result;
                } else if (initialsContainer) {
                    // Reemplazar el label completo cuando no hay foto
                    const label = initialsContainer.closest('.avatar-uploader');
                    if (label) {
                        // Remover clase avatar-soft-primary
                        label.classList.remove('avatar-soft-primary');
                        
                        // Crear la imagen
                        const newImg = document.createElement('img');
                        newImg.id = 'editAvatarImgModal';
                        newImg.className = 'avatar-img';
                        newImg.src = e.target.result;
                        newImg.alt = 'Image Description';
                        newImg.onerror = function() { this.onerror=null; retryImageLoad(this); };
                        newImg.onload = function() { hideAvatarLoading(); };
                        
                        // Reemplazar el span de iniciales con la imagen
                        initialsContainer.replaceWith(newImg);
                        
                        // Agregar placeholder de loading
                        const loadingDiv = document.createElement('div');
                        loadingDiv.className = 'image-loading-placeholder';
                        loadingDiv.id = 'avatar-loading';
                        loadingDiv.style.cssText = 'position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 50%; z-index: 1;';
                        loadingDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>';
                        label.appendChild(loadingDiv);
                    }
                }
            };
            reader.readAsDataURL(file);

            // Subir inmediatamente
            const formData = new FormData();
            formData.append('profile_photo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('{{ route("profile.update-avatar") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar con URL del servidor
                    const avatarImg = document.getElementById('editAvatarImgModal');
                    if (avatarImg) avatarImg.src = data.avatar_url;
                    
                    // Actualizar en navbar
                    const navbarImg = document.getElementById('navbar-avatar-img');
                    const dropdownImg = document.getElementById('dropdown-avatar-img');
                    if (navbarImg) navbarImg.src = data.avatar_url;
                    if (dropdownImg) dropdownImg.src = data.avatar_url;
                    
                    // Ocultar trigger de subir y mostrar botón de eliminar
                    const uploadTrigger = document.getElementById('upload-avatar-trigger');
                    if (uploadTrigger) {
                        uploadTrigger.style.display = 'none';
                    }
                    
                    // Mostrar botón de eliminar (siempre existe, solo cambiamos visibilidad)
                    const deleteBtn = document.getElementById('delete-avatar-btn');
                    if (deleteBtn) {
                        deleteBtn.style.display = 'flex';
                    }
                } else {
                    alert(data.message || 'Error al subir la foto.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión. Intenta nuevamente.');
            });
        });
    }

    // SUBIR BANNER INMEDIATAMENTE AL SELECCIONAR
    const bannerPhotoInput = document.getElementById('banner-photo-input');
    if (bannerPhotoInput) {
        bannerPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Solo se permiten archivos JPG, PNG.');
                e.target.value = '';
                return;
            }

            // Mostrar preview inmediatamente
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileCoverImg').src = e.target.result;
            };
            reader.readAsDataURL(file);

            // Subir inmediatamente
            const formData = new FormData();
            formData.append('banner_photo', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('{{ route("profile.update-banner") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar con URL del servidor
                    document.getElementById('profileCoverImg').src = data.banner_url;
                    
                    // Ocultar botón de subir y mostrar botón de eliminar
                    const uploadLabel = document.getElementById('upload-banner-label');
                    if (uploadLabel) {
                        uploadLabel.style.display = 'none';
                    }
                    
                    // Mostrar botón de eliminar si no existe
                    if (!document.getElementById('delete-banner-btn')) {
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.className = 'btn btn-sm btn-danger';
                        deleteBtn.id = 'delete-banner-btn';
                        deleteBtn.onclick = deleteBanner;
                        deleteBtn.innerHTML = '<i class="bi-trash"></i><span class="d-none d-sm-inline-block ms-1">Eliminar</span>';
                        document.querySelector('.profile-cover-uploader').appendChild(deleteBtn);
                    }
                } else {
                    alert(data.message || 'Error al subir el banner.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión. Intenta nuevamente.');
            });
        });
    }

    // ELIMINAR AVATAR
    window.deleteAvatar = function() {
        if (!confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
            return;
        }

        fetch('{{ route("profile.delete-avatar") }}', {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reemplazar imagen con iniciales
                const avatarImg = document.getElementById('editAvatarImgModal');
                const label = avatarImg ? avatarImg.closest('.avatar-uploader') : null;
                if (avatarImg && label) {
                    @php
                        $nombreCompleto = $user->name ?? 'Usuario';
                        $nombres = explode(' ', $nombreCompleto);
                        $primerNombre = $nombres[0] ?? '';
                        $segundoApellido = isset($nombres[2]) ? $nombres[2] : (isset($nombres[1]) ? $nombres[1] : '');
                        $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($segundoApellido, 0, 1));
                    @endphp
                    
                    // Agregar clase avatar-soft-primary al label
                    label.classList.add('avatar-soft-primary');
                    
                    // Crear span de iniciales
                    const initialsSpan = document.createElement('span');
                    initialsSpan.className = 'avatar-initials';
                    initialsSpan.id = 'avatar-initials-container';
                    initialsSpan.textContent = '{{ $iniciales }}';
                    
                    // Remover imagen y loading placeholder
                    avatarImg.remove();
                    const loadingPlaceholder = document.getElementById('avatar-loading');
                    if (loadingPlaceholder) loadingPlaceholder.remove();
                    
                    // Agregar iniciales
                    label.insertBefore(initialsSpan, label.firstChild);
                }
                
                // Ocultar botón de eliminar y mostrar trigger de subir
                const deleteBtn = document.getElementById('delete-avatar-btn');
                if (deleteBtn) {
                    deleteBtn.style.display = 'none';
                }
                
                // Mostrar trigger de subir (cámara) - siempre existe, solo cambiamos visibilidad
                const uploadTrigger = document.getElementById('upload-avatar-trigger');
                if (uploadTrigger) {
                    uploadTrigger.style.display = '';
                }
                
                // Actualizar navbar
                const navbarImg = document.getElementById('navbar-avatar-img');
                const dropdownImg = document.getElementById('dropdown-avatar-img');
                if (navbarImg) navbarImg.remove();
                if (dropdownImg) dropdownImg.remove();
                
                alert('Foto de perfil eliminada correctamente.');
                setTimeout(() => window.location.reload(), 500);
            } else {
                alert(data.message || 'Error al eliminar la foto.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Intenta nuevamente.');
        });
    };

    // ELIMINAR BANNER
    window.deleteBanner = function() {
        if (!confirm('¿Estás seguro de que deseas eliminar el banner?')) {
            return;
        }

        fetch('{{ route("profile.delete-banner") }}', {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Restaurar banner por defecto
                document.getElementById('profileCoverImg').src = '{{ asset("img/1920x400/img2.jpg") }}';
                
                // Eliminar botón de eliminar y mostrar botón de subir
                const deleteBtn = document.getElementById('delete-banner-btn');
                if (deleteBtn) deleteBtn.remove();
                
                // Mostrar botón de subir
                const uploadLabel = document.getElementById('upload-banner-label');
                if (!uploadLabel) {
                    const uploaderDiv = document.querySelector('.profile-cover-uploader');
                    if (uploaderDiv) {
                        const newLabel = document.createElement('label');
                        newLabel.className = 'profile-cover-uploader-label btn btn-sm btn-white';
                        newLabel.id = 'upload-banner-label';
                        newLabel.setAttribute('for', 'banner-photo-input');
                        newLabel.innerHTML = '<i class="bi-camera-fill"></i><span class="d-none d-sm-inline-block ms-1">Subir banner</span>';
                        uploaderDiv.appendChild(newLabel);
                    }
                } else {
                    uploadLabel.style.display = '';
                }
                
                alert('Banner eliminado correctamente.');
                setTimeout(() => window.location.reload(), 500);
            } else {
                alert(data.message || 'Error al eliminar el banner.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Intenta nuevamente.');
        });
    };

    // Manejar envío del formulario de perfil
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Deshabilitar botón y mostrar carga
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirigir al index
                    window.location.href = '{{ route("profile.index") }}';
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el perfil. Por favor, intenta nuevamente.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }

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
        const loadingId = imgElement.id === 'profileCoverImg' || imgElement.id === 'editAvatarImgModal' ? 
            (imgElement.id === 'profileCoverImg' ? 'banner-loading' : 'avatar-loading') : 
            (imgElement.classList.contains('profile-cover-img') ? 'banner-loading' : 'avatar-loading');
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
    const avatarImg = document.getElementById('editAvatarImgModal');
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

