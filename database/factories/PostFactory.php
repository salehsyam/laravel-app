<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => implode(' ',$this->faker->words(5)),
            'content' => $this->faker->paragraph(100),
            'status' => ['draft','published','archived'][rand(0,2)],
            'published_at' => $this->faker->dateTime,
        ];
    }
}
