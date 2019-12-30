@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')

@section('content')
<table width="500px">
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Subscription</td>
        <td></td>
    </tr>
@foreach($data_subscriber['subscribers'] as $subscriber)
        <tr>
            <td><a href="{{route('subscribers.show',$subscriber['id'])}}">{{$subscriber['name']}}</a></td>
            <td>{{$subscriber['email']}}</td>
            <td>{{implode(',',$subscriber['group_name'])}}</td>
            <td></td>
        </tr>
@endforeach
</table>

{{$data_subscriber['paginate']->links()}}
@stop
