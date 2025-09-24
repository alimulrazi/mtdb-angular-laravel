<?php

namespace Api\Workspaces\Listeners;

use Api\Workspaces\Actions\JoinWorkspace;
use Api\Workspaces\WorkspaceInvite;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;

class AttachWorkspaceToUser
{
    /**
     * @param  Login|Registered  $event
     * @return void
     */
    public function handle($event)
    {
        $inviteId = session()->get('workspaceInvite');
        if ( ! $inviteId) return;

        $invite = app(WorkspaceInvite::class)->find($inviteId);
        if ($invite) {
            app(JoinWorkspace::class)->execute($invite, $event->user);
        }
    }
}

