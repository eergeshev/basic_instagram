@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row "><h2><a href="/profile/{{$user->id}}" class="">{{ $user->username }} </a></h2></div>
    @foreach($followingusers as $followinguser);

            <div class="row">
                <div class="col-6 offset-3">
                    <a href="/profile/{{$post->user->id}}">
                        {{$followinguser}}
                    </a>
                </div>
            </div>
            

    @endforeach
  

</div>
@endsection
