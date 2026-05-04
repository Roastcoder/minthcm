<?php

namespace mehar finance\Lib\MintLogic;

enum Hook: string
{
    case ALL = 'all';
    case INIT = 'init';
    case CHANGE = 'change';
}
