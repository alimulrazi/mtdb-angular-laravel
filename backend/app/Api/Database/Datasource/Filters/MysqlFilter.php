<?php

namespace Api\Database\Datasource\Filters;

use Api\Database\Datasource\Filters\Traits\SupportsMysqlFilters;

class MysqlFilter extends BaseFilter
{
    use SupportsMysqlFilters;

    public function apply()
    {
        $this->applyMysqlFilters($this->filters, $this->query);

        if ($this->searchTerm) {
            $this->query->mysqlSearch($this->searchTerm);
        }

        return $this->query;
    }
}

