<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request, Idea $idea){

       /*  $validated = request()->validated([
            'content' => 'required|min:3|max:240'
        ]); */
        //dd($idea->id);
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();
        $validated['idea_id'] = $idea->id;

        Comment::create($validated);

        /* $comment = new Comment();
        $comment->idea_id = $idea->id;
        $comment->user_id =  auth()->id();
        $comment->content = request()->get('content');
        $comment->save(); */

        return redirect()->route('ideas.show', $idea->id)->with('success',"Comment Posted Successfully");
    }
}
