@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('dashboard.title')
                        {{ json_decode(Auth::user())->name }}
                    </div>

                    <div class="panel-body">
                        This is your home feel free to test change every thing
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
