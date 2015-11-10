@extends('layouts.page')

@section('content')
<div class="row m-t">
    <div class="col-md-12">
        <h1>Administration Panel</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table">            
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ title_case( $user->name) }}</td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td>{{  $user->email }}</td>
        </tr>
        <tr>
            <th>Admin</th>
            <td> @if($user->rights > 0)
                    yes
                @else
                    no
                @endif</td>
        </tr>
        </table>
    </div>
</div>
@endsection
