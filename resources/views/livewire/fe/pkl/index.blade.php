<div class="py-24">
  {{-- Card --}}
  <div class="mx-auto  rounded-lg shadow-lg overflow-hidden px-6 py-6">

    {{-- Tampilan Pesan --}}
    <div>
      @if (session()->has('error'))
          <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
              class="p-4 bg-red-500 text-white rounded-md mb-4">
              {{ session('error') }}
          </div>
      @endif

      @if (session()->has('success'))
          <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
              class="p-4 bg-green-500 text-white rounded-md mb-4">
              {{ session('success') }}
          </div>
      @endif
    </div>
    {{-- ./Tampilan Pesan --}}

    {{-- Judul --}}
    <div class="w-full bg-gray-200 p-4 text-center text-2xl font-bold text-gray-700">
      Siswa PKL
    </div>
    {{-- ./Judul --}}
    
    {{-- Form Entry dan Searching --}}
    <div class="mx-auto flex items-center justify-between  p-6 rounded-lg shadow-md mb-6">

        <!-- Tombol Create Lapor PKL di kiri -->
        <button wire:click="create()" class="bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 transition duration-200">
            Create Lapor PKL
        </button>

        {{-- Cek apakah menampilkan halaman modal --}}
        @if($isOpen)
            @include('livewire.fe.pkl.create')
        @endif
        {{-- ./Cek apakah menampilkan halaman modal --}}

        {{-- Form Searching --}}
        <input wire:model.live="search" type="text" placeholder="Search ..." class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        {{-- ./Form Searching --}}
    </div>
    {{-- ./Form Entry dan Searching --}}

    {{-- Table PKL --}}
    <div class="overflow-x-auto">
      <table class="min-w-full  border border-gray-200">
        <thead>
          <tr>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">No</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Nama Siswa</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Industri</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Bidang Usaha</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Mulai</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Selesai</th>
            <th class="px-6 py-4 border-b border-gray-200 text-left text-gray-600">Durasi (Hari)</th>
          </tr>
        </thead>

        <tbody>
          @php
            use Carbon\Carbon;
            $no = 0;
          @endphp

          @foreach($pkls as $key => $pkl)
            @php
              $no++;
              $d1 = Carbon::parse($pkl->mulai);
              $d2 = Carbon::parse($pkl->selesai);
              $selisihHari = $d1->diffInDays($d2); // Selisih dalam hari
            @endphp
            
            <tr class="hover:bg-gray-100 transition duration-200">
              <td class="px-6 py-4 border-b border-gray-200">{{ $no }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $pkl->siswa->nama }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $pkl->industri->nama }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $pkl->industri->bidang_usaha }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $pkl->mulai }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $pkl->selesai }}</td>
              <td class="px-6 py-4 border-b border-gray-200">{{ $selisihHari }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{-- ./Table PKL --}}

    {{-- Pagination --}}
    <div class="p-4">
       {{ $pkls->links() }}
    </div>
    {{-- ./Pagination --}}
  </div>
  {{-- ./Card --}}
</div>