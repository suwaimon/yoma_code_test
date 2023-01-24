<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MovieTag;
use App\Models\Rating;
use App\Models\Tag;
use App\Models\Comment;

class Movie extends Model
{
    use HasFactory;
    
    protected $table = 'movies';

    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'title', 
        'summary', 
        'cover_image', 
        'user_id',
        'genre_id',
        'author_id', 
        'pdf_url',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function tags() {
        return $this->belongsToMany('App\Models\Tag');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    public function ratings() {
        return $this->hasMany('App\Models\Rating');
    }

    public function author() {
        return $this->belongsTo('App\Models\Author');
    }

    public function genre() {
        return $this->belongsTo('App\Models\Genre');
    }

    public function getRatings() {
        return Rating::where('movie_id', $this->id)->value('rate');
    }

    public function getTagsName() {
        return $this->tags->pluck('name');
    }

    public function getComments() {
        return Comment::where('movie_id', $this->id)
                        ->select('description', 'email', 'created_at')
                        ->get();
    }

    public function relatedMovies() {
        
        $tags = $this->tags->pluck('id')->toArray();
        $movieIdByTags = MovieTag::whereIn('tag_id', $tags)->pluck('movie_id')->toArray();

        $ratings = $this->ratings()->pluck('rate')->toArray();
        $movieIDByRatings = Rating::whereIn('rate', $ratings)->pluck('movie_id')->toArray();

        $combine_movies_ids = array_merge($movieIDByRatings,$movieIdByTags);

        $movieIds = array_filter($combine_movies_ids, function($value) {
            return $value != $this->id;  
        });

        $movieList =  Movie::where('genre_id', $this->genre_id)
                            ->orWhere('author_id', $this->author_id)
                            ->orWhereIn('id', $movieIds)
                            ->take(7)
                            ->get();
                
        return $movieList->except($this->id);

    }
    
}
