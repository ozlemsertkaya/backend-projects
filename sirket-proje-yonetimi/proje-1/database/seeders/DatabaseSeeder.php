<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyProfile;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //önce bağımsız olan etiketleri üretiyoruz.
        $tags = Tag::factory(5)->create();
        //3 adet şirket üretiyoruz.
        Company::factory(3)->create()->each(function ($company) use ($tags) {
            //şirkete bir adet profil bağlıyoruz.
            CompanyProfile::factory()->create([
                'company_id' => $company->id,
            ]);

            //şirkete 3 adet departman bağlıyoruz.
            $departments = Department::factory(3)->create([
                'company_id' => $company->id,
            ]);

            $departments->each(function ($department) use ($tags) {
                // Departmana 5 çalışan bağlıyoruz (hasMany)
                $employees = Employee::factory(5)->create([
                    'department_id' => $department->id,
                ]);

                // 2 adet Proje oluşturuyoruz
                $projects = Project::factory(2)->create();
                $projects->each(function ($project) use ($employees, $tags) {
                    // Projeye çalışanları bağlıyoruz (Many-to-Many / Pivot tabloya yazar)
                    $project->employees()->attach($employees->random(2));

                    // Projeye etiketleri bağlıyoruz (Many-to-Many / Pivot tabloya yazar)
                    $project->tags()->attach($tags->random(2));

                    // Projeye ait 3 görev ekliyoruz (hasMany)
                    Task::factory(3)->create([
                        'project_id' => $project->id,
                    ]);
                });
            });
        });
    }
}
