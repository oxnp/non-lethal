@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Product category</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('products-pages-categories.update',$category[0]->id)}}"
              method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($category as $page)
                            @if($lang->id==$page->lang_id)
                                <div style="overflow: auto">
                                    <br/><br/>
                                    <div class="col-md-6">
                                        <label>Title</label>
                                        <input class="form-control" name="name[{{$page->lang_id}}][{{$page->id}}]"
                                               type="text"
                                               value="{{$page->name}}"/>
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Subtitle</label>
                                        <input type="text" value="{{$page->sub_name}}" class="form-control" name="sub_name[{{$page->lang_id}}][{{$page->id}}]"/>
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
                        <a href="{{route('products-pages-categories.index')}}" class="btn btn-primary">Close</a>
                    </div>
            </div>
        </form>
    </div>
@endsection
