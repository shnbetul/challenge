<?php

namespace App\DataTransferObjects;

use App\DataTransferObjects\DataTransferObject;
use App\Enums\packagePeriod;
use App\Enums\PackageStatus;


class PackageData extends DataTransferObject
{

    public function __construct(
        protected ?int $id = null,
        protected ?string $name = null,
        protected ?string $status = PackageStatus::PASSIVE,
        protected ?int $price = null,
        protected ?string $periyot = packagePeriod::NOT_ASSIGN,
    ) {
        $this->modifyEmptyStringsToNull();
    }
}
