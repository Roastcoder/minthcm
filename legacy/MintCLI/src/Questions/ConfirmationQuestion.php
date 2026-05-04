<?php

namespace mehar finance\MintCLI\Questions;

use mehar finance\MintCLI\InputValidators\NoWhitespaceValidator;
use mehar finance\MintCLI\InputValidators\YesNoValidator;
use Symfony\Component\Console\Question\ConfirmationQuestion as BasicConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[\AllowDynamicProperties]
class ConfirmationQuestion extends Question
{
    protected $defaultDisplayValue;

    public function ask()
    {
        $this->question = $this->question . " (yes/no)";
        if (isset($this->defaultValue)) {
            $this->question = $this->question . " [" . $this->defaultDisplayValue . "]";
        }
        $this->question = $this->question . ": ";
        $question = new BasicConfirmationQuestion($this->question, $this->defaultValue);

        return $this->qh->ask($this->input, $this->output, $question);
    }

}
