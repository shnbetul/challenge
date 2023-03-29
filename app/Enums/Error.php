<?php

namespace App\Enums;

class Error extends Enum
{
    const USER_NOT_CREATE = [
        'code' => 5001,
        'message' => 'Company user is not created',
    ];

    const NOT_MATCH_EMAIL_OR_PASSWORD = [
        'code' => 401,
        'message' => 'Email & Password is not match our record',
    ];

    const PACKAGE_NOT_CREATE = [
        'code' => 5001,
        'message' => 'Package is not created',
    ];

    const PACKAGE_NOT_UPDATE = [
        'code' => 5001,
        'message' => 'Package status is not update',
    ];

    const COMPANY_PACKAGE_NOT_ASSIGN = [
        'code' => 5001,
        'message' => ' Company package is not assign',
    ];

    const COMPANY_PAYMENT_NOT_COMPLETED = [
        'code' => 5001,
        'message' => ' Company payment is not completed',
    ];
}

