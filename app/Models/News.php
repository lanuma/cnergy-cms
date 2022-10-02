<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $guard = [];

    protected $table = 'news';

    protected $primaryKey = 'id';

    protected $fillable = [
        'is_headline',
        'title',
        'slug',
        'types',
        'image',
        'video ',
        'synopsis',
        'content',
        'published_at',
        'published_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'category_id',
        'is_published'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'news_tags');
    }
}
