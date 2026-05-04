<?php

namespace mehar finance\MintCLI\Questions;

class DatabaseCollation extends Question
{
    protected $question = "Database Collation";
    protected $defaultValue = "utf8mb4_general_ci";
}
