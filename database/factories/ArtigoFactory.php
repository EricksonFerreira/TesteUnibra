<?php

namespace Database\Factories;

use App\Models\Artigo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtigoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Artigo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->sentence,
            'descricao' => $this->faker->paragraph,
            'concluido' => rand(0,1),
            'created_by' => rand(1,10),
        ];
    }
}
