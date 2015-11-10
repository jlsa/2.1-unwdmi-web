@extends('layouts.page')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
        <h1>Administration Panel</h1>
    </div>
</div>
<div class="row m-t">
    <div class="col-md-12">
        <a href="{{ action('Admin\AdminPanelController@create') }}">Create user account</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Admin</th>
                </tr>
            </thead>
            @foreach ($users as $user)
            <tr>
                <td>
                    <a href="{{ action('Admin\AdminPanelController@show', $user) }}">
                        {{ $user->id }}
                    </a>
                </td>
                <td>
                    {{ title_case( $user->name) }}
                </td>
                <td>
                    {{  $user->email }}
                </td>
                <td>
                @if($user->rights > 0)
                    yes
                @else
                    no
                @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
