<?php

namespace App\Policies;

use App\Models\Setting;

class CompanyPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'companies';
    }

    public function canEditThisCompany($company_id = null) {
        if ((Setting::getSettings()->scope_locations_fmcs) && ($this->company_id == $company_id)){
            return true;
        }

        return false;
    }
}
