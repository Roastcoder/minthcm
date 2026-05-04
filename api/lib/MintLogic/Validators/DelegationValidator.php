<?php

namespace mehar finance\Lib\MintLogic\Modules\Delegations\Validators;

use mehar finance\Lib\MintLogic\Exceptions\ValidationException;
use mehar finance\Lib\MintLogic\Validator;

class DelegationValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $db = \DBManagerFactory::getInstance();
        $query = sprintf(
            "SELECT id FROM %s WHERE deleted = 0 AND name = '%s' AND start_date = '%s' AND end_date = '%s' AND id != '%s'",
            $bean->table_name,
            $bean->name,
            $bean->start_date,
            $bean->end_date,
            $bean->id
        );
        if ($db->getOne($query)) {
            throw new ValidationException('ERR_DELEGATION_EXISTS');
        }
    }
}