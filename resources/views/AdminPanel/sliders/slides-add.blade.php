@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('sliders.store')}}"
              method="POST">
            {{csrf_field()}}
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">

                        <div class="col-md-12">
                            <label>Title</label>
                            <input class="form-control" name="title[{{$lang->id}}]"
                                   type="text"
                                   value=""/>
                            <br/><br/>
                        </div>
                        <div class="col-md-12">
                            <label>Subtitle</label>
                            <input class="form-control" name="sub_title[{{$lang->id}}]"
                                   type="text"
                                   value=""/>
                            <br/><br/>
                        </div>

                    </div>
                @endforeach
                <div class="col-md-12">
                    <label>Link</label>
                    <input class="form-control" name="link" type="text" value=""/>
                </div>
                <div class="col-md-12">
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image"
                               type="file"/>
                    </div>
                </div>

            </div>
            <div class="col-md-12" style="margin-top:30px">
                <input type="submit" class="form-control btn btn-primary"/>
            </div>
        </form>
    </div>
@stop
