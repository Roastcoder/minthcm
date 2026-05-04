<?php

namespace mehar finance\MintCLI\Questions;
use mehar finance\MintCLI\InputValidators\NotEmptyValidator;

class ModuleName extends Question
{
    protected $question = "Module Name";
    protected $defaultValue = null;
    
    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator()
        ];
    }
}
