<?php

namespace Modules\Content\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Content\Entities\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->title;
        return [
            'image' =>Str::slug($title).'.jpg',
            'title' =>$title,
            'content' =>$this->faker->text,
        ];
    }
}

