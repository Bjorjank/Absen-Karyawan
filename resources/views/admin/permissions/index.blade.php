<!-- resources/views/admin/permissions/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin - Permohonan Izin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Daftar Permohonan Izin</h3>
                    @foreach ($permissions as $permission)
                        <div class="mb-4 p-3 border rounded">
                            <p><strong>Karyawan:</strong> {{ $permission->user->name }}</p>
                            <p><strong>Alasan:</strong> {{ $permission->reason }}</p>
                            <p><strong>Tanggal:</strong> {{ $permission->date }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($permission->status) }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($permission->attendance->kehadiran) }}</p>

                            @if ($permission->status === 'pending')
                                <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Update Status:</label>
                                        <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 focus:outline-none focus:shadow-outline">
                                        Update
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
