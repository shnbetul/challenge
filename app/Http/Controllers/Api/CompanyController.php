<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\CompanyData;
use App\Enums\Error;
use App\Http\Controllers\Controller;
use App\Http\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{

    public function createCompany(CompanyService $CompanyService, Request $request)
    {
        $response = $CompanyService->create(new CompanyData(...$request->toArray()));

        try {

            if ($response) {

                return [
                    'companyId' => $response->id,
                    'status' => $response->status,
                    'token' => $response->token
                ];
            }

            return $response;
        } catch (\Throwable $th) {

            Log::error(Error::USER_NOT_CREATE, [
                'exception' => $th
            ]);

            return [
                'message' => 'user is not create',
                'code' => 5001
            ];
        }
    }

    public function loginCompany(CompanyService $companyService, Request $request)
    {
        $response = $companyService->login(new CompanyData(...$request->toArray()));
        
        try {

            if ($response) {
                
            
                $companyStatus = $companyService->update(new CompanyData(...$request->toArray()));
               
                $response->status = $companyStatus->status;
                
                return $response->toArray();
            }
            return $response;
        } catch (\Throwable $th) {

            Log::error(Error::NOT_MATCH_EMAIL_OR_PASSWORD, [
                'exception' => $th
            ]);

            return [
                'message' => 'email or password is not match',
                'code' => 401
            ];
        }
    }


    public function check(CompanyService $companyService, Request $request)
    {
        try {
            $response = $companyService->check(new CompanyData(...$request->toArray()));

            if ($response) {
                return $response->toArray();
            } else {
                return [
                    'message' => 'Company is not find',
                    'code' => 5001
                ];
            }
        } catch (\Throwable $th) {
            Log::error(Error::COMPANY_PACKAGE_NOT_ASSIGN, [
                'exception' => $th
            ]);
            return [
                'message' => 'Company and Package are not find',
                'code' => 5001
            ];
        }
    }
}
