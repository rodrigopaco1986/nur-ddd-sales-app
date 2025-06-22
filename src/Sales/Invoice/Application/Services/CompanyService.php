<?php

namespace Src\Sales\Invoice\Application\Services;

use Src\Sales\Company\Domain\Entities\Company;

class CompanyService
{
    // TODO: Call from micro service
    public function getInfo(): Company
    {
        $companyConfig = config('company');

        return new Company(
            $companyConfig['name'],
            $companyConfig['nit'],
            $companyConfig['authorizationCode'],
            $companyConfig['address'],
            $companyConfig['phone'],
            $companyConfig['email'],
        );
    }
}
