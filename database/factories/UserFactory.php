<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // return [
        //     'name' => $this->faker->name,
        //     'no_identitas' => $this->faker->integer,
        //     'email' => $this->faker->unique()->safeEmail,
        //     'password' => Hash::make('123'), // Password yang di-hash
        //     'role' => 'user', // Role default, bisa diubah sesuai kebutuhan
        // ];
    }
}
