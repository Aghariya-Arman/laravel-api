<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\fileExists;

class PostController extends Controller
{
    public function index()
    {
        $data['posts'] = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'get all data',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validatepost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validatepost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatepost->errors()->all()
            ], 401);
        }
        //$path = $request->file('photo')->store('image', 'public');

        $img = $request->image;
        $text = $img->getClientOriginalExtension();
        $filename = time() . "." . $text;
        $img->move(public_path() . '/uploads', $filename);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filename,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'add successfully',
            'post' => $post,
        ], 200);
    }

    public function show(string $id)
    {
        $data['posts'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->find($id);

        return response()->json([
            'status' => true,
            'message' => 'single post',
            'post' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatepost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validatepost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatepost->errors()->all()
            ], 401);
        }

        //get user update image && id
        $postimage = Post::select('id', 'image')->where(['id' => $id])->get();
        //return $postimage[0]['image'];

        //check form filled
        if ($request->image != '') {
            //assign folder image
            $path = public_path() . '/uploads/';

            //check image exists in database or not
            if ($postimage[0]->image != '' && $postimage[0]->image  != null) {
                //assign image into old image
                $old_file = $path . $postimage[0]->image;
                //check file exists in folder
                if (fileExists($old_file)) {
                    unlink($old_file);
                }
            }
            $img = $request->image;
            $text = $img->getClientOriginalExtension();
            $filename = time() . "." . $text;
            $img->move(public_path() . '/uploads', $filename);
        } else {
            $filename = $$postimage[0]->image;
        }

        //$path = $request->file('photo')->store('image', 'public');
        $post = Post::where(['id' => $id])->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $filename,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'updated successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagepath = Post::select('image')->where('id', $id)->get();
        $filepath = public_path() . '/uploads/' . $imagepath[0]['image'];

        unlink($filepath);

        $post = Post::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'your post has been deleted',
            'post' => $post,
        ], 200);
    }
}
