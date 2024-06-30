<?php
// app/Http/Controllers/PermissionController.php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'date' => 'required|date',
        ]);

        $permission = new Permission();
        $permission->reason = $request->reason;
        $permission->date = $request->date;
        $permission->employee_id = auth()->id(); // atau sesuai dengan cara Anda menyimpan ID karyawan
        $permission->status = 'pending'; // status default bisa 'pending'

        $permission->save();

        return redirect()->route('dashboard')->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function index()
    {
        $permissions = Permission::all(); // Misalnya, Anda bisa filter atau urutkan data izin di sini

        return view('admin.permissions.index', ['permissions' => $permissions]);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        // Validasi dan proses update status izin
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $permission->status = $request->status;
        $permission->save();

        return redirect()->route('admin.permissions.index')->with('success', 'Status izin berhasil diperbarui.');
    }
}
