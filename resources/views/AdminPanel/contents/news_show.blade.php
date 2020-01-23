@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>News item</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('news.update',$news[0]->id)}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div style="overflow: hidden;">
                <div class="col-md-4">
                    <br/><br/>
                    <div style="float: left">
                        <img style="max-height: 90px;margin-right: 15px" src="{{$news[0]['image']}}">
                    </div>
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image" type="file"/>
                    </div>

                </div>
                <div class="col-md-4">
                    <br/><br/>
                    <label>Status</label>
                    <select class="form-control" name="published">
                        <option @if($news[0]['published']==1) selected @endif value="1">Published</option>
                        <option @if($news[0]['published']==0) selected @endif value="0">Unpublished</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <br/><br/>
                    <label>Publish date</label>
                    <input class="form-control" name="created_at" type="date" value="{{date('Y-m-d',strtotime($news[0]['created_at']))}}">
                </div>
            </div>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($news as $page)
                            @if($lang->id==$page->lang_id)
                                <div style="overflow: auto">
                                    <br/><br/>
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input class="form-control" name="title[{{$page->lang_id}}][{{$page->id}}]"
                                               type="text"
                                               value="{{$page->title}}"/>
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Slug</label>
                                        <input class="form-control" name="slug[{{$page->lang_id}}][{{$page->id}}]"
                                               type="text"
                                               value="{{$page->slug}}"/>
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Short text</label>
                                        <textarea rows="7" class="form-control"
                                                  name="short_text[{{$page->lang_id}}][{{$page->id}}]">{{$page->short_text}}</textarea>
                                        <br/><br/>
                                    </div>
                                <!--<div class="col-md-6">
                                    <label>Image</label>
                                    <input class="form-control" name="image[{{$page->lang_id}}][{{$page->id}}]"
                                           type="file" value="{{$page->image}}"/>
                                    <img style="max-height: 90px" src="{{$page->image}}">
                                    <br/><br/>
                                </div>-->
                                </div>
                                <textarea name="content[{{$page->lang_id}}][{{$page->id}}]" class="summernote">
                                    {{$page->content}}
                                </textarea>
                            @endif
                        @endforeach
                    </div>
                @endforeach
                <input type="submit" class="form-control btn btn-primary"/>
            </div>
        </form>
    </div>
@endsection
