<?php

namespace App\DataTransferObjects;

use App\DataTransferObjects\DataTransferObject;

use App\Enums\CompanyStatus;


class CompanyData extends DataTransferObject
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $name=null,
        protected ?string $lastname=null,
        protected ?string $companyName=null,
        protected ?string $email=null,
        protected ?string $siteUrl=null,
        protected ?string $password=null,
        protected ?string $status=CompanyStatus::PASSIVE,
        protected ?string $token= null,
        protected ?PackageData $packageData=null,

       

    ) {
        $this->modifyEmptyStringsToNull();
    }
}
