<?php

namespace Database\Seeders;

use App\Models\Allow;
use Illuminate\Database\Seeder;

class AdminEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Allow::create([
            'email' => config('auth.defaults.admin_email')
        ]);
    }
}
