@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>User story</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('user-stories.update',$user_story[0]->id)}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div style="overflow: hidden;">
                <div class="col-md-6">
                    <br/><br/>
                    <div style="float: left">
                        <img style="max-height: 90px;margin-right: 15px" src="{{$user_story[0]['image']}}">
                    </div>
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image" type="file"/>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($user_story as $page)
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
                                        <label>Subtitle</label>
                                        <input class="form-control" name="sub_title[{{$page->lang_id}}][{{$page->id}}]"
                                               type="text"
                                               value="{{$page->sub_title}}"/>
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
                        <a href="{{route('user-stories.index')}}" class="btn btn-primary">Close</a>
                    </div>
            </div>
        </form>
    </div>
@endsection
