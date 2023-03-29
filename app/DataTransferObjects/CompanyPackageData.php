<?php

namespace App\DataTransferObjects;

use DateTime;

class CompanyPackageData extends DataTransferObject
{
    public function __construct(
        protected ?int $id = null,
        protected ?int $companyId = null,
        protected ?int $packageId = null,
        protected ?DateTime $startDate=null,
        protected ?DateTime $endDate = null,


    ) {
        $this->modifyEmptyStringsToNull();
    }
}
