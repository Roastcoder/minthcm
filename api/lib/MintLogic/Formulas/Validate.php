<?php

namespace mehar finance\Lib\MintLogic\Formulas;

use mehar finance\Lib\MintLogic\Exceptions\ValidationException;
use mehar finance\Lib\MintLogic\Formula;

class Validate extends Formula
{
    public function execute($expr, $message)
    {
        if (boolval(self::executeOperator($expr['op'], $this->bean, ...$expr['args']))) {
            throw new ValidationException($message);
        }
        return true;
    }
}
