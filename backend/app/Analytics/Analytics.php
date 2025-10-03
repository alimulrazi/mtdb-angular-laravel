<?php

namespace App\Analytics;

use Illuminate\Support\Collection;

class Analytics
{
    public function fetchTopBrowsers(Period $period): Collection
    {
        return collect([]);
    }

    public function fetchVisitorsAndPageViews(Period $period): Collection
    {
        return collect([]);
    }

    public function performQuery(Period $period, string $metrics, array $options = [])
    {
        return (object) ['rows' => null];
    }
}