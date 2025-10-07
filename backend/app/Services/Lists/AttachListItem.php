<?php

namespace App\Services\Lists;

use App\Models\Listable;
use App\Models\ListModel;
use Carbon\Carbon;

class AttachListItem
{
    public function execute(ListModel $list, $params)
    {
        $count = app(Listable::class)->where('list_id', $list->id)->count();

        app(Listable::class)->insert([
            'list_id' => $list->id,
            'listable_type' => $list->getListableType($params['itemType']),
            'listable_id' => $params['itemId'],
            'order' => $count + 1,
            'created_at' => Carbon::now(),
        ]);
    }
}