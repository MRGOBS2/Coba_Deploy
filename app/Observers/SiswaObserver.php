<?php

namespace App\Observers;

use App\Models\Siswa;

class SiswaObserver
{
    /**
     * Handle the Siswa "created" event.
     */
    public function created(Siswa $siswa): void
    {
        //
    }

    /**
     * Handle the Siswa "updated" event.
     */
    public function updated(Siswa $siswa): void
    {
        // Cek jika email berubah dan siswa memiliki user terkait
        if ($siswa->isDirty('email') && $siswa->user) {
            $siswa->user->email = $siswa->email;
            $siswa->user->save();
        }
    }

    /**
     * Handle the Siswa "deleted" event.
     */
    public function deleted(Siswa $siswa): void
    {
        //
    }

    /**
     * Handle the Siswa "restored" event.
     */
    public function restored(Siswa $siswa): void
    {
        //
    }

    /**
     * Handle the Siswa "force deleted" event.
     */
    public function forceDeleted(Siswa $siswa): void
    {
        //
    }
}
