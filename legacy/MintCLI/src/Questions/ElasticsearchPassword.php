<?php

namespace mehar finance\MintCLI\Questions;

use mehar finance\MintCLI\InputValidators\NotEmptyValidator;
use mehar finance\MintCLI\InputValidators\NoWhitespaceValidator;

class ElasticsearchPassword extends Question
{
    protected $question = "Elasticsearch Password";
    protected $defaultValue = "changeme";

    public function __construct($qh, $input, $output)
    {
        parent::__construct($qh, $input, $output);
        $this->validators = [
            new NotEmptyValidator(),
            new NoWhitespaceValidator(),
        ];
    }
}
