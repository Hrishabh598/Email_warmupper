<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class empty_emails extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("emails")->delete();
    }
}
