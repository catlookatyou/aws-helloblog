<?php

use Illuminate\Database\Seeder;

use App\User as UserEloquent;
use App\Post as PostEloquent;
use App\PostType as PostTypeEloquent;
use Carbon\Carbon;  

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    /*$users = factory(UserEloquent::class, 4)->create();
	    $postTypes = factory(PostTypeEloquent::class, 10)->create();
	    $posts= factory(PostEloquent::class, 50)->create()
		    ->each(function($post) use ($postTypes){
			    $post->type = $postTypes[mt_rand(0, (count($postTypes)-1))]->id;
			    $post->save();
		    });
        $comments = factory(CommentEloquent::class, 300)->create();*/
        /*DB::table('post_types')->insert([
            [
                'id' => 1,
                'name'=>'test',
            ],
        ]);
        DB::table('posts')->insert([
            [
                'title'=>'helloworld',
                'type'=> 1,
                'content'=> "hihi",
                'user_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'title'=>'test2',
                'type'=> 1,
                'content'=> "hihi",
                'user_id'=>2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);*/
    }
}
