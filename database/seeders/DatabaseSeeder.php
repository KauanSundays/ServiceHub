<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        UserProfile::factory()->create([
            'user_id' => $admin->id,
            'position' => 'Administrator'
        ]);

        Company::factory(3)->create()->each(function ($company) use ($admin) {
            $projects = Project::factory(2)->create([
                'company_id' => $company->id
            ]);

            $projects->each(function ($project) use ($admin) {
                Ticket::factory(5)->create([
                    'project_id' => $project->id,
                    'user_id' => $admin->id
                ]);
            });
        });
    }
}
