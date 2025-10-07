<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Menampilkan daftar semua karyawan
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    // Menampilkan form untuk menambah karyawan baru
    public function create()
    {
        return view('employees.create');
    }

    // Menyimpan data karyawan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'department' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }
    
    // Menampilkan data karyawan untuk diedit
    public function show(Employee $employee)
    {
        // Biasanya tidak digunakan di CRUD standar, kita pakai edit saja.
        return redirect()->route('employees.edit', $employee);
    }

    // Menampilkan form untuk mengedit data karyawan
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Mengupdate data karyawan
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,' . $employee->id,
            'department' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // Menghapus data karyawan
    public function destroy(Employee $employee)
    {
        // Tambahan: Di masa depan, mungkin perlu logika untuk menonaktifkan kartu terkait
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}