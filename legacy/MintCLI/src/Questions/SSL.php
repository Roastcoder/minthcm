<?php

namespace mehar finance\MintCLI\Questions;

class SSL extends ConfirmationQuestion
{
    protected $question = "SSL";
    protected $defaultValue = false;
    protected $defaultDisplayValue = 'no';
}
