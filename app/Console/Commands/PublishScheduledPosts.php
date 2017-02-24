<?php

namespace App\Console\Commands;

use App\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\PublishScheduledPost;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes scheduled posts.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from = Carbon::now()->subMinutes(2);
        $till = Carbon::now()->addMinutes(2);

        $posts = Post::shouldBePublishedBetween($from, $till)->get();

        if (!count($posts)) {
            return true;
        }

        $this->info('Got '.count($posts).' pending posts.');

        $posts->each(function ($post) {
            dispatch(new PublishScheduledPost($post));
        });
    }
}
