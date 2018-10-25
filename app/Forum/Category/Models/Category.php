<?php

namespace App\Forum\Category\Models;

use App\Forum\Base\Models\Base;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Category extends Base
{
    use Sluggable;
    use SoftDeletes;
    use SoftCascadeTrait;
    use PresentableTrait;

    protected $presenter = \App\Forum\Category\Presenters\CategoryPresenter::class;

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

    protected $softCascade = ['topics'];

    public function sluggable()
    {
        return ['slug' => ['source' => 'name']];
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function topics()
    {
        return $this->hasMany('App\Forum\Topic\Models\Topic', 'category_id');
    }
}
