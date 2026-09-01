<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    // Tüm şirketleri profilleriyle birlikte listeleme
    public function index(): JsonResponse
    {
        // with Şirket profillerini tek sorguda yanına ekler (N+1 önlenir)
        $companies = Company::with('profile')->paginate(10);
        return response()->json($companies);
    }

    // Şirket ve Detay Profilini Birlikte Kaydetme (DB Transaction)
    public function store(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'website' => 'nullable|url',
            'phone'   => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $company = DB::transaction(function () use ($validated) {
            $company = Company::create([
                'name' => $validated['name'],
            ]);




            // hasOne ilişkisi üzerinden otomatik company_id atayarak profil kaydı
            $company->profile()->create([
                'website' => $validated['website'] ?? null,
                'phone'   => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            return $company->load('profile');
        });

        return response()->json($company, 201);
    }
    // YÖNERGE MADDESİ: Bir şirketin departmanlarını ve departmandaki çalışanlarını getirme
    public function show(int $id): JsonResponse
    {
        // departments.employees -> Şirket -> Departmanlar -> O departmandaki Çalışanlar zinciri
        $company = Company::with(['profile', 'departments.employees'])->findOrFail($id);

        return response()->json($company);
    }
    //Şirket Güncelleme
    public function update(Request $request, Company $company): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company);
    }
    // Şirket Silme (Cascade sayesinde profili ve departmanları da otomatik silinir)
    public function destroy(Company $company): JsonResponse
    {
        $company->delete();
        return response()->json(['message' => 'Şirket ve bağlı tüm veriler başarıyla silindi.']);
    }
}
