<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|string|max:20',
            'time_start' => 'required|string|max:10',
            'time_end' => 'required|string|max:10',
            'course' => 'required|string|max:255',
            'lecturer' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        Schedule::create($validated);
        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'day' => 'required|string|max:20',
            'time_start' => 'required|string|max:10',
            'time_end' => 'required|string|max:10',
            'course' => 'required|string|max:255',
            'lecturer' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);
        $validated['sort_order'] = $validated['sort_order'] ?? $schedule->sort_order;
        $schedule->update($validated);
        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diupdate.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
