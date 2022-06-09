<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $name = $this->faker->name,
            'slug' => Str::slug($name),
            'price' => rand(100,10000),
            'type' => ['Simple', 'Grouped', 'Variable', 'Gift'][rand(0,3)],
            'categories' => ['Electronics', 'Books', 'Games', 'Garden'][rand(0,3)],
        ];
    }
}
