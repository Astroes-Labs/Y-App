<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $ideas = $user->ideas()->paginate(5);
        return view('users.show', compact('user', 'ideas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        
        Gate::authorize('update', $user);

        $edit = true;
        $ideas = $user->ideas()->paginate(5);

        return view('users.edit', compact('user', 'edit', 'ideas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        Gate::authorize('update', $user);

       /*  $validated = request()->validate([
            'name' => 'required|min:3|max:40',
            'bio' => 'nullable|min:1|max:255',
            'image' => 'image'
        ]); */

        $validated = $request->validated();

        if($request->has('image')){
            $imagePath = $request->file('image')->store('profile','public');
            $validated['image'] = $imagePath;
            Storage::disk('public')->delete($user->image ?? '');
        }
        $user->update($validated);

        return redirect()->route('profile');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function profile()
    {
        return $this->show(auth()->user());
    }
}
