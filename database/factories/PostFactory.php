<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titleSk = rtrim($this->faker->unique()->sentence(4), '.');
        $titleEn = rtrim($this->faker->unique()->sentence(4), '.');

        return [
            'title' => [
                'sk' => $titleSk,
                'en' => $titleEn,
            ],
            'excerpt' => [
                'sk' => $this->faker->sentence(12),
                'en' => $this->faker->sentence(12),
            ],
            'content' => [
                'sk' => '<p>'.$this->faker->paragraph(5).'</p>',
                'en' => '<p>'.$this->faker->paragraph(5).'</p>',
            ],
            'slug' => [
                'sk' => Str::slug($titleSk).'-'.$this->faker->unique()->numberBetween(1, 999999),
                'en' => Str::slug($titleEn).'-'.$this->faker->unique()->numberBetween(1, 999999),
            ],
            'likes' => $this->faker->numberBetween(0, 250),
            'dislikes' => $this->faker->numberBetween(0, 50),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_draft' => false,
            'locale_scope' => null,
        ];
    }

    /**
     * Indicate that the post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_draft' => true,
        ]);
    }
}
