<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;


class PostController extends BaseController
{

    public function index()
    {
       $posts = Post::latest()->paginate(5);
       return $this->sendResponse(PostResource::collection($posts), 'Posts retrieved success.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'teknologi' => 'required',
            'repo' => 'required',
            'live' => '',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validator error.', $validator->errors());
        }

        $image = $request->file('photo');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'teknologi' => $request->teknologi,
            'repo' => $request->repo,
            'live' => $request->live,
            'photo' => $image->hashName(),
        ]);

        return $this->sendResponse(new PostResource($post), 'Created success.');
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        return $this->sendResponse(new PostResource($post), 'Posts retrieved success.');
    }

    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'teknologi' => 'required',
            'repo' => 'required',
            'live' => '',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validator error.', $validator->errors());
        }

        if($request->hashFile('photo')) {
            $image = $request->file('photo');
            $image->storeAs('public/posts', $image->hashName());

            Storage::delete('public/posts/'.$post->photo);

            $post->update([
                'title' => $request->title,
                'description' => $request->description,
                'teknologi' => $request->teknologi,
                'repo' => $request->repo,
                'live' => $request->live,
                'photo' => $image->hashName(),
            ]);
        }else{
            $post->update([
                'title' => $request->title,
                'description' => $request->description,
                'teknologi' => $request->teknologi,
                'repo' => $request->repo,
                'live' => $request->live,
            ]);
        }

        return $this->sendResponse(new PostResource($post), 'Update success.');
    }

    public function destroy(Post $post)
    {
        Storage::delete('public/posts/'.$post->image);
        $post->delete();

        return $this->sendResponse([], 'Delete success.');
    }
}
