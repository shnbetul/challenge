<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\Payment as CommandsPayment;
use App\DataTransferObjects\CompanyPaymentData;
use App\Enums\CompanyPaymentStatus;
use App\Enums\Error;
use App\Http\Controllers\Controller;
use App\Http\Services\CompanyPaymentService;
use App\Jobs\Payment;
use App\Models\CompanyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyPaymentController extends Controller
{
    public function create(CompanyPaymentService $companyPaymentService, Request $request)
    {
        try {

            $response = $companyPaymentService->create(new CompanyPaymentData(...$request->toArray()));
            $expireTime = $companyPaymentService->checkExpireDate($response->companyId);

            if ($expireTime) {
                if ($response) {
                    return $response->toArray();
                } else {
                    return [
                        'message' => 'Bir sorun oldu',
                        'code' => 5001
                    ];
                }
            } else {
             Payment::dispatch($response);
                return [
                    'message' => 'Bilemedim',
                    'code' => 5001
                ]  ;
               
            }
        } catch (\Throwable $th) {
            Log::error(Error::COMPANY_PAYMENT_NOT_COMPLETED, [
                'exception' => $th
            ]);
            return [
                'message' => 'ödeme de bir sorun oldu',
                'code' => 5001
            ];
        }
    }
}
