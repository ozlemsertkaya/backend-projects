<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{

    // Çalışanları departman bilgileriyle birlikte listeleme
    public function index(): JsonResponse
    {
        $employees = Employee::with('department')->paginate(10);
        return response()->json($employees);
    }
    // Yeni Çalışan Ekleme + Validation
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id', // Girilen departman veritabanında var mı?
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:employees,email', // E-posta benzersiz olmalı
        ]);
        $employee = Employee::create($validated);

        return response()->json($employee->load('department'), 201);
    }
    // YÖNERGE MADDELERİ: Çalışanın departmanı VE dahil olduğu projeleri getirme
    public function show(int $id): JsonResponse
    {
        // Çalışanı çekerken hem departmanını hem de belongsToMany ile bağlı olduğu projeleri alıyoruz
        $employee = Employee::with(['department', 'projects'])->findOrFail($id);

        return response()->json($employee);
    }
    //Çalışan Güncelleme
    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'department_id' => 'sometimes|required|exists:departments,id',
            'first_name'    => 'sometimes|required|string|max:100',
            'last_name'     => 'sometimes|required|string|max:100',
            'email'         => 'sometimes|required|email|unique:employees,email,' . $employee->id,
        ]);

        $employee->update($validated);

        return response()->json($employee->fresh('department'));
    }

    // Çalışan Silme
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();
        return response()->json(['message' => 'Çalışan başarıyla silindi.']);
    }
}
