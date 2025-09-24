<?php

namespace Api\Admin\Analytics\Actions;

interface GetAnalyticsHeaderDataAction
{
    /**
     * Get analytics header data.
     *
     * @param string $channel
     * @return array
     */
    public function execute($channel);
}

