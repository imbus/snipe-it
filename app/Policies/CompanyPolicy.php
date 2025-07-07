<?php

namespace App\Policies;

use App\Models\Setting;

class CompanyPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'companies';
    }
    
}
