<?php

namespace Api\Csv;

use Auth;
use Api\Auth\Jobs\ExportUsersCsv;

class CommonCsvExportController extends BaseCsvExportController
{
    public function exportUsers()
    {
        return $this->exportUsing(new ExportUsersCsv(Auth::id()));
    }
}

