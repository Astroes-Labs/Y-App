<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use Illuminate\Http\Request;
use App\Models\Idea;
use Illuminate\Support\Facades\Gate;

class IdeaController extends Controller
{
    
    public function show(Idea $idea)
    {

        return view('ideas.show',[
            'idea' => $idea,
            
            'viewing' => true,
        ]);
    }
    public function edit(Idea $idea)
    {
        /* if(auth()->id() !== $idea->user_id){
            abort(404);
        } */
        
        Gate::authorize('idea.edit', $idea);
        $editing = true;
        return view('ideas.show',compact('idea', 'editing'));
    }
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        /* if(auth()->id() !== $idea->user_id){
            abort(404);
        } */
       /*  request()->validate([
            'content' => 'required|min:3|max:240'
        ]);
        $idea->content = request()->get('content', '');
        $idea->save(); */

        //OR
        
        //gates permissions
        //Gate::authorize('idea.edit', $idea);

        //policy permissions
        Gate::authorize('update', $idea);

        
        $validated = $request->validated();
        /* 
        $validated = request()->validate([
            'content' => 'required|min:3|max:240'
        ]); */

        $idea->update($validated);

        return redirect()->route('ideas.show',$idea->id)->with('success',"Idea updated successfully!");
    }
    public function store(CreateIdeaRequest $request)
    {
        //dump();
      /*   $validated = request()->validate([
            'content' => 'required|min:3|max:240'
        ]); */

        $validated = $request->validated();

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
       /*  if(auth()->id() !== $idea->user_id){
            abort(404);
        } */

        //gates permissions
        //Gate::authorize('idea.delete', $idea);

        //policy permissions
        Gate::authorize('delete', $idea);
        $idea->delete();
        return redirect()->route('dashboard')->with('success', 'Idea Deleted Successfully!');
    }
}
