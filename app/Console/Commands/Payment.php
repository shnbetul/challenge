<?php

namespace App\Console\Commands;

use App\Jobs\Payment as JobsPayment;
use App\Models\CompanyPayment;
use Illuminate\Console\Command;

class Payment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payment = CompanyPayment::select([
            'company_payments.id',
            'company_payments.receipt',
            'company_payments.company_id',
            'company_payments.package_id',
            'company_payments.payment_status',
            'companies.status',
            'company_packages.start_date',
            'company_packages.end_date'

        ])->join('company_packages', 'company_packages.package_id', '=', 'company_payments.package_id')
        ->join('companies', 'companies.id', '=', 'company_payments.company_id')
            ->where('payment_status', '=', '0')
            ->whereDate('end_date', '<=', date('Y-m-d H:i:s', strtotime('+1 month')))->get();

        foreach ($payment as $key => $value) {
            
           
            JobsPayment::dispatch($value);
        }
    }
}
