<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
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
            'sender_id' => $user1->id,
            'receiver_id' => $this->faker->unique()->randomElement(User::whereNotIn('id', [$user1->id])->pluck('id')),
            'message' => $this->faker->text(),
            'status' => $this->faker->randomElement(array_values(config('choices.message_status'))),
        ];
    }
}
