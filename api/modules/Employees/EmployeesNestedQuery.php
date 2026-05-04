<?php

namespace mehar finance\Modules\Employees;

use mehar finance\Lib\Search\ElasticSearch\BaseNestedQuery;

class EmployeesNestedQuery extends BaseNestedQuery
{
    protected $queries = ['getNestedSecurityGroupQuery'];
}
