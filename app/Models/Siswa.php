<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Siswa extends Model
{
    use HasRoles;
    
    protected $fillable = ['foto', 'nama', 'nis', 'gender', 'alamat', 'kontak', 'email', 'status_pkl'];

    public function pkl()
    {
        return $this->hasMany(Pkl::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     protected static function booted()
    {
        static::updated(function ($siswa) {
            if ($siswa->isDirty('email')) {
                $oldEmail = $siswa->getOriginal('email');
                $newEmail = $siswa->email;

                $user = User::where('email', $oldEmail)
                            ->whereHas('roles', function ($q) {
                                $q->where('name', 'siswa');
                            })
                            ->first();

                if ($user) {
                    $user->email = $newEmail;
                    $user->save();
                }
            }
        });
    }
}