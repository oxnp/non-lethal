@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')



    <div class="pads">
        <form action="{{route('removeIlokCodes')}}" method="POST" name="delete_codes">
            {{csrf_field()}}
            <input type="submit" class="btn btn-primary btn-md" value="Delete checked" />
        </form>
    </div>
    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-3">iLok codes</div>
            <div class="col-md-2">Product code</div>
            <div class="col-md-3">Product name</div>
            <div class="col-md-3">Batch time</div>
        </div>
        <div class="list_body">
            @foreach($ilok_codes as $code)
                <div class="item">
                    <div class="col-md-1 idcol"><input type="checkbox" value="{{$code['id']}}" name="precode_id" /> {{$code['id']}}</div>
                    <div class="col-md-3">{{$code['ilok_code']}}
                        <?php if($code->used == 1){?><i class="fas fa-check"></i><?php }?></div>
                    <div class="col-md-2">{{$code['code']}}</div>
                    <div class="col-md-3">{{$code['name']}}</div>
                    <div class="col-md-3">{{$code['batchtime']}}</div>
                </div>
                @endforeach
        </div>
    </div>
    {{$ilok_codes->links()}}

@stop
