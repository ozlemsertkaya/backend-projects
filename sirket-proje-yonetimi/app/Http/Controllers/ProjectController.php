<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        //with ile N+1 i engelliyoruz.Projenin çalışanlarını,tasklarını ve taglarını tek seferde çekiyoruz.
        $query = Project::with(['employees:id,first_name,last_name', 'tasks', 'tags:id,name']);




        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%") //Başı ve sonu ne olursa olsun, kullanıcının girdiği kelime başlığın herhangi bir yerinde geçiyorsa o projeyi bulur.
                    //içinde geçsin yetr.
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($tag = $request->input('tag')) {
            $query->whereHas('tags', function ($q) use ($tag) {
                //bağlı oldğ. diğer tablodaki veriye göre ana tabloyu filtreler.
                $q->where('name', $tag);
            });
        }
        $projects = $query->latest()->paginate(10);
        //henüz veritabanına gönderilmmiş hazırlanmakta olan sql taslağı.
        return response()->json($projects);
        //json formatıan çevirip,http yanıtı olrk döndürür.
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'employee_ids'   => 'required|array',
            'employee_ids.*' => 'exists:employees,id', // Gelen her ID employees tablosunda var mı?
            'tag_ids'        => 'nullable|array',
            'tag_ids.*'      => 'exists:tags,id',
        ]);

        //transaption proje oluşturulurken veya pivot tabloya
        // kayıt atılırken hata çıkarsa tüm veritabnı işlmlerini iptal eder.
        $project = DB::transaction(function () use ($validated) {
            $project = Project::create([
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
            ]);
            $project->employees()->attach($validated['employee_ids']);

            if (!empty($validated['tag_ids'])) {
                $project->tags()->attach($validated['tag_ids']);
            }

            return $project->load(['employees', 'tags']);
            //elinde var olan modelin yanına ilişkili veriyi sonradan ekler.
        });

        return response()->json($project, 201);
    }


    public function show(int $id): JsonResponse
    {
        //projedeki çalışanları ve çalışanların departlarını tek sefrde getirir.
        $project = Project::with(['employees.department', 'tasks', 'tags'])->findOrFail($id);

        return response()->json($project);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'nullable|string',
            'employee_ids'   => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
            'tag_ids'        => 'nullable|array',
            'tag_ids.*'      => 'exists:tags,id',
        ]);

        DB::transaction(function () use ($project, $validated) {
            $project->update($validated);
            if (isset($validated['employee_ids'])) {
                $project->employees()->sync($validated['employee_ids']);
            }

            if (isset($validated['tag_ids'])) {
                $project->tags()->sync($validated['tag_ids']);
            }
        });
        return response()->json($project->fresh(['employees', 'tags']));
        //modeli veritabanından sıfırdan,yeniden sorgulayıp çeker.
    }


    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Proje başarıyla silindi.'], 200);
    }
}
