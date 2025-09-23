<?php

namespace Webkul\Menu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shetabit\Visitor\Traits\Visitable;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Core\Eloquent\TranslatableModel;

class Category extends TranslatableModel implements CategoryContract
{
    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    

}
