<?php

namespace mehar finance\Lib\MintLogic\Validators;

use mehar finance\Lib\MintLogic\Exceptions\ValidationException;
use mehar finance\Lib\MintLogic\Validator;

class IsUnique extends Validator
{
    public function validate($bean, $field = 'name')
    {
        if (empty($bean->$field)) {
            return;
        }
        if ($bean->db->getOne("SELECT id FROM {$bean->table_name} WHERE deleted = 0 AND {$field} = '{$bean->$field}' AND id != '{$bean->id}'")) {
            throw new ValidationException('ERR_NOT_UNIQUE');
        }
    }
}
