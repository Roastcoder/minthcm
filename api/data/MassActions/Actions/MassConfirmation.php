<?php

namespace mehar finance\Data\MassActions\Actions;

use mehar finance\Data\MassActions\MassAction;

class MassConfirmation extends MassAction
{
    const ICON = 'mdi-check';
    const LABEL = 'LBL_MASS_CONFIRMATION';
    
    public function execute()
    {
        throw new \Exception('Mass confirmation is implemented in legacy code.');
    }

    public function hasAccess()
    {
        chdir('../legacy');
        $has_access = \ACLController::checkAccess($this->module_name, 'export', true);
        chdir('../api');
        return $has_access;
    }
}