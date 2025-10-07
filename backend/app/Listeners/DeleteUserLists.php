<?php

namespace App\Listeners;

use App\Models\ListModel;
use App\Services\Lists\DeleteLists;
use Api\Auth\Events\UserCreated;
use Api\Auth\Events\UsersDeleted;

class DeleteUserLists
{
    /**
     * @var ListModel
     */
    private $list;

    /**
     * @param ListModel $list
     */
    public function __construct(ListModel $list)
    {
        $this->list = $list;
    }

    /**
     * @param UsersDeleted $event
     */
    public function handle(UsersDeleted $event)
    {
        $listIds = $this->list->whereIn('user_id', $event->users->pluck('id'))->pluck('id');
        app(DeleteLists::class)->execute($listIds);
    }
}
