@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('dashboard.edit_profile')</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('profile.update') }}">
                            {{ csrf_field() }}

                            {{-- name --}}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Full Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           value="{{ $user->name }}" autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- age --}}
                            <div class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Age</label>

                                <div class="col-md-6">
                                    <input id="age" type="text" class="form-control" name="age"
                                           value="{{ $user->age }}">
                                    @if ($errors->has('age'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('age') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- email address--}}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email"
                                           value="{{ $user->email }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- password --}}
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password">
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- last  update --}}
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">
                                    Last Update : </label>
                                <div class="col-md-6">
                                    {{ $user->updated_at }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success btn-rounded">
                                        Update
                                    </button>
                                    @if( isset($errors) && count($errors) >0)
                                        @include('dashboard.message.error', array('message' => App\UserDirectory\Config\Messages::ERROR_UPDATE))
                                    @endif
                                    @if(isset($update) &&  null !=$update)
                                        @include('dashboard.message.success', array('message' => App\UserDirectory\Config\Messages::SUCCESS_UPDATE))
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
