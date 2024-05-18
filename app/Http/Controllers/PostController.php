<?php

namespace App\Http\Controllers;
use App\Models\Posts;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostResource;



class PostController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 2);
        $posts = Posts::paginate($perPage);
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return PostResource::notFoundErrorResponse();
        }

        return PostResource::successResponse('show', 'Post fetched successfully', new PostResource($post));
    }
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image_path' => 'required|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return PostResource::validationErrorResponse($validator);
        }

        $image = $request->file('image_path');
        $imageName = time() . '.' . $image->extension();
        $image = $image->storeAs('public/storage/images', $imageName);

        $post = Posts::create($request->all());

        return PostResource::successResponse('store', 'Post created successfully', new PostResource($post), 201);
    }

    public function update(Request $request, $id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return PostResource::notFoundErrorResponse();
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image_path' => 'required|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return PostResource::validationErrorResponse($validator);
        }

        $post->update($request->all());

          return PostResource::successResponse('update', 'Post updated successfully', new PostResource($post));
    }


    public function destroy($id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return PostResource::notFoundErrorResponse();
        }

        $post->delete();

        return PostResource::successResponse('destroy' , 'Post deleted successfully', 200);
    }

}

