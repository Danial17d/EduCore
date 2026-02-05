@if(auth()->check())
    @include('courses.partials.auth-view-courses')
@else
    @include('courses.partials.guest-view-courses')
@endif


