<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            "name"=>"Administrator",
            "slug"=>"administrator"
        ]);

        Role::create([
            "name"=>"Chef",
            "slug"=>"chef"
        ]);

        Role::create([
            "name"=>"Customer",
            "slug"=>"customer"
        ]);
    }
}
