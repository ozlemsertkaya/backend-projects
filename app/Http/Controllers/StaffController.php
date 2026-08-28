<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Staff::all(),

        ], 200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'department' => 'required|string|max:255',
            'salary' => 'required|numeric',
        ]);

        $staff = Staff::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Personel başarıyla oluşturuldu.',
            'data' => $staff
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Personel bulunamadı.'], 404);
        }

        return response()->json(['success' => true, 'data' => $staff], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Personel bulunamadı.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:staff,email,' . $id,
            'department' => 'sometimes|required|string|max:255',
            'salary' => 'sometimes|required|numeric',
        ]);

        $staff->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Personel güncellendi.',
            'data' => $staff
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Personel bulunamadı.'], 404);
        }

        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Personel silindi.'
        ], 200);
    }
}
