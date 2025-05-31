<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class industri extends Model
{
    protected $fillable =['nama', 'website', 'bidang_usaha', 'alamat', 'kontak', 'email'];

    public function pkl()
    {
        return $this->hasMany(pkl::class);
    }

    // public function guru ()
    // {
    //     return $this->belongsTo(guru::class, 'guru_pembimbing');
    // }
}