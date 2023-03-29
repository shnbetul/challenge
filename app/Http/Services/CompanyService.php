<?php

namespace App\Http\Services;

use App\DataTransferObjects\CompanyData;
use App\DataTransferObjects\PackageData;
use App\Enums\CompanyStatus;
use App\Models\Companies;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyService
{

    public function create(CompanyData $companyData)
    {
        if (!$this->createValidator($companyData)->fails()) {

            try {

                $company = new Company($companyData->toArray());
                $company->password = Hash::make($companyData->password);
                $company->status = CompanyStatus::PASSIVE;
                $company->name = $companyData->name;
                $company->lastname = $companyData->lastname;
                $company->company_name = $companyData->companyName;
                $company->email = $companyData->email;
                $company->site_url = $companyData->siteUrl;

                $company->save();

                if (!($company->save())) {
                    return false;
                }

                $company->token = $company->createToken("API TOKEN")->plainTextToken;
                $company->save();

                $companyData->token = $company->token;
                $companyData->id = $company->id;
                $companyData->status = $company->status;

                return  $companyData;
            } catch (\Throwable $th) {

                Log::error('Validator error, CompanyRegisterService', [
                    'exception' => $th
                ]);
                throw $th;
            }
        }
        return  $this->createValidator($companyData)->messages();
    }
    public function createValidator(CompanyData $companyData)
    {
        return Validator::make(
            $companyData->toArray(),
            [
                'name' => 'required',
                'lastname' => 'required',
                'companyName' => 'required',
                'email' => 'required|email',
                'siteUrl' => 'required',
                'password' => 'required',
                'status' => 'nullable',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function login(CompanyData $companyData)
    {

        if (!$this->loginValidator($companyData)->fails()) {

            try {
                $company = Company::where('email', $companyData->email)->first();
                
                if ($companyData->email === $company->email && Hash::check($companyData->password, $company->password)) {
                    
                    $companyData = new CompanyData(
                        id: $company->id,
                        name: $company->name,
                        lastname: $company->lastname,
                        companyName: $company->company_name,
                        email: $company->email,
                        siteUrl: $company->site_url,
                        password: $company->password,
                        status: $company->status,
                        token: $company->token
                    );
                    return $companyData;
                }else{
                    return false;
                }
                
            } catch (\Throwable $th) {

                Log::error('Validator error, CompanyLoginService', [
                    'exception' => $th
                ]);
                throw $th;
            }
        }
        return  $this->loginValidator($companyData)->messages();
    }

    public function loginValidator(CompanyData $companyData)
    {
        return Validator::make(
            $companyData->toArray(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function updateValidator(CompanyData $companyData)
    {
        return Validator::make(
            $companyData->toArray(),
            [
                'email' => 'required|email',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function update(CompanyData $companyData)
    {


        if (!$this->updateValidator($companyData)->fails()) {

            try {
                $company = Company::where('email', $companyData->email)->first();
                $company->status=CompanyStatus::ACTIVE;
                $company->save();
               
                $companyData = new CompanyData(
                    id: $company->id,
                    name: $company->name,
                    lastname: $company->lastname,
                    companyName: $company->company_name,
                    email: $company->email,
                    siteUrl: $company->site_url,
                    password: $company->password,
                    status: $company->status,
                    token: $company->token
                );

                return $companyData;
            } catch (\Throwable $th) {
                
                Log::error('Validator error, CompanyLoginService', [
                    'exception' => $th
                ]);
                throw $th;
            }
        }
        return  $this->updateValidator($companyData)->messages();
    }

    public function check(CompanyData $companyData)
    {

        if (!$this->checkValidator($companyData)->fails()) {

            try {

                $findCompany = Company::where('token', $companyData->token)->first();

                if (isset($findCompany)) {
                    $findCompanyPackage = DB::table('company_packages')
                        ->rightJoin('companies', 'companies.id', '=', 'company_packages.company_id')
                        ->rightJoin('packages', 'packages.id', '=', 'company_packages.package_id')
                        ->select('companies.company_name', 'companies.email', 'companies.site_url', 'companies.status', 'companies.token', 'packages.name', 'packages.status', 'packages.periyot', 'packages.price', 'company_packages.end_date', 'company_packages.start_date')
                        ->get()->first();

                    $companyData->packageData = new PackageData(
                        name: $findCompanyPackage->name,
                        status: $findCompanyPackage->status,
                        periyot: $findCompanyPackage->periyot,
                        price: $findCompanyPackage->price
                    );
                    $companyData->company_name = $findCompanyPackage->company_name;
                    $companyData->email = $findCompanyPackage->email;
                    $companyData->site_url = $findCompanyPackage->site_url;
                    $companyData->status = $findCompanyPackage->status;
                    $companyData->token = $findCompanyPackage->token;
                    return $companyData;
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
        return  $this->checkValidator($companyData)->messages();
    }

    public function checkValidator(CompanyData $companyData)
    {
        return Validator::make(
            $companyData->toArray(),
            [
                'token' => 'required',
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }
}
