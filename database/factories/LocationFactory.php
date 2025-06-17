<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $officeLocations = [
            'Main Office - Floor 1',
            'Annex Building - IT Department',
            'Warehouse - Storage Area A',
            'Remote Office - Manila',
            'Branch Office - Cebu',
            'Data Center - Server Room 1',
            'Conference Room 3',
            'Training Room',
            'Employee Lounge',
            'Admin Building - Level 2',
            'Research Lab - Wing B',
            'Customer Service Center',
            'Executive Suite',
            'Server Room - Ground Floor',
            'Networking Hub - 3rd Floor',
        ];

        return [
            'name' => $this->faker->randomElement($officeLocations),
            'address' => $this->faker->address(),
        ];
    }
}
