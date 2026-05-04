<?php

namespace mehar finance\Lib\MintLogic\Formulas;

use mehar finance\Lib\MintLogic\Formula;

class _Empty extends Formula
{
    public function execute($arg)
    {
        return empty($arg);
    }
}
