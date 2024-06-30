<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $attendances = Attendance::with('employee')->get();
        return view('admin.dashboard', compact('attendances'));
    }

    public function confirmAttendance(Request $request)
    {
        $attendance = Attendance::find($request->attendance_id);
        $attendance->status = $request->status;
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Attendance confirmed.');
    }

    public function showAllAttendance()
    {
        $attendances = Attendance::with('employee')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('attendances'));
    }

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
        return view('admin.dashboard', compact('attendances'));
    }

    public function izin()
    {
        $attendances = Attendance::where('status', 'izin')->get();
        return view('admin.izin', compact('attendances'));
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Absen berhasil dihapus');
    }
    
}
