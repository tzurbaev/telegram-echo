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
     */
    public function handle()
    {
        $from = Carbon::now()->second(0);
        $till = Carbon::now()->second(59);

        $posts = Post::shouldBePublishedBetween($from, $till)->get();

        if (!count($posts)) {
            $this->info('No posts were found (from: '.$from.', till: '.$till.')');

            return;
        }

        $this->info('Got '.count($posts).' pending posts.');

        $posts->each(function ($post) {
            dispatch(new PublishScheduledPost($post));
        });
    }
}
