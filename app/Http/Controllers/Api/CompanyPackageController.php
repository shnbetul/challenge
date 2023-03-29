<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\CompanyPackageData;
use App\Enums\Error;
use App\Http\Controllers\Controller;
use App\Http\Services\CompanyPackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyPackageController extends Controller
{
    public function assign(CompanyPackageService $companyPackageService, Request $request)
    {
        try {
            $response = $companyPackageService->assign(new CompanyPackageData(...$request->toArray()));

            if ($response) {
                return $response->toArray();
            } else {
                return [
                    'message' => 'Company or Package is not find',
                    'code' => 5001
                ];
            }
        } catch (\Throwable $th) {
            Log::error(Error::COMPANY_PACKAGE_NOT_ASSIGN, [
                'exception' => $th
            ]);
            return [
                'message' => 'Company Package is not assign',
                'code' => 5001
            ];
        }
    }

}
