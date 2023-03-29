<?php

namespace App\DataTransferObjects;

use App\Enums\CompanyPaymentStatus;

class CompanyPaymentData extends DataTransferObject
{
    public function __construct(
        protected ?int $id = null,
        protected ?int $companyId = null,
        protected ?int $packageId = null,
        protected ?int $packagePrice = null,
        protected ?string $paymentStatus = CompanyPaymentStatus::NOTPAID,
        protected ?array $cardInfo=null,
        protected ?string $receipt = null,
    ) {
        $this->modifyEmptyStringsToNull();
    }
}
