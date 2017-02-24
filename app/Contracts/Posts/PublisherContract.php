<?php

namespace App\Contracts\Posts;

use App\Contracts\PostContract;

interface PublisherContract
{
    /**
     * Публикует запись.
     *
     * @param \App\Contracts\PostContract $post
     *
     * @return bool
     */
    public function publish(PostContract $post);
}
