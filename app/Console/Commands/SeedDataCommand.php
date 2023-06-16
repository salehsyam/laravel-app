<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;



class SeedDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  'data:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding some fake data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '500MB');
        DB::disableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
 //       $this->seedUsers();
//        $this->seedCategories();
//        $this->seedTags();
        $this->seedPosts();
        $this->seedTagsLinks();

    }

    public function __destruct()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedUsers()
    {
        $this->info('Creating users');
        User::truncate();
           \App\Models\User::factory(500)->create();
    }

    private function seedTags()
    {
        $this->info('Creating tags');
        Tag::truncate();
        foreach(range(1,4) as $_) {
         \App\Models\Tag::factory(1000)->create()->toArray();

        }
    }

    private function seedCategories()
    {
        $this->info('Creating categories');
        Category::truncate();
        foreach(range(1,7) as $_) {
           \App\Models\Category::factory(500)->create()->toArray();

        }
    }

    private function seedPosts($target = 50000)
    {
        $this->info('Creating posts');
        $this->output->progressStart($target);
        Post::truncate();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $entries = [];
        $i = 0;
        while ($i < $target) {
            $i++;
            $entries[] = \App\Models\Post::factory()->make([
                'user_id' => rand(1,$totalUsers),
                'category_id' => rand(1, $totalCategories),
            ])->toArray();

            if($i % 500 == 0) {
                Post::insert($entries);
                $entries = [];
                $this->output->progressAdvance(500);
            }
        }

        $this->output->progressFinish();
    }

    private function seedTagsLinks()
    {
        $postsCount = Post::count();
        DB::statement('Truncate post_tag');
        $this->info('linking tags');
        $this->output->progressStart($postsCount);
        Post::chunk(
            1000,
            function ($posts) {
                $links = [];
                $ids = Tag::query()->limit(50)->get()->random(3)->pluck('id')->toArray();
                $posts->each(
                    function ($post) use ($ids, &$links) {
                        foreach ($ids as $id) {
                            $links[] = [
                                'post_id' => $post->id,
                                'tag_id' => $id,
                            ];
                        }
                    }
                );
                DB::table('post_tag')->insert($links);
                $this->output->progressAdvance(1000);
            }
        );
        $this->output->progressFinish();
    }


}
