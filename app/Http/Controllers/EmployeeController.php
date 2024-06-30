<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
    // Menampilkan daftar karyawan dan kehadiran mereka
    public function index()
    {
        $employees = Employee::with('attendances')->get();
        return view('employees.index', compact('employees'));
    }

    // Menambahkan karyawan baru
    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees',
            'password' => 'required|min:6',
            'position' => 'required',
        ]);

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'position' => $request->position,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }
    public function checkIn(Request $request)
    {
        // Validasi request
        $request->validate([
            'photo' => 'required|image',
            'latitude' => 'required',
            'longitude' => 'required',
            'status' => 'required',
            'alasan' => ($request->status != 'hadir') ? 'required|string' : 'nullable|string',
        ]);
    
        // Ambil waktu check-in dan atur timezone jika perlu
        $checkInTime = now()->toDateTimeString(); // Format datetime MySQL
    
        // Simpan foto ke dalam direktori
        $photoPath = $request->file('photo')->store('attendance_photos', 'public');
    
        // Atur kolom kehadiran berdasarkan waktu check-in
        $checkInTimeParsed = now()->format('H:i'); // Ambil hanya jam dan menit
        $batasWaktu = '14:30'; // Jam batas waktu
    
        $kehadiran = $checkInTimeParsed > $batasWaktu ? 'telat' : 'tepat waktu';
    
        try {
            // Simpan data absensi ke database
            DB::table('attendances')->insert([
                'employee_id' => Auth::id(),
                'check_in' => $checkInTime, // Pastikan format datetime sudah benar
                'photo_path' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'alasan' => $request->alasan,
                'status' => $request->status,
                'kehadiran' => $kehadiran,
                'updated_at' => now(),
                'created_at' => now(),
            ]);

            // Alert::success('Terimakasih sudah Absen' . $attendance->name);
            return redirect()->route('attendance.index')->with('success', 'Check-in successful.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
