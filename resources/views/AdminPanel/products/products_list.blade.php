@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <table width="1100px">
        <tr>
            <td>ID</td>
            <td>Publish</td>
            <td>Name</td>
            <td>Access Level</td>
            <td>Upgradeable from</td>
            <td>Product code</td>
            <td>Paddle PID/UPID</td>
            <td>Major Ver.</td>
        </tr>
  @foreach($products as $product)
            <tr>
                <td>{{$product['id']}}</td>
                <td>{{$product['published']}}</td>
                <td><a href="{{route('products.show', $product['id'])}}">{{$product['name']}}</a></td>
                <td>{{$product['access']}}</td>
                <td>@foreach($product['upgradeable_products'] as $upgradeable) {{$upgradeable}} <br> @endforeach</td>
                <td>{{$product['code']}}</td>
                <td>{{$product['paddle_pid']}} / {{$product['paddle_upgrade_pid']}}</td>
                <td>{{$product['default_majver']}}.x</td>
            </tr>
  @endforeach
    </table>
    {{$products_links->links()}}
@stop
