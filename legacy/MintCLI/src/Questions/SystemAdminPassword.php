<?php

namespace mehar finance\MintCLI\Questions;

use mehar finance\MintCLI\InputValidators\NotEmptyValidator;
use mehar finance\MintCLI\InputValidators\NoWhitespaceValidator;

class SystemAdminPassword extends Question
{
    protected $question = "System Administrator Password";    
    protected $defaultValue = null;

    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator(),
            new NoWhitespaceValidator(),
        ];
    }
}
