<?php

namespace App\Contracts\Posts;

use App\Contracts\PostContract;
use App\Contracts\Transports\TransportContract;

interface PublisherContract
{
    /**
     * Заменяет транспорт публикации.
     *
     * @param \App\Contracts\Transports\TransportContract $transport
     *
     * @return \App\Contracts\Posts\PublisherContract
     */
    public function withTransport(TransportContract $transport);

    /**
     * Публикует запись.
     *
     * @param \App\Contracts\PostContract $post
     *
     * @return bool
     */
    public function publish(PostContract $post);
}
