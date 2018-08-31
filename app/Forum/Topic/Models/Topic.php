<?php

namespace App\Forum\Topic\Models;

use App\Forum\Base\Models\Base;
use App\Forum\Category\Models\Category;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
