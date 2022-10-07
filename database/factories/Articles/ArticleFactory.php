<?php

namespace Database\Factories\Articles;

use App\Models\Articles\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articles\Tag>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'     => $this->faker->sentence(4),
            'body'      => $this->faker->text,
            'slug'      => Str::slug($this->faker->sentence(4)),
            'topic_id'  => Topic::get()->random()->id
        ];
    }
}
