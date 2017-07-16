<div class="panel-heading">@lang('dashboard.view_profile')</div>

<div class="panel-body">
    <p>Your Information </p>
    <ul class="list-group">
        <li class="list-group-item list-group-item-success">Name : {{$profile->name}}</li>
        <li class="list-group-item list-group-item-action">Age : {{$profile->age}}</li>
        <li class="list-group-item list-group-item-action">Email : {{$profile->email}}</li>
        <li class="list-group-item list-group-item-action">Join At : {{$profile->created_at}}</li>
    </ul>
</div>