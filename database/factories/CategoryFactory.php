<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define common device categories you'd expect
        $deviceCategories = [
            'Laptop',
            'Desktop PC',
            'Monitor',
            'Printer',
            'Network Switch',
            'Router',
            'Server',
            'Mobile Phone',
            'Tablet',
            'Projector',
            'IP Camera',
            'Firewall',
            'UPS (Uninterruptible Power Supply)',
            'Storage Device',
            'Software License', 
            'Peripherals', 
        ];
        return [
            'name' => $this->faker->randomElement($deviceCategories),
            'description' => $this->faker->sentence()
        ];
    }
}
