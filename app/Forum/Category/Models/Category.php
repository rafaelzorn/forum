<?php

namespace App\Forum\Category\Models;

use App\Forum\Base\Models\Base;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Base
{
    use Sluggable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
        return ['slug' => ['source' => 'name']];
    }
}
