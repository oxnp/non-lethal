@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Add knowledge base category</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('knowledge-base-categories.store')}}"
              method="POST">
            {{csrf_field()}}
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                                <div class="row" style="overflow: auto">
                                    <br/><br/>
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input class="form-control" name="title[{{$lang->id}}]"
                                               type="text" />
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Slug</label>
                                        <input class="form-control" name="slug[{{$lang->id}}]"
                                               type="text" />
                                        <br/><br/>
                                    </div>
                                </div>
                                <textarea name="content[{{$lang->id}}]" class="summernote">
                                </textarea>
                    </div>
                @endforeach
                <input type="submit" class="form-control btn btn-primary"/>
            </div>
        </form>
    </div>
@endsection
