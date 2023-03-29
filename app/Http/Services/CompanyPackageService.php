<?php

namespace App\Http\Services;

use App\DataTransferObjects\CompanyPackageData;
use App\Models\Company;
use App\Models\CompanyPackage;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyPackageService
{
    public function assign(CompanyPackageData $companyPackageData)
    {
        if (!$this->assignValidator($companyPackageData)->fails()) {
            try {

                $companyPackage = new CompanyPackage($companyPackageData->toArray());

                $company = Company::where('id', $companyPackageData->companyId)->first();
                $package = Package::where('id', $companyPackageData->packageId)->first();

                if (isset($company) && isset($package)) {
                    $packageService = new PackageService();
                    $companyPackage->company_id = $company->id;
                    $companyPackage->package_id = $package->id;
                    $companyPackage->start_date = Carbon::now();

                    $companyPackage->end_date = $packageService->calculateEndDate($package);

                    if ($companyPackage->end_date == false) {
                        return false;
                    }

                    $companyPackage->save();
                    if (!($companyPackage->save())) {
                        return false;
                    }
                    $companyPackageData->id = $companyPackage->id;
                    $companyPackageData->startDate = $companyPackage->start_date;
                    $companyPackageData->endDate = $companyPackage->end_date;

                    return $companyPackageData;
                } else {
                    return false;
                }
            } catch (\Throwable $th) {


                Log::error('Validator error, CompanyPackageService', [
                    'exception' => $th
                ]);
                throw $th;
            }
        }
        return  $this->assignValidator($companyPackageData)->messages();
    }

    public function assignValidator(CompanyPackageData $companyPackageData)
    {
        return Validator::make(
            $companyPackageData->toArray(),
            [
                'companyId' => 'required',
                'packageId' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }
}
