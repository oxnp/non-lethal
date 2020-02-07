@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Slide</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('sliders.update',$slide[0]->id)}}"
              method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($slide as $page)
                            @if($lang->id==$page->lang_id)
                                <div class="col-md-12">
                                    <label>Title</label>
                                    <input class="form-control" name="title[{{$page->lang_id}}][{{$page->id}}]"
                                           type="text"
                                           value="{{$page->title}}"/>
                                    <br/><br/>
                                </div>
                                <div class="col-md-12">
                                    <label>Subtitle</label>
                                    <input class="form-control" name="sub_title[{{$page->lang_id}}][{{$page->id}}]"
                                           type="text"
                                           value="{{$page->sub_title}}"/>
                                    <br/><br/>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
                <div class="col-md-12">
                    <label>Link</label>
                    <input class="form-control" name="link"
                           type="text"
                           value="{{$slide[0]['link']}}"/>

                </div>
                <div class="col-md-12">
                    <br/><br/>
                    @if($slide[0]['image']!='')
                        <div style="float: left">
                            <img style="max-height: 90px;margin-right: 15px" src="{{$slide[0]['image']}}">
                        </div>
                    @endif
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image"
                               type="file"/>
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <input type="hidden" name="redirect" value="0" />
                <input class="btn btn-primary" type="submit" value="Save">
                <a class="btn btn-primary" id="redir">Save and close</a>
                <script>
                    $('a#redir').click(function () {
                        $('input[name="redirect"]').val('1');
                        $(this).closest('form').submit();
                    })
                </script>
                <a href="{{route('sliders.index')}}" class="btn btn-primary">Close</a>
            </div>
        </form>
    </div>
@stop
