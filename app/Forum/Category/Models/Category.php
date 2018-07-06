<?php

namespace App\Forum\Category\Models;

use App\Forum\Base\Models\Base;

class Category extends Base
{
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
        'slug',
        'active',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'active'   => 'boolean',
    ];
}
