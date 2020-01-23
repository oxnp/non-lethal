@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Add news</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('news.store')}}" method="POST">
            {{csrf_field()}}
            <div style="overflow: hidden;">
                <div class="col-md-4">
                    <br/><br/>
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image" type="file"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <br/><br/>
                    <label>Status</label>
                    <select class="form-control" name="published">
                        <option selected value="1">Published</option>
                        <option value="0">Unpublished</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <br/><br/>
                    <label>Publish date</label>
                    <input class="form-control" type="date" name="created_at" value="{{date('Y-m-d')}}" />
                </div>
            </div>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                                <div style="overflow: auto">
                                    <br/><br/>
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input class="form-control" name="title[{{$lang->id}}]" type="text" />
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Slug</label>
                                        <input class="form-control" name="slug[{{$lang->id}}]" type="text" />
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Short text</label>
                                        <textarea rows="7" class="form-control"
                                                  name="short_text[{{$lang->id}}]"></textarea>
                                        <br/><br/>
                                    </div>
                                </div>
                                <textarea name="content[{{$lang->id}}]" class="summernote"></textarea>
                    </div>
                @endforeach
                <input type="submit" class="form-control btn btn-primary"/>
            </div>
        </form>
    </div>
@endsection
