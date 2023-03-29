<?php

namespace App\Jobs;

use App\Enums\CompanyPaymentStatus;
use App\Enums\CompanyStatus;
use App\Http\Services\CompanyPaymentService;
use App\Models\Company;
use App\Models\CompanyPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class Payment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public $data;
    public $tries = 3;
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function handle()
    {
        try {
            $receiptCheck = new CompanyPaymentService();

            if ($receiptCheck->checkReceiptlastNumber($this->data->receipt)) {
                
                CompanyPayment::where('company_id', $this->data->company_id)->update(['payment_status' => CompanyPaymentStatus::PAID]);
                Company::where('id', $this->data->company_id)->update(['status' => CompanyStatus::ACTIVE]);

                Log::info($this->data->company_id . 'Ödeme alındı şeklinde güncellendi');
                return $this->data->company_id . "Ödeme alındı şeklinde güncellendi";
            } else {
                CompanyPayment::where('company_id', $this->data->company_id)->update(['payment_status' => CompanyPaymentStatus::NOTPAID]);
                Company::where('id', $this->data->company_id)->update(['status' => CompanyStatus::PASSIVE]);

                Log::info($this->data->company_id . 'Ödeme alınmadı şeklinde güncellendi');

                return $this->data->company_id . "Ödeme alınmadı şeklinde güncellendi" ;
            }
           
        } catch (Throwable $th) {
            $this->failed($th);
            
        }
    }

    public function failed($th)
    {
        $this->release(86400);
        $th->getMessage();
    }
}
