<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\Tag;
use App\User;
use App\Post;
use App\Level;
use App\Image;
use App\Video;
use App\Profile;
use App\Comment;
use App\Location;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        factory(Group::class, 3)->create();

        factory(Level::class)->create(['name'=>'Gold']);
        factory(Level::class)->create(['name'=>'Silver']);
        factory(Level::class)->create(['name'=>'Bronze']);

        factory(User::class, 5)->create()->each(function($user) {
          $profile = $user->profile()->save(factory(Profile::class)->make());
          $profile->location()->save(factory(Location::class)->make());
          $user->groups()->attach($this->array(rand(1,3)));
          $user->image()->save(factory(Image::class)->make(['url'=>$this->getAvatar(['men','women'],rand(1,99))]));
        });

        factory(Category::class, 4)->create();

        factory(Tag::class, 12)->create();

        factory(Post::class, 40)->create()->each(function($post) {
          $post->image()->save(factory(Image::class)->make(['url' => $this->getPic(rand(1,249))]));
          $post->tags()->attach($this->array(rand(1,12)));

          $number_comments = rand(1,6);
          for ($i=0; $i < $number_comments; $i++) {
            $post->comments()->save(factory(Comment::class)->make());
          }
        });

        factory(Video::class, 40)->create()->each(function($video) {
          $video->image()->save(factory(Image::class)->make(['url'=>$this->getPic(rand(250,500))]));
          $video->tags()->attach($this->array(rand(1,12)));

          $number_comments = rand(1,6);
          for ($i=0; $i < $number_comments; $i++) {
            $video->comments()->save(factory(Comment::class)->make());
          }
        });

    }

    private function array($max) {
      $values = [];
      for ($i=1; $i < $max; $i++) {
        $values[] = $i;
      }
      return $values;
    }

    private function getPic($max) {
      return 'https://picsum.photos/id/'.$max.'/1024/';
    }

    private function getAvatar($arr,$max) {
      $arr_index = array_rand($arr);
      $index = $arr[$arr_index];
      return 'https://randomuser.me/api/portraits/'.$index.'/'.$max.'.jpg';
    }
}
