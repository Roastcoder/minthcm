<?php

namespace mehar finance\Lib\MintLogic\Formulas;

use mehar finance\Lib\MintLogic\Formula;

class _And extends Formula
{
    public function execute(...$args)
    {
        foreach ($args as $arg) {
            if (!boolval(self::executeOperator($arg['op'], $this->bean, ...$arg['args']))) {
                return false;
            }
        }
        return true;
    }
}
