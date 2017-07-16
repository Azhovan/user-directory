<div class="panel-heading">@lang('dashboard.view_others')</div>

<div class="panel-body">
    <p> Hi I am <strong>{{$profile->name}}</strong> !</p>
    <p>Welcome to my page . Here is all information you wanna see about me </p>
    <ul class="list-group">
        <li class="list-group-item list-group-item-success">Name : {{$profile->name}}</li>
        <li class="list-group-item list-group-item-action">Age : {{$profile->age}}</li>
        <li class="list-group-item list-group-item-action">Email : {{$profile->email}}</li>
        <li class="list-group-item list-group-item-action">Join At : {{$profile->created_at}}</li>
    </ul>
</div>