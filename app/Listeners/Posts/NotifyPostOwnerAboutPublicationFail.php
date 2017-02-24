<?php

namespace App\Listeners\Posts;

use App\Notifications\PostPublicationFailed;
use App\Events\Posts\ScheduledPostWasNotPublished;

class NotifyPostOwnerAboutPublicationFail
{
    /**
     * Handle the event.
     *
     * @param \App\Events\Posts\ScheduledPostWasNotPublished $event
     */
    public function handle(ScheduledPostWasNotPublished $event)
    {
        $notification = new PostPublicationFailed($event->post);

        $event->post->getOwner()->notify($notification);
    }
}
