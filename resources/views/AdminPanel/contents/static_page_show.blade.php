@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <div class="container-fluid">

        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}">{{$lang->name}}</a></li>
            @endforeach
        </ul>
        <form action="{{route('static-pages.update',$static_page[0]->id)}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($static_page as $page)
                            @if($lang->id==$page->lang_id)
                                <br/><br/>
                                <input class="form-control" name="title[{{$page->lang_id}}][{{$page->id}}]" type="text"
                                       value="{{$page->title}}"/>
                                <br/><br/>
                                <textarea name="content[{{$page->lang_id}}][{{$page->id}}]" class="summernote">
                                    {{$page->content}}
                                </textarea>
                            @endif
                        @endforeach
                    </div>
                @endforeach
                <input type="submit" class="form-control btn btn-primary" />
            </div>
        </form>
    </div>
@endsection
