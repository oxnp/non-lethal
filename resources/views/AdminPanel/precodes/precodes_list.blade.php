@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <h1>Pre-codes</h1>
    <div class="pads">
        <form action="{{route('purgeEmpty')}}" method="GET" name="purge_codes">
            {{csrf_field()}}
            <input type="submit" class="btn btn-primary btn-md" value="Purge used Pre-codes" />
        </form>
        <form action="{{route('exportPrecodes')}}" method="GET" name="export_codes">
            {{csrf_field()}}
            <input type="submit" class="btn btn-primary btn-md" value="Export Pre-codes" />
        </form>
        <a id="clear_codes" class="btn btn-primary btn-md">Clear selected codes</a>
    </div>
<div class="codes_list" style="margin-bottom: 15px;font-size:18px;">
    Selected codes: <span></span>
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

    <script>
        console.log(localStorage.getItem("ids"));
        if(localStorage.getItem("ids")==null){
            let arr = [];
            localStorage.setItem("ids", JSON.stringify(arr));
        }

        let idArray = JSON.parse(localStorage.getItem("ids"));
        $('.codes_list span').text(JSON.parse(localStorage.getItem("ids")));

        jQuery.map(idArray,function (a) {
            $('input[value="'+a+'"]').attr('checked','checked');
        })

        $('input[name="precode_id"]').each(function () {
            var inp = $(this);
            $(this).change(function () {
               if($(this).is(':checked')){
                   idArray.push($(this).val());
               }else{
                   idArray = jQuery.grep(idArray, function (value) {
                       return value != $(inp).val()
                   })
               }
               localStorage.setItem("ids", JSON.stringify(idArray));
               $('.codes_list span').text(JSON.parse(localStorage.getItem("ids")));
            });
        });

        $('form[name="export_codes"]').submit(function (e) {
            if(idArray.length === 0){
                alert('Choose one product from list!');
                e.preventDefault();
            }else{
                jQuery.map(idArray,function (a) {
                    $('form[name="export_codes"]').append('<input value="' + a + '" type="hidden" name="cid[]"/>');
                })

                var status = confirm("Click OK to continue?");
                if(status == false){
                    $('form[name="export_codes"] input[name^="cid"]').remove();
                    return false;
                }
                else{
                    return true;
                }
            }
        });
        $('a#clear_codes').click(function () {
            localStorage.removeItem("ids");
            window.location.reload();
        });
    </script>
@stop

