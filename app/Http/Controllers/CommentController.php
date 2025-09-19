<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;
use \App\Models\Comment;

class commentController extends Controller
{

    public function create(Request $request)
    {
        $commentItem = new Comment;
        $commentItem->contents = $_POST['content'];
        $commentItem->user_id = Auth::user()->id;
        $commentItem->blog_id = $_POST['blog_id'];

        $commentItem->save();
        return redirect()->route('page.blogfull', ['id' => $_POST['blog_id']]);
    }

    public function delete()
    {
        try {
            $comment = \App\Models\Comment::find($_GET['id']);
            $blogItem = $comment['blog_id'];
            $comment->delete();
        } catch (\Exception $e) {
            http_response_code(400);
        }
        return redirect()->route('page.blogfull', ['id' => $blogItem]);
    }

    public function edit()
    {
        try {
            $comment = Comment::find($_GET['id']);
            $blogItem = $comment['blog_id'];
            assert(Auth::user()->id == $comment['user_id']);
            $comment->CONTENTS = $_POST['content'];
            $comment->save();
        } catch (\Exception $e) {
            http_response_code(400);
        }
        return redirect()->route('page.blogfull', ['id' => $blogItem]);
    }
}
