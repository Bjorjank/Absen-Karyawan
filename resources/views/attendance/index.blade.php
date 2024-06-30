<style>
    .kehadiran-telat {
        background-color: red;
    }

    .kehadiran-tepat-waktu {
        background-color: green;
    }
</style>

<!-- resources/views/attendances/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-center mb-4">Daftar Kehadiran</h1>
                    
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('attendance.index') }}" class="mb-6">
                        <select name="filter" class="border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Semua</option>
                            <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="3months" {{ request('filter') == '3months' ? 'selected' : '' }}>3 Bulan</option>
                            <option value="6months" {{ request('filter') == '6months' ? 'selected' : '' }}>6 Bulan</option>
                        </select>
                    </form>                    
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam absensi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latitude</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Longitude</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->check_in }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->alasan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($attendance->photo_path)
                                            <img src="{{ asset('storage/' . $attendance->photo_path) }}" alt="Photo" class="h-16 w-16 object-cover rounded-full">
                                        @else
                                            <span class="text-gray-400">No Photo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->latitude }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->longitude }}</td>
                                    <td id="kehadiran-{{ $attendance->id }}" class="px-6 py-4 whitespace-nowrap text-white text-xl">{{ $attendance->kehadiran }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let attendances = @json($attendances); // Ambil data kehadiran dari PHP Laravel
    
            // Function untuk mengubah kelas CSS berdasarkan kehadiran
            function updateBorderColor() {
                attendances.forEach(attendance => {
                    let kehadiranElement = document.getElementById(`kehadiran-${attendance.id}`);
    
                    if (kehadiranElement) {
                        if (attendance.kehadiran === 'telat') {
                            kehadiranElement.classList.add('kehadiran-telat'); // Ubah border jadi merah
                        } else {
                            kehadiranElement.classList.add('kehadiran-tepat-waktu'); // Ubah border jadi hijau
                        }
                    }
                });
            }
    
            // Panggil fungsi saat halaman dimuat
            updateBorderColor();
        });
    </script>
    
</x-app-layout>
