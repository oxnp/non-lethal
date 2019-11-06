@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <table width="1300px">
        <tr>
            <td>ID</td>
            <td>Pre-Activation code</td>
            <td>Product code</td>
            <td>Product name</td>
            <td>Type</td>
            <td>License system</td>
            <td>Details</td>
            <td>Reference</td>
        </tr>
    @foreach($precodes as $precode)
            <tr>
                <td>{{$precode->id}}</td>
                <td>{{substr(chunk_split($precode->precode,5,'-'),0,-1)}} {{$precode->used}}</td>
                <td>{{$precode->code}}</td>
                <td>{{$precode->name}}</td>
                <td>
                    @php
                    $licenseTypes = array(
                        0 => 'feature',
                        1 => 'full',
                        2 => 'upgrade',
                        3 => 'temp'
                    );
                    echo ucfirst($licenseTypes[intval($precode->type)]);
                    @endphp
                </td>
                <td>
                @php
                    $license = array(
                        1 => 'NLA Licensing',
                        2 => 'PACE iLok'
                    );
                    echo ucfirst($license[intval($precode->licsystem)]);
                @endphp
                </td>
                <td>
                    @php $itemData = json_decode($precode->data);  @endphp
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
                    @endphp
                </td>
                <td>{{$precode->reference}}</td>
            </tr>
    @endforeach
    </table>
    {{$precodes->links()}}
@stop

