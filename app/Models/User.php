<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
        'avatar',
        'avatar_url',
        'banner',
        'banner_url',
        'phone',
        'department',
        'company',
        'location',
        'about',
        'theme_preference',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avatar URL attribute - siempre genera URL correcta basada en la solicitud actual
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute($value)
    {
        // Si hay un avatar guardado, generar URL usando la solicitud actual
        if ($this->avatar) {
            return $this->getImageUrl('storage/avatars/' . basename($this->avatar));
        }
        
        return null;
    }

    /**
     * Get the banner URL attribute - siempre genera URL correcta basada en la solicitud actual
     *
     * @return string|null
     */
    public function getBannerUrlAttribute($value)
    {
        // Si hay un banner guardado, generar URL usando la solicitud actual
        if ($this->banner) {
            return $this->getImageUrl('storage/banners/' . basename($this->banner));
        }
        
        return null;
    }

    /**
     * Genera URL de imagen basada en la solicitud actual (funciona con dominio e IP)
     *
     * @param string $path
     * @return string
     */
    private function getImageUrl($path)
    {
        // Usar asset() que genera URLs relativas al dominio actual
        // El JavaScript se encargará de ajustar las URLs según el hostname
        return asset($path);
    }
}
