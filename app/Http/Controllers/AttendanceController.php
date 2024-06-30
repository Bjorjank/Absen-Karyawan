<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;  // Tambahkan ini
use Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Attendance::where('employee_id', $userId)->with('user');
        
     // Filter berdasarkan rentang waktu
     if ($request->has('filter')) {
        switch ($request->filter) {
            case 'daily':
                $query->whereDate('created_at', today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case '3months':
                $query->whereBetween('created_at', [now()->subMonths(3), now()]);
                break;
            case '6months':
                $query->whereBetween('created_at', [now()->subMonths(6), now()]);
                break;
            case 'all':
            default:
                // Tidak ada filter tambahan
                break;
        }
    }

        $attendances = $query->get(); // Ambil data dengan menggunakan $query yang sudah difilter
        return view('attendance.index', compact('attendances'));
    }
    

    public function checkIn(Request $request)
{
    $request->validate([
        'photo' => 'required|image',
        'latitude' => 'required', // Sesuaikan dengan aturan validasi Anda
        'longitude' => 'required',
        'status' => 'required|string',
    ]);

    $photoPath = $request->file('photo')->store('attendance_photos', 'public');

    Attendance::create([
        'employee_id' => Auth::id(), // Menggunakan Auth::id() untuk mendapatkan ID pengguna yang sedang login
        'check_in' => now(),
        'photo_path' => $photoPath,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        
        
        
    ]);

    return redirect()->route('attendance.index')->with('success', 'Check-in successful.');
}


    public function checkOut(Request $request)
    {
        $attendance = Attendance::where('employee_id', Auth::user()->id)->latest()->first();
        $attendance->update([
            'check_out' => now(),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Check-out successful.');
    }
    
    public function filter(Request $request)
    {
        $query = Attendance::query();
    
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            $query->whereBetween('check_in', [Carbon::parse($dates[0]), Carbon::parse($dates[1])]);
        }
    
        $attendances = $query->get();
    
        return view('admin.dashboard', compact('attendances'));
    }
    
    public function downloadPDF(Request $request)
    {
        $query = Attendance::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('check_in', [$request->start_date, $request->end_date]);
        }

        $attendances = $query->get();

        $pdf = PDF::loadView('attendance.pdf', compact('attendances'));
        return $pdf->download('attendance_report.pdf');
    }

    public function destroy($id)
{
    $attendance = Attendance::find($id);
    
    if ($attendance) {
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully.');
    } else {
        return redirect()->route('attendance.index')->with('error', 'Attendance record not found.');
    }
}

}
