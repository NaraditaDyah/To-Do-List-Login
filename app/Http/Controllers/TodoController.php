<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // 1. Menampilkan semua tugas
    public function index()
    {
        $todos = Todo::orderBy('deadline', 'asc')->get();
        return view('todo', compact('todos'));
    }

    // 2. Menyimpan tugas baru
    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        Todo::create($request->all());

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan!');
    }

    // 3. Update status Checklist (Selesai/Belum)
    public function check($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->update([
            'is_completed' => !$todo->is_completed
        ]);

        return redirect()->back();
    }

    // 4. Menyimpan perubahan Edit Tugas
    public function update(Request $request, $id)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update($request->all());

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    // 5. Menghapus tugas
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}