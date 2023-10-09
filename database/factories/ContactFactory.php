<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user1 = User::factory()->create();

        return [
            'contact_user1_id' => $user1->id,
            'contact_user2_id' => $this->faker->unique()->randomElement(User::whereNotIn('id', [$user1->id])->pluck('id')),
            'name' => $this->faker->name(),
        ];
    }
}
