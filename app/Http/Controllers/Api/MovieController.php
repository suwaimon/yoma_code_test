<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\MovieRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\MovieResource;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MovieResource::collection(Movie::latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieRequest $request)
    {
        $data = [
            'title' => $request->title,
            'summary' => $request->summary, 
            'author_id' => $request->author_id,
            'user_id' => auth()->user()->id,
            'genre_id' => $request->genre_id,
        ];
        
        if ($file = $request->file('cover_image')) {
            $data['cover_image'] = get_uploaded_file($file, 'movies');
        }

        if ($file = $request->file('pdf_url')) {
            $data['pdf_url'] = get_uploaded_file($file, 'movies');
        }

        $movie = Movie::create($data);

        if (!empty($request->tag)) {
            $tag_ids = array_map (
                function($value) { 
                    return (int)$value; 
                },
                $request->tag
            );

            $movie->tags()->attach($tag_ids, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
        $movie["route_url"]    = 'detail';
        return new MovieResource($movie);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        $movie["route_url"]    = 'detail';
        return new MovieResource($movie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(MovieRequest $request, Movie $movie)
    {
        if(auth()->user()->id != $movie->user_id) {
            return response(['message' => 'Unauthorised!']);
        }

        $data = [
            'title' => $request->title,
            'summary' => $request->summary, 
            'author_id' => $request->author_id,
            'user_id' => auth()->user()->id,
            'genre_id' => $request->genre_id,
        ];
        
        if ($file = $request->file('cover_image')) {
            $data['cover_image'] = get_uploaded_file($file, 'movies');
        }

        $movie->update($data);
        
        if (!empty($request->tag)) {
            $tag_ids = array_map (
                function($value) { 
                    return (int)$value; 
                },
                $request->tag
            );

            $movie->tags()->attach($tag_ids, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $movie["route_url"]    = 'detail';
        return new MovieResource($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie) {

        if(auth()->user()->id == $movie->user_id) {
            $movie->delete();
            return response(['message' => 'Successfully Deleted.']);
        }
        return response(['message' => 'Something is wrong!']);
    }

    public function saveComment(CommentRequest $request, Movie $movie) {
        $data = [
            'description' => $request->description,
            'email' => $request->email,
            'user_id' => auth()->user()->id,
            'movie_id' => $movie->id,
        ];

        $comment = Comment::create($data);
        $movie["route_url"]    = 'detail';
        
        return new MovieResource($movie);
    }
}
