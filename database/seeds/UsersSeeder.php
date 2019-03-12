<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 3;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('tb_users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'gender'=> $faker->randomElement(['male', 'female']),
                'password'=> bcrypt('123'),
                'description'=>$faker->word,
                'image'=>$faker->imageUrl($width = 640, $height = 480),
            ]);
        }
    }
}
