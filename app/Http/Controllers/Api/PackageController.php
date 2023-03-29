<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\PackageData;
use App\Enums\Error;
use App\Http\Controllers\Controller;
use App\Http\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{

    public function packageCreate(PackageService $packageService, Request $request)
    {

        try {
            $package = $packageService->create(new PackageData(...$request->toArray()));

            return $package->toArray();
        } catch (\Throwable $th) {
            Log::error(Error::PACKAGE_NOT_CREATE, [
                'exception' => $th
            ]);
            return [
                'message' => 'package is not create',
                'code' => 5001
            ];
        }
    }

    public function packageStatusUpdate(PackageService $packageService, Request $request)
    {
        try {
            $package = $packageService->updatePackageStatus(new PackageData(...$request->toArray()));


            return $package->toArray();
        } catch (\Throwable $th) {
            Log::error(Error::PACKAGE_NOT_UPDATE, [
                'exception' => $th
            ]);
            return [
                'message' => 'package status is not update',
                'code' => 202
            ];
        }
    }
    public function packagePeriodUpdate(PackageService $packageService, Request $request)
    {
        try {
            $package = $packageService->updatePackagePeriod(new PackageData(...$request->toArray()));


            return $package->toArray();
        } catch (\Throwable $th) {
            Log::error(Error::PACKAGE_NOT_UPDATE, [
                'exception' => $th
            ]);
            return [
                'message' => 'package period is not update',
                'code' => 202
            ];
        }
    }
}
