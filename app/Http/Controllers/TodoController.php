<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;                   

class TodoController extends Controller
{
    // 1. Menampilkan Semua Tugas Milik User yang Login
    public function index()
    {
        $todos = Auth::user()->todos()->orderBy('deadline', 'asc')->get();
        $pageTitle = "All Tasks";
        return view('todo', compact('todos', 'pageTitle'));
    }

    // 2. Menampilkan Tugas Hari Ini Milik User yang Login Saja
    public function today()
    {
        $todayDate = Carbon::today()->toDateString();

        // Filter: Hanya mengambil tugas milik user ini yang deadlinenya hari ini
        $todos = Auth::user()->todos()
            ->whereDate('deadline', $todayDate)
            ->orderBy('created_at', 'desc')
            ->get();

        $pageTitle = "Today's Task";

        return view('todo', compact('todos', 'pageTitle'));
    }

    // 3. Menyimpan tugas baru terikat dengan User ID yang login
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        // Menyisipkan user_id otomatis dari user yang sedang aktif
        Todo::create([
            'user_id' => Auth::id(),
            'task_name' => $request->task_name,
            'deadline' => $request->deadline,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // 4. Update status Checklist (Hanya untuk tugas milik sendiri)
    public function check($id)
    {
        // Mengamankan data: cari berdasarkan user_id login agar orang lain tidak bisa tebak ID via AJAX
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        
        $todo->update([
            'is_completed' => !$todo->is_completed
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    // 5. Menyimpan perubahan Edit Tugas (Hanya untuk tugas milik sendiri)
    public function update(Request $request, $id)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        // Pastikan hanya pemilik tugas yang bisa memperbarui
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        
        $todo->update([
            'task_name' => $request->task_name,
            'deadline' => $request->deadline,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    // 6. Menghapus tugas (Hanya untuk tugas milik sendiri)
    public function destroy($id)
    {
        // Pastikan hanya pemilik tugas yang bisa menghapus
        $todo = Todo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}