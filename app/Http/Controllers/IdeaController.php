<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;

class IdeaController extends Controller
{
    public function show(Idea $idea)
    {

        return view('ideas.show',[
            'idea' => $idea,
        ]);
    }
    public function edit(Idea $idea)
    {
        if(auth()->id() !== $idea->user_id){
            abort(404);
        }
        $editing = true;
        return view('ideas.show',compact('idea', 'editing'));
    }
    public function update(Idea $idea)
    {
        if(auth()->id() !== $idea->user_id){
            abort(404);
        }
       /*  request()->validate([
            'content' => 'required|min:3|max:240'
        ]);
        $idea->content = request()->get('content', '');
        $idea->save(); */

        //OR
        $validated = request()->validate([
            'content' => 'required|min:3|max:240'
        ]);

        $idea->update($validated);

        return redirect()->route('ideas.show',$idea->id)->with('success',"Idea updated successfully!");
    }
    public function store()
    {
        //dump();
        $validated = request()->validate([
            'content' => 'required|min:3|max:240'
        ]);

        $validated['user_id'] =  auth()->id();
        Idea::create($validated);

        return redirect()->route('dashboard')->with('success', 'Idea Created Successfully!');
    }

    //without route model binding
    /* 
    public function destroy($idea){
        $idea = Idea::where('id',$idea)->firstOrFail();
        $idea->delete();
        return redirect()->route('dashboard')->with('success','Idea Deleted Successfully!');
    } */

    //Route Model Binding
    public function destroy(Idea $idea)
    {
        if(auth()->id() !== $idea->user_id){
            abort(404);
        }
        $idea->delete();
        return redirect()->route('dashboard')->with('success', 'Idea Deleted Successfully!');
    }
}
