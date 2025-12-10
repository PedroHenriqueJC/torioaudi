<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $table = 'usuario';
    protected $primaryKey = 'cod_usuario';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email_usuario',
        'senha_usuario',
        'nome_usuario',
        'role_usuario',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'senha_usuario',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function getAuthPassword(){
        return $this->senha_usuario;
    }

    public function isAdmin(){
        return $this->role_usuario == 1;
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'usuario_cod_usuario', 'cod_usuario');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'senha_usuario' => 'hashed',
        ];
    }
}
