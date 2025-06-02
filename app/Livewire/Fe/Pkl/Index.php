<?php

namespace App\Livewire\Fe\Pkl;

use App\Models\Pkl;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Industri;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    public $siswaId, $industriId, $guruId, $mulai, $selesai;
    public $isOpen = 0;
    public $editMode = false;
    public $editingId = null;
    public $pklIdToDelete = null;

    use WithPagination;

    public $rowPerPage = 10;
    public $search;
    public $userMail;
    public $siswa_login;

    public function mount()
    {
        $this->userMail = Auth::user()->email;
        $this->siswa_login = Siswa::where('email', '=', $this->userMail)->first();
    }
    
    public function render()
    {
        return view('livewire.fe.pkl.index', [
            'pkls' => $this->search === NULL ?
                        Pkl::latest()->paginate($this->rowPerPage) :
                        Pkl::latest()->whereHas('siswa', function ($query) {
                                                $query->where('nama', 'like', '%' . $this->search . '%');
                                            })
                                    ->orWhereHas('industri', function ($query) {
                                                $query->where('nama', 'like', '%' . $this->search . '%');
                                    })->paginate($this->rowPerPage),
            
            'siswa_login' => $this->siswa_login,
            'industris' => Industri::all(),
            'gurus' => Guru::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->editMode = false;
        $this->openModal();
    }
    
    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->editMode = false;
        $this->editingId = null;
    }

    private function resetInputFields()
    {
        $this->siswaId = '';
        $this->industriId = '';
        $this->guruId = '';
        $this->mulai = '';
        $this->selesai = '';
    }

    public function canEditDelete($pklSiswaId)
    {
        return $this->siswa_login && $this->siswa_login->id == $pklSiswaId;
    }

    private function validateTanggalMulai($attribute, $value, $fail)
    {
        $mulaiDate = Carbon::parse($value);
        $currentYear = Carbon::now()->year;
        $julyFirst = Carbon::create($currentYear, 7, 1);
        
        if (Carbon::now()->lt($julyFirst)) {
            $julyFirst = Carbon::create($currentYear, 7, 1);
        } else {
            $julyFirstThisYear = Carbon::create($currentYear, 7, 1);
            $julyFirstNextYear = Carbon::create($currentYear + 1, 7, 1);
            
            if ($mulaiDate->lt($julyFirstThisYear)) {
                $fail('Tanggal mulai harus minimal tanggal 1 Juli ' . $currentYear . ' atau setelahnya.');
                return;
            }
        }
        
        if ($mulaiDate->lt($julyFirst)) {
            $fail('Tanggal mulai harus minimal tanggal 1 Juli atau setelahnya.');
        }
    }

    private function validateDurasi($attribute, $value, $fail)
    {
        if ($value && $this->mulai) {
            $mulaiDate = Carbon::parse($this->mulai);
            $selesaiDate = Carbon::parse($value);
            $durasiHari = $mulaiDate->diffInDays($selesaiDate) + 1;
            if ($durasiHari < 90) {
                $fail('Durasi PKL harus minimal 90 hari. Saat ini durasi adalah ' . $durasiHari . ' hari.');
            }
        }
    }

    public function updatedMulai()
    {
        if ($this->mulai) {
            $mulaiDate = Carbon::parse($this->mulai);
            if (!$this->selesai) {
                $this->selesai = $mulaiDate->addDays(89)->format('Y-m-d');
            }
        }
    }

    public function store()
    {
        $this->validate([
            'siswaId' => 'required',
            'industriId' => 'required',
            'guruId' => 'nullable|exists:gurus,id',
            'mulai' => [
                'required', 
                'date',
                function($attribute, $value, $fail) {
                    $this->validateTanggalMulai($attribute, $value, $fail);
                }
            ],
            'selesai' => [
                'required', 
                'date', 
                'after_or_equal:mulai',
                function($attribute, $value, $fail) {
                    $this->validateDurasi($attribute, $value, $fail);
                }
            ],
        ], [
            'siswaId.required' => 'Siswa harus dipilih.',
            'industriId.required' => 'Industri harus dipilih.',
            'guruId.exists' => 'Guru yang dipilih tidak valid.',
            'mulai.required' => 'Tanggal mulai harus diisi.',
            'mulai.date' => 'Format tanggal mulai tidak valid.',
            'selesai.required' => 'Tanggal selesai harus diisi.',
            'selesai.date' => 'Format tanggal selesai tidak valid.',
            'selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        // Validasi durasi minimal 90 hari
        $mulaiDate = Carbon::parse($this->mulai);
        $selesaiDate = Carbon::parse($this->selesai);
        $durasiHari = $mulaiDate->diffInDays($selesaiDate) + 1;
        if ($durasiHari < 90) {
            session()->flash('error', 'Durasi PKL harus minimal 90 hari. Durasi saat ini: ' . $durasiHari . ' hari.');
            return;
        }

        DB::beginTransaction();
        try {
            $existingPkl = Pkl::where('siswa_id', $this->siswaId)->first();
            if ($existingPkl && !$this->editMode) {
                throw new \Exception('Siswa sudah memiliki data PKL.');
            }

            if ($this->editMode) {
                $pkl = Pkl::findOrFail($this->editingId);
                $pkl->update([
                    'siswa_id' => $this->siswaId,
                    'industri_id' => $this->industriId,
                    'guru_id' => $this->guruId ?: null,
                    'mulai' => $this->mulai,
                    'selesai' => $this->selesai,
                ]);
                session()->flash('success', 'Data PKL berhasil diupdate.');
            } else {
                Pkl::create([
                    'siswa_id' => $this->siswaId,
                    'industri_id' => $this->industriId,
                    'guru_id' => $this->guruId ?: null,
                    'mulai' => $this->mulai,
                    'selesai' => $this->selesai,
                ]);

                $siswa = Siswa::find($this->siswaId);
                if ($siswa) {
                    $siswa->update(['status_pkl' => 1]);
                }
                session()->flash('success', 'Data PKL berhasil ditambahkan.');
            }

            DB::commit();
            $this->closeModal();
            $this->resetInputFields();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pkl = Pkl::findOrFail($id);
        if ($pkl->siswa_id !== $this->siswa_login->id) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit data ini.');
            return;
        }

        $this->editingId = $id;
        $this->siswaId = $pkl->siswa_id;
        $this->industriId = $pkl->industri_id;
        $this->guruId = $pkl->guru_id;
        $this->mulai = $pkl->mulai;
        $this->selesai = $pkl->selesai;
        
        $this->editMode = true;
        $this->openModal();
    }

    public function setPklIdToDelete($id)
    {
        $this->pklIdToDelete = $id;
    }

    public function confirmDelete()
    {
        if (!$this->pklIdToDelete) {
            session()->flash('error', 'Tidak ada data yang dipilih untuk dihapus.');
            return;
        }

        $pkl = Pkl::findOrFail($this->pklIdToDelete);
        if ($pkl->siswa_id !== $this->siswa_login->id) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
            $this->pklIdToDelete = null;
            return;
        }

        DB::beginTransaction();
        try {
            $siswa = Siswa::find($pkl->siswa_id);
            if ($siswa) {
                Log::info('Before update:', ['status_pkl' => $siswa->status_pkl]);
                $pkl->delete();
                $siswa->update(['status_pkl' => 0]);
                Log::info('After update:', ['status_pkl' => $siswa->status_pkl]);
            }

            DB::commit();
            session()->flash('success', 'Data PKL berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        $this->pklIdToDelete = null;
    }

    public function getMinStartDate()
    {
        $currentYear = Carbon::now()->year;
        $julyFirst = Carbon::create($currentYear, 7, 1);
        
        if (Carbon::now()->lt($julyFirst)) {
            return $julyFirst->format('Y-m-d');
        } else {
            return Carbon::now()->format('Y-m-d');
        }
    }
}
