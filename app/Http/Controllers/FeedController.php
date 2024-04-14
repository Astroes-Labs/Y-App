<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;

class FeedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $followingIDs = $user->followings()->pluck('user_id');
        //dd($followingIDs);
        $ideas = Idea::whereIn('user_id', $followingIDs)->latest();

        if(request()->has('search')){
             $ideas = $ideas->where('content','like','%'.request()->get('search','').'%');
        }
         //dump(Idea::all());
         
 
         return view('dashboard',[
             'ideas' => $ideas->paginate(5),
         ]); 
    }
}
