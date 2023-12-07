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


        $user1 = User::inRandomOrder()->first();
        $user2 = User::where('id', '!=', $user1->id)->inRandomOrder()->first();

        // Check if users exist, if not, create dummy users
        if (!$user1) {
            $user1 = User::factory()->create();
        }
        if (!$user2) {
            $user2 = User::factory()->create();
        }

        return [
            'contact_user1_id' => $user1->id,
            'contact_user2_id' => $user2->id,
            'name' => $this->faker->name(),
        ];
    }
}
