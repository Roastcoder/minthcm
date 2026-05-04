<?php

namespace mehar finance\Lib\MintLogic\Formulas;

use mehar finance\Lib\MintLogic\Formula;

class _Not extends Formula
{
    public function execute($arg)
    {
        return !boolval(self::executeOperator($arg['op'], $this->bean, ...$arg['args']));
    }
}
