<?php

namespace App\Analytics;

use Carbon\Carbon;

class Period
{
    public static function days(int $days): self
    {
        return new self();
    }

    public static function create(Carbon $start, Carbon $end): self
    {
        return new self();
    }
}