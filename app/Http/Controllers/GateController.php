<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index()
    {
        $gates = Gate::latest()->paginate(10);
        return view('gates.index', compact('gates'));
    }

    public function create()
    {
        return view('gates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'termno' => 'required|string|max:50|unique:gates,termno',
            'status' => 'required|in:active,inactive',
        ]);
        Gate::create($validated);
        return redirect()->route('gates.index')->with('success', 'Gate berhasil ditambahkan.');
    }

    public function edit(Gate $gate)
    {
        return view('gates.edit', compact('gate'));
    }

    public function update(Request $request, Gate $gate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'termno' => 'required|string|max:50|unique:gates,termno,' . $gate->id,
            'status' => 'required|in:active,inactive',
        ]);
        $gate->update($validated);
        return redirect()->route('gates.index')->with('success', 'Gate berhasil diperbarui.');
    }

    public function destroy(Gate $gate)
    {
        $gate->delete();
        return redirect()->route('gates.index')->with('success', 'Gate berhasil dihapus.');
    }
}