<?php

namespace App\Http\Services;

use App\DataTransferObjects\PackageData;
use App\Enums\packagePeriod;
use App\Enums\PackageStatus;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class PackageService
{
    public function create(PackageData $packageData)
    {
        if (!$this->createValidator($packageData)->fails()) {

            try {

                $package = new Package($packageData->toArray());
                $package->status = PackageStatus::PASSIVE;
                $package->periyot = packagePeriod::NOT_ASSIGN;

                $package->save();

                if (!($package->save())) {
                    return false;
                }

                $packageData->id = $package->id;

                return $packageData;
            } catch (\Throwable $th) {

                Log::error('Validator error, PackageService', [
                    'exception' => $th
                ]);

                throw $th;
            }
        }

        return $this->createValidator($packageData)->messages();
    }

    public function createValidator(PackageData $packageData)
    {
        return  Validator::make(
            $packageData->toArray(),
            [
                'name' => 'required',
                'status' => 'nullable',
                'periyot' => 'nullable',
                'price' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function updateValidator(PackageData $packageData)
    {
        return  Validator::make(
            $packageData->toArray(),
            [
                'id' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }
    public function updatePackageStatus(PackageData $packageData)
    {
        if (!$this->updateValidator($packageData)->fails()) {

            try {
                $package = Package::where('id', $packageData->id)->first();
                $package->status = PackageStatus::ACTIVE;

                $package->save();

                $packageData->name = $package->name;
                $packageData->id = $package->id;
                $packageData->price = $package->price;
                $packageData->status = $package->status;
                $packageData->periyot = $package->periyot;

                return $packageData;
            } catch (\Throwable $th) {
                Log::error('Validator error, PackageService', [
                    'exception' => $th
                ]);

                throw $th;
            }
        }

        return $this->updateValidator($packageData)->messages();
    }

    public function calculateEndDate(Package $package)
    {

        if ($package->periyot == 0) {
            return false;
        }
        if ($package->periyot == 1) {

            return Carbon::now()->startOfDay(now())->addDays(30);
        }
        if ($package->periyot == 2) {
            return Carbon::now()->startOfDay(now())->addDays(365);
        }
    }

    public function updatePeriodValidator(PackageData $packageData)
    {
        return  Validator::make(
            $packageData->toArray(),
            [
                'periyot' => 'required',
                'id' => 'required',

            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }
    public function updatePackagePeriod(PackageData $packageData)
    {

        if (!$this->updatePeriodValidator($packageData)->fails()) {

            try {
                $package = Package::where('id', $packageData->id)->first();


                if ($packageData->periyot == 1) {

                    $package->periyot = PackagePeriod::MONTHLY;
                }
                if ($packageData->periyot == 2) {
                    $package->periyot = PackagePeriod::YEARLY;
                }


                $package->save();

                $packageData->name = $package->name;
                $packageData->id = $package->id;
                $packageData->price = $package->price;
                $packageData->status = $package->status;
                $packageData->periyot = $package->periyot;

                return $packageData;
            } catch (\Throwable $th) {
                Log::error('Validator error, PackageService', [
                    'exception' => $th
                ]);

                throw $th;
            }
        }

        return $this->updatePeriodValidator($packageData)->messages();
    }
}
