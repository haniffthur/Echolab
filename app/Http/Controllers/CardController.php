<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Employee;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with('cardable')->latest()->paginate(10);
        return view('cards.index', compact('cards'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        return view('cards.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cardno' => 'required|string|max:50|unique:cards,cardno',
            'type' => 'required|in:employee,guest',
            // 'cardable_id' menjadi wajib hanya jika tipenya 'employee'
            'cardable_id' => 'required_if:type,employee|nullable|exists:employees,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validated['type'] === 'employee') {
            $validated['cardable_type'] = Employee::class;
        } else {
            // Jika tipe 'guest', pastikan pemiliknya NULL
            $validated['cardable_id'] = null;
            $validated['cardable_type'] = null;
        }

        Card::create($validated);

        return redirect()->route('cards.index')->with('success', 'Kartu berhasil ditambahkan.');
    }

    public function edit(Card $card)
    {
        $employees = Employee::where('is_active', true)->orderBy('name')->get();
        return view('cards.edit', compact('card', 'employees'));
    }

    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'cardno' => 'required|string|max:50|unique:cards,cardno,' . $card->id,
            'type' => 'required|in:employee,guest',
            'cardable_id' => 'required_if:type,employee|nullable|exists:employees,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validated['type'] === 'employee') {
            $validated['cardable_type'] = Employee::class;
        } else {
            $validated['cardable_id'] = null;
            $validated['cardable_type'] = null;
        }
        
        $card->update($validated);

        return redirect()->route('cards.index')->with('success', 'Data kartu berhasil diperbarui.');
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->route('cards.index')->with('success', 'Data kartu berhasil dihapus.');
    }
}