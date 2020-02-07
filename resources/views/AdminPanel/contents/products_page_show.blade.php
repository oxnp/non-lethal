@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@section('content')
    <h1>Product page</h1>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            @foreach($langs as $lang)
                <li @if($loop->index==0)class="active"@endif><a data-toggle="tab"
                                                                href="#{{$lang->locale}}"> {{$lang->name}} </a></li>
            @endforeach
        </ul>
        <form enctype="multipart/form-data" action="{{route('products-pages.update',$product_page[0]->id)}}"
              method="POST">
            {{csrf_field()}}
            <input type="hidden" value="PUT" name="_method"/>
            <div style="overflow: hidden;">
                <div class="col-md-6">
                    <br/><br/>
                    <div style="float: left">
                        <img style="max-height: 90px;margin-right: 15px" src="{{$product_page[0]['image']}}">
                    </div>
                    <div style="overflow:auto;">
                        <label>Image</label>
                        <input class="form-control" name="image"
                               type="file"/>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                @foreach($langs as $lang)
                    <div id="{{$lang->locale}}" class="tab-pane @if($loop->index==0) fade in active @endif">
                        @foreach($product_page as $page)
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
                                    <div class="col-md-6">
                                        <label>Category</label>
                                        <select class="form-control"
                                                name="category_id[{{$page->lang_id}}][{{$page->id}}]">
                                            @foreach($categories as $cat)
                                                @if($lang->id==$cat->lang_id)
                                                    <option @if($page->category_id==$cat->id) selected
                                                            @endif value="{{$cat->id}}"
                                                            data-group="{{$cat->relation}}">{{$cat->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <br/><br/>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Subtitle</label>
                                        <input class="form-control" name="sub_title[{{$page->lang_id}}][{{$page->id}}]"
                                               type="text"
                                               value="{{$page->sub_title}}"/>
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
                        <a href="{{route('products-pages.index')}}" class="btn btn-primary">Close</a>
                    </div>
            </div>
        </form>
    </div>
@endsection
