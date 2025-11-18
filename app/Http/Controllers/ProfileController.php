<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function index()
    {
        $user = Auth::user();
        return view('modules.profile.index', compact('user'));
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('modules.profile.edit', compact('user'));
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'about' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'company' => $request->company,
                    'location' => $request->location,
                    'about' => $request->about,
                ]);
            
            $user->refresh();

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente'
                ]);
            }

            // Si no es AJAX, redirigir
            return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil: ' . $e->getMessage());
            
            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
                ], 500);
            }

            // Si no es AJAX, redirigir con error
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Actualizar el avatar del usuario
     */
    public function updateAvatar(Request $request)
    {
        // Validar que haya un archivo (puede ser 'avatar' o 'profile_photo')
        $file = $request->hasFile('avatar') ? $request->file('avatar') : $request->file('profile_photo');
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionó ningún archivo'
            ], 422);
        }

        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        try {
            $user = Auth::user();
            
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->avatar);
                $oldPublicFile = public_path('storage/avatars/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }

            // Guardar nuevo avatar en storage
            $avatarPath = $file->store('avatars', 'public');
            
            // Copiar también a public/storage para acceso directo
            $publicStoragePath = public_path('storage');
            if (!File::exists($publicStoragePath)) {
                File::makeDirectory($publicStoragePath, 0755, true);
            }
            $publicAvatarsPath = $publicStoragePath . '/avatars';
            if (!File::exists($publicAvatarsPath)) {
                File::makeDirectory($publicAvatarsPath, 0755, true);
            }
            
            $fileName = basename($avatarPath);
            $sourceFile = storage_path('app/public/' . $avatarPath);
            $destinationFile = $publicAvatarsPath . '/' . $fileName;
            
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destinationFile);
            }
            
            // Generar URL usando asset() para que funcione tanto en desktop como móvil
            $avatarUrl = asset('storage/avatars/' . $fileName);
            
            // Actualizar en la base de datos (ruta y URL)
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'avatar' => $avatarPath,
                    'avatar_url' => $avatarUrl
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Avatar actualizado correctamente',
                'avatar_url' => $avatarUrl
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar el banner del usuario
     */
    public function updateBanner(Request $request)
    {
        // Validar que haya un archivo (puede ser 'banner' o 'banner_photo')
        $file = $request->hasFile('banner') ? $request->file('banner') : $request->file('banner_photo');
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionó ningún archivo'
            ], 422);
        }

        $request->validate([
            'banner' => 'nullable|image|mimes:jpeg,png,jpg',
            'banner_photo' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        try {
            $user = Auth::user();
            
            // Eliminar banner anterior si existe
            if ($user->banner) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->banner)) {
                    Storage::disk('public')->delete($user->banner);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->banner);
                $oldPublicFile = public_path('storage/banners/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }

            // Guardar nuevo banner en storage
            $bannerPath = $file->store('banners', 'public');
            
            // Copiar también a public/storage para acceso directo
            $publicStoragePath = public_path('storage');
            if (!File::exists($publicStoragePath)) {
                File::makeDirectory($publicStoragePath, 0755, true);
            }
            $publicBannersPath = $publicStoragePath . '/banners';
            if (!File::exists($publicBannersPath)) {
                File::makeDirectory($publicBannersPath, 0755, true);
            }
            
            $fileName = basename($bannerPath);
            $sourceFile = storage_path('app/public/' . $bannerPath);
            $destinationFile = $publicBannersPath . '/' . $fileName;
            
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destinationFile);
            }
            
            // Generar URL usando asset() para que funcione tanto en desktop como móvil
            $bannerUrl = asset('storage/banners/' . $fileName);
            
            // Actualizar en la base de datos (ruta y URL)
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'banner' => $bannerPath,
                    'banner_url' => $bannerUrl
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Banner actualizado correctamente',
                'banner_url' => $bannerUrl
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar banner: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar el avatar del usuario
     */
    public function deleteAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->avatar) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->avatar);
                $oldPublicFile = public_path('storage/avatars/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }
            
            // Limpiar en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'avatar' => null,
                    'avatar_url' => null
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Avatar eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar el banner del usuario
     */
    public function deleteBanner(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->banner) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->banner)) {
                    Storage::disk('public')->delete($user->banner);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->banner);
                $oldPublicFile = public_path('storage/banners/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }
            
            // Limpiar en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'banner' => null,
                    'banner_url' => null
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Banner eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar banner: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el banner: ' . $e->getMessage()
            ], 500);
        }
    }
}

