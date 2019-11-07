@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')

    <div class="pads">
        <form name="purge_codes">
            <input type="submit" class="btn btn-primary btn-md" value="Purge used Pre-codes" />
        </form>
        <form name="export_codes">
            <input type="submit" class="btn btn-primary btn-md" value="Export Pre-codes" />
        </form>
    </div>


    <div class="list">
        <div class="list_head">
            <div class="col-md-1">ID</div>
            <div class="col-md-2">Pre-Activation code</div>
            <div class="col-md-1">Product code</div>
            <div class="col-md-2">Product name</div>
            <div class="col-md-1">Type</div>
            <div class="col-md-1">License system</div>
            <div class="col-md-2">Details</div>
            <div class="col-md-2">Reference</div>
        </div>
        <div class="list_body">
            @foreach($precodes as $precode)
                <div class="item">
                    <div class="col-md-1 idcol"><input type="checkbox" value="{{$precode['id']}}" name="precode_id" />{{$precode->id}}</div>
                    <div class="col-md-2">{{substr(chunk_split($precode->precode,5,'-'),0,-1)}}
                    <?php if($precode->used == 1){?><i class="fas fa-check"></i><?php }?></div>
                    <div class="col-md-1">{{$precode->code}}</div>
                    <div class="col-md-2">{{$precode->name}}</div>
                    <div class="col-md-1"> @php
                            $licenseTypes = array(
                                0 => 'feature',
                                1 => 'full',
                                2 => 'upgrade',
                                3 => 'temp'
                            );
                            echo ucfirst($licenseTypes[intval($precode->type)]);
                        @endphp</div>
                    <div class="col-md-1">@php
                            $license = array(
                                1 => 'NLA Licensing',
                                2 => 'PACE iLok'
                            );
                            echo ucfirst($license[intval($precode->licsystem)]);
                        @endphp</div>
                    <div class="col-md-2">@php $itemData = json_decode($precode->data);  @endphp
                        @php
                            switch ($precode->type) {
                                case 0:
                                     echo 'Feature Name: ' .$itemData->feature_name;
                                    break;
                                case 3:
                                    echo 'Duration: ' .$itemData->temp_days. ' days';
                                    break;

                                default:
                            }
                        @endphp</div>
                    <div class="col-md-2">{{$precode->reference}}</div>
                </div>
            @endforeach
        </div>
    </div>
    {{$precodes->links()}}
@stop

