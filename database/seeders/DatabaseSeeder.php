<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Asset;
use App\Models\Manufacturer;
use App\Models\Category;
use App\Models\Location;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(UserSeeder::class);

         // Create 10 more random manufacturers
         Manufacturer::factory(10)->create();

         // Create 10 more random categories
         Category::factory(10)->create();
 
         // Create 10 more random locations
         Location::factory(10)->create();
 
         // Create 100 random assets
         // These assets will randomly pick existing categories, locations, manufacturers, and users
         // (including the specific ones from the UserSeeder and the random ones from UserFactory)
         Asset::factory(100)->create();
    }
}
