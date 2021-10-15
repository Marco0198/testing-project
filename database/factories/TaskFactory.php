<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return ['title'=>$this->faker->sentence(5)
        ,'description'=>$this->faker->sentence(50)
            , 'attach'=>$this->faker->imageUrl
            , 'status'=>$this->faker->word
        ];
    }
}
