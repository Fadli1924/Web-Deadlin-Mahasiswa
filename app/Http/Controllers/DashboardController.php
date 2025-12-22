<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
                    ->orderBy('deadline', 'asc')
                    ->get();

        // Get notifications for tasks with deadlines within 3 days
        $notifications = Task::where('user_id', auth()->id())
                            ->whereDate('deadline', '>', now())
                            ->whereDate('deadline', '<=', now()->addDays(3))
                            ->orderBy('deadline', 'asc')
                            ->get();

        return view('dashboard', compact('tasks', 'notifications'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        // Cek apakah user adalah pemilik task
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Cek apakah user adalah pemilik task
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('dashboard')->with('success', 'Tugas berhasil diperbarui!');
    }
}