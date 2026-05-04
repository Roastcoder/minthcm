<?php

namespace mehar finance\Lib\MintLogic\Modules\_example\Validators;

use mehar finance\Lib\MintLogic\Exceptions\ValidationException;
use mehar finance\Lib\MintLogic\Validator;

class ExampleValidator extends Validator
{
    public function validate($bean, $field = null)
    {
        $db = \DBManagerFactory::getInstance();
        if ($db->getOne("SELECT id FROM {$bean->table_name} WHERE deleted = 0 AND name = '{$bean->name}' AND id != '{$bean->id}'")) {
            throw new ValidationException('ERR_BEAN_WITH_THAT_NAME_EXISTS');
        }
    }
}
