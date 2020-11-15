<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory;

    /**
     * Ability to work with Nested Set Model
     */
    use NodeTrait;

    /**
     * Fillable attributes
     *
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * Hidden attributes (from Nested Set Model)
     *
     * @var string[]
     */
    protected $hidden = ['_lft', '_rgt', 'parent_id'];

    /**
     * Disable timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}
