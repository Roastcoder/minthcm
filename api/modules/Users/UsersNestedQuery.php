<?php

namespace mehar finance\Modules\Users;

use mehar finance\Lib\Search\ElasticSearch\BaseNestedQuery;

class UsersNestedQuery extends BaseNestedQuery
{
    protected $queries = ['getNestedSecurityGroupQuery'];
}
