@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if($profileType == \App\UserDirectory\Config\Constants::OTHER_PROFILE)
                        @include('dashboard.profile.partial.other_user', array($profile))
                    @else
                        @include('dashboard.profile.partial.current_user', array($profile))
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
