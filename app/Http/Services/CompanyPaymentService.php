<?php

namespace App\Http\Services;

use App\DataTransferObjects\CompanyPaymentData;
use App\Enums\CompanyPaymentStatus;
use App\Models\CompanyPackage;
use App\Models\CompanyPayment;
use App\Models\Package;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyPaymentService
{
    public function validator(CompanyPaymentData $companyPaymentData)
    {
        return Validator::make(
            $companyPaymentData->toArray(),
            [
                'companyId' => 'required',
                'packageId' => 'required',
                'packagePrice' => 'nullable',
                'paymentStatus' => 'nullable',
                'cardInfo' => 'required',
                'receipt' => 'required'
            ],
            [
                'required' => 'The :attribute field is required.',
            ]
        );
    }

    public function create(CompanyPaymentData $companyPaymentData)
    {

        if (!$this->validator($companyPaymentData)->fails()) {

            try {
                $companyPackage = CompanyPackage::where('company_id', $companyPaymentData->companyId)->first();

                $package = Package::where('id', $companyPaymentData->packageId)->first();

                $companyPayment = new CompanyPayment($companyPaymentData->toArray());

                if ($companyPaymentData->companyId == $companyPackage->company_id && $companyPaymentData->packageId == $companyPackage->package_id) {
                    
                    $companyPayment->receipt = $companyPaymentData->receipt;
                    
                    if ($this->checkReceiptlastNumber($companyPayment->receipt)) {
                        $companyPayment->payment_status = CompanyPaymentStatus::PAID;
                    } else {
                        $companyPayment->payment_status = CompanyPaymentStatus::NOTPAID;
                    }

                    $companyPayment->company_id = $companyPaymentData->companyId;
                    $companyPayment->package_id = $companyPaymentData->packageId;
                    $companyPayment->package_price = $package->price;
                    $companyPayment->card_info = $companyPaymentData->cardInfo;

                    $companyPayment->save();
                }
                return $companyPaymentData;
            } catch (\Throwable $th) {

                Log::error('Validator error, CompanyService', [
                    'exception' => $th
                ]);
                throw $th;
            }
        }
        return  $this->validator($companyPaymentData)->messages();
    }

    public function checkReceiptlastNumber(string $receipt)
    {
        $last = substr($receipt, -1);
        if (is_numeric($last) && $last % 2 != 0) {
            return false;
        }
        return true;
    }

    public function checkExpireDate(int $companyId)
    {
        $expireDate = CompanyPackage::where('company_id', $companyId)
            ->whereDate('end_date', '<', date('Y-m-d H:i:s'))
            ->first();
            if(isset($expireDate)){
                return $expireDate;
            }
            return false;
    }
}
