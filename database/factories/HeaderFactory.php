<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Header>
 */
class HeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $sentence = strtolower(fake()->sentence(6));
        return [
            'header' => $sentence,
            'slug' => str_replace(' ', '-', $sentence)
        ];
    }
}
