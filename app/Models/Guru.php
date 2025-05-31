<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class guru extends Model
{
    use HasRoles;
    
    protected $fillable = ['nama', 'nip', 'gender', 'alamat', 'kontak', 'email'];

    public function pkl()
    {
        return $this->hasMany(pkl::class);
    }

    public function industri()
    {
        return $this->hasMany(industri::class);
    }
}