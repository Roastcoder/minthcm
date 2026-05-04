<?php

namespace mehar finance\Lib\MintLogic\Formulas;

use mehar finance\Lib\MintLogic\Formula;

class Equals extends Formula
{
    public function execute(...$args)
    {
        return $args[0] == $args[1];
    }
}
