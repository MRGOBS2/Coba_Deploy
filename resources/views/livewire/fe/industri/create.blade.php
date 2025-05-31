<div class="fixed inset-0 z-10 overflow-y-auto ease-out duration-400">
  <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    
   <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
    
    <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      
      <div class="bg-gradient-to-r from-green-500 to-blue-500 py-4 px-4">
        <h2 class="font-bold text-lg text-white text-center">Tambah Industri</h2>
      </div>

      <form wire:submit.prevent="store" class="px-6 py-4">
        
        {{-- Nama --}}
        <div class="mb-4">
          <label for="nama" class="block text-sm font-medium text-gray-900 mb-1">Nama Industri</label>
          <input type="text" id="nama" wire:model.defer="nama" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan nama industri">
          @error('nama') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Bidang Usaha --}}
        <div class="mb-4">
          <label for="bidang_usaha" class="block text-sm font-medium text-gray-900 mb-1">Bidang Usaha</label>
          <input type="text" id="bidang_usaha" wire:model.defer="bidang_usaha" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan bidang usaha">
          @error('bidang_usaha') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Alamat --}}
        <div class="mb-4">
          <label for="alamat" class="block text-sm font-medium text-gray-900 mb-1">Alamat</label>
          <input type="text" id="alamat" wire:model.defer="alamat" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan alamat">
          @error('alamat') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Kontak --}}
        <div class="mb-4">
          <label for="kontak" class="block text-sm font-medium text-gray-900 mb-1">Kontak</label>
          <input type="text" id="kontak" wire:model.defer="kontak" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan kontak">
          @error('kontak') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-900 mb-1">Email</label>
          <input type="email" id="email" wire:model.defer="email" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan email">
          @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Website --}}
        <div class="mb-4">
          <label for="website" class="block text-sm font-medium text-gray-900 mb-1">Website</label>
          <input type="text" id="website" wire:model.defer="website" class="w-full rounded-lg border border-gray-300 p-3 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Masukkan website">
          @error('website') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="flex justify-end space-x-3 pt-4">
          <button wire:click.prevent="closeModal" type="button" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition duration-200">Batal</button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition duration-200">Simpan</button>
        </div>
        
      </form>
    </div>
  </div>
</div>
