<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

        $postsCount = Cache::remember(
            'count.post.'. $user->id,
            now()->addSeconds(30),
             function () use ($user)
             {
                return $user->posts->count();

             });

        $followersCount = Cache::remember(
            'count.followers.'. $user->id,
            now()->addSeconds(5),
            function () use ($user)
            {
                return $user->profile->followers->count();

            });

        $followingCount = Cache::remember(
            'count.following.'. $user->id,
            now()->addSeconds(5),
            function () use ($user)
            {
                return $user->following->count();

            });

        // $following = $user->following->id;



        return view('profiles.index', compact('user', 'follows', 'postsCount', 'followersCount', 'followingCount'));
    }

    public function followingusers(User $user)
    {
        $following = auth()->user()->following->contains($user->id);
        return view('profile.followingusers', compact('following'));
    }
  

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }



    public function update(Request $reqiest, User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title'=>'required',
            'description'=>'required',
            // 'adress'=>'required',
            'url'=>'url',
            'image'=>'',
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('profile', 'public');
            // dd($imagePath);
            $image = Image::make(public_path("storage/{$imagePath}"))->resize(1000, 1000);
            
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }
}
