@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if(false == $friends)
                        <p class="green-text">{{ \App\UserDirectory\Config\Constants::EMPTY_FRIEND_LIST  }}</p>
                    @else
                        <div class="panel-heading">@lang('dashboard.friends')</div>

                        <div class="panel-body">
                            <p>Your Friends </p>
                            @foreach($friends as $friend)
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-success">Name : {{$friend->name}}</li>
                                    <li class="list-group-item list-group-item-action">Age : {{$friend->age}}</li>
                                    <li class="list-group-item list-group-item-action">Email : {{$friend->email}}</li>
                                </ul>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
