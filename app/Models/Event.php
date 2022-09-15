<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        "title",
        "slug",
        "image",
        "date_time",
        "start_publish_date",
        "end_publish_date",
        "description",
        "has_booking",
        "confirm_booking",
        "featured",
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom("title")
            ->saveSlugsTo("slug");
    }
}
