<?php

namespace Database\Factories;

use App\Enums\CompanyStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;
    public function definition()
    {
        $email = $this->faker->unique()->email();
        return [

            'name' => $this->faker->name(),
            'lastname' => $this->faker->lastName(),
            'company_name' => $this->faker->company(),
            'email' => $email,
            'site_url' => $this->faker->url(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'status' => CompanyStatus::randomValue(),
            'token' => Hash::make('$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
        ];
    }
}
