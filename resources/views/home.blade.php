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
                        <div class="col-lg-12">
                            <div class="input-group">
                                <div class="h4-responsive">You Can Search For Making New Friends !</div>
                                <input type="text" class="form-control search-input" placeholder="Search with name | email | age ..." autofocus>
                                <input type="hidden" class="_token" name="_token" value="{{csrf_token()}}">
                                <span class="input-group-btn">
                                    <button class="btn btn-success search" type="button">@lang('dashboard.search')</button>
                                </span>
                            </div>
                        </div>
                        <br>
                        <div class="search-result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
