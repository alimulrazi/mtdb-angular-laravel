<?php

namespace Api\Notifications;

use Api\Auth\BaseUser;
use Api\Core\Policies\BasePolicy;

class NotificationSubscriptionPolicy extends BasePolicy
{
    public function index(BaseUser $user, $userId)
    {
        return ($userId && $user->id === (int) $userId);
    }

    public function update(BaseUser $user, $userId)
    {
        return ($userId && $user->id === (int) $userId);
    }
}

