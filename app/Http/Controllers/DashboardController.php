<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;

class DashboardController extends Controller
{
    public function index(){

        //preview of your email
        //return new WelcomeEmail(auth()->user());
        $ideas = Idea::orderBy('created_at', 'DESC');

       if(request()->has('search')){
            $ideas = $ideas->where('content','like','%'.request()->get('search','').'%');
       }
        //dump(Idea::all());
        

        return view('dashboard',[
            'ideas' => $ideas->paginate(5),
        ]);
    }
}
