<?php

namespace App\Forum\Topic\Models;

use App\Forum\Base\Models\Base;
use App\Forum\Category\Models\Category;
use App\Forum\User\Models\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Topic extends Base
{
    use Sluggable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'topics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'active',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function sluggable()
    {
        return ['slug' => ['source' => 'title']];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIsNotAdmin($query)
    {
        $user = Auth::user();

        if (!is_null($user) && !$user->isAdmin()) {
            return $query->where('user_id', $user->id);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('topics.active', 1);
    }

    public function scopeJoinCategory($query)
    {
        return $query->join('categories', 'categories.id', '=', 'topics.category_id');
    }
}
