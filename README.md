# Eloquent-Relationships-Advanced-Laravel-7-and-MySQL

## Description

This repository is a Software of Development with Laravel,MySQL and Bootstrap,etc

## Installation

Using Laravel 7 preferably.

## DataBase

Using MySQL preferably.
Create a MySQL database and configure the .env file.

## Apps

Using Postman, Insomnia,etc

## Usage

```html
$ git clone https://github.com/DanielArturoAlejoAlvarez/Eloquent-Relations-Laravel-7-and-MySQL[NAME APP]

$ composer install

$ copy .env.example .env

$ php artisan key:generate

$ php artisan migrate:refresh --seed

$ php artisan serve

$ npm install (Frontend)

$ npm run dev

```

Follow the following steps and you're good to go! Important:

![alt text](https://dab1nmslvvntp.cloudfront.net/wp-content/uploads/2017/07/1500534190watcher.gif)

## Coding

### Models

```php
...
class Post extends Model
{
    public function user() {
      return $this->belongsTo(User::class);
    }

    public function category() {
      return $this->belongsTo(Category::class);
    }

    public function comments() {
      return $this->morphMany(Comment::class, 'commentable');
    }

    public function image() {
      return $this->morphOne(Image::class, 'imageable');
    }

    public function tags() {
      return $this->morphToMany(Tag::class, 'taggable');
    }
}


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile() {
      return $this->hasOne(Profile::class);
    }

    public function level() {
      return $this->belongsTo(Level::class);
    }

    public function groups() {
      return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function location() {
      return $this->hasOneThrough(Location::class, Profile::class);
    }

    public function posts() {
      return $this->hasMany(Post::class);
    }

    public function videos() {
      return $this->hasMany(Video::class);
    }

    public function comments() {
      return $this->hasMany(Comment::class);
    }

    public function image() {
      return $this->morphOne(Image::class, 'imageable');
    }
}
...
```

### Routes
```php
...
Route::get('/profile/{id}', function($id) {
    $user = App\User::find($id);
    $posts = $user->posts()->with('category','image','tags')->withCount('comments')->get();
    $videos = $user->videos()->with('category','image','tags')->withCount('comments')->get();
    //dd($user->name);
    return view('profile', [
        'user'  =>  $user,
        'posts' =>  $posts,
        'videos'=>  $videos
    ]);
})->name('profile');
...
```

### Factory
```php
...
$factory->define(User::class, function (Faker $faker) {
    return [
        'level_id'          =>    $faker->randomElement([null,1,2,3]),
        'name'              =>    $faker->name,
        'email'             =>    $faker->unique()->safeEmail,
        'email_verified_at' =>    now(),
        'password'          =>    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token'    =>    Str::random(10),
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    return [
        'name'        =>    $faker->sentence,
        'category_id' =>    rand(1,4),
        'user_id'     =>    rand(1,5)
    ];
});
...
```

### Seeders
```php
...
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
...
```

### Views
```php
...
<div class="row">
    @foreach($videos as $video)
    <div class="col-6">
        <div class="card mb-3">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="{{ $video->image->url }}" class="card-img">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ $video->name }}</h5>
                        <h6 class="card-subtitle text-muted">
                            {{ $video->category->name }} |
                            {{ $video->comments_count }}
                            {{ Str::plural('Comment', $video->comments_count) }}
                        </h6>
                        <hr>
                        <p class="text-small">
                            @foreach($video->tags as $tag)
                                <span class="badge badge-light">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
...
```


## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/DanielArturoAlejoAlvarez/Eloquent-Relationships-Advanced-Laravel-7-and-MySQL. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

## License

The gem is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

```

```
