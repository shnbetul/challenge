<?php

namespace Database\Factories;

use App\Enums\packagePeriod;
use App\Enums\PackageStatus;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition()
    {
        $name = $this->faker->unique()->text(15);
        return [

            'name' => $name,
            'status' => PackageStatus::randomValue(),
            'periyot' => packagePeriod::randomValue(),
            'price' => $this->faker->randomDigitNotNull(),
        ];
    }
}
