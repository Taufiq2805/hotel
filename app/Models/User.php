<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================
    // Role helper methods
    // ============================
    public function IsAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHousekeeping(): bool
    {
        return $this->role === 'housekeeping';
    }

    public function isResepsionis(): bool
    {
        return $this->role === 'resepsionis';
    }

    // ============================
    // Scope untuk query berdasarkan role
    // ============================
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeHousekeeping($query)
    {
        return $query->where('role', 'housekeeping');
    }

    public function scopeResepsionis($query)
    {
        return $query->where('role', 'resepsionis');
    }

    // ============================
    // Relasi dengan tabel lain
    // ============================
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'user_id'); // user sebagai housekeeping
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'created_by'); // user sebagai admin
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'resepsionis_id'); // user sebagai resepsionis
    }
}
