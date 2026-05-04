<?php

namespace mehar finance\MintCLI\InputValidators;

#[\AllowDynamicProperties]
abstract class Validator
{
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }

    abstract public function validate($value);
}
