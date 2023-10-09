<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** php artisan make:model Block -crRf

 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Block>
 */
class BlockFactory extends Factory
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
            'blocker_id' => $user1->id,
            'banned_id' => $this->faker->unique()->randomElement(User::whereNotIn('id', [$user1->id])->pluck('id')),
        ];
    }
}
