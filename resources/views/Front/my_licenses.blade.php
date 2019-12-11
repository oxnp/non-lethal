@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro lic">
        <h1>My licenses</h1>
    </section>
    <section id="licenses">
        <div class="container">
            @foreach($licenses as $key=>$value)
                <div class="item">
                    <h2 class="text-center">{{$key}}</h2>
                    <table>
                        <thead>
                        <th>
                            Serial / iLok Code<br/>
                            <span>(Mouseover for status)</span>
                        </th>
                        <th>
                            Type
                        </th>
                        <th>
                            Purchase date
                        </th>
                        <th>
                            Expiry date
                        </th>
                        <th>
                            Upgrade
                        </th>
                        <th>
                            Notes
                        </th>
                        </thead>
                        <tbody>
                        @foreach($value as $license)
                            <tr>
                                <td>
                                    {{$license['serial']}}
                                </td>
                                <td>
                                    {!! $license['type'] !!}
                                </td>
                                <td>
                                    {{$license['purchase_date']}}
                                </td>
                                <td>
                                    {{$license['expire_date']}}
                                </td>
                                <td>
                                    {{$license['select_upgrade']}}
                                </td>
                                <td>
                                    @if(!empty($license['notes']))
                                        <a data-toggle="modal" data-target="#{{$license['serial']}}" href="">
                                            Read more about notes
                                        </a>
                                    @endif
                                </td>
                                @if(!empty($license['notes']))
                                    <div class="modal fade" id="{{$license['serial']}}" tabindex="-1" role="dialog"
                                         aria-labelledby="{{$license['serial']}}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    {!! $license['notes'] !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="total text-center">
                        Total license count: {{count($value)}}
                    </div>
                    <a class="dlinks">
                        Download Links
                    </a>
                </div>
            @endforeach
            <div class="off_act text-center">
                <h2>Activate code</h2>
                <div class="activate_desc">
                    If you recived a pre-activation code from your local reseller,<br/>
                    please click below to start product activation
                </div>
                <a data-toggle="modal" data-target="#activation" class="activate">Activate code</a>
            </div>
            <div class="modal fade" id="activation" tabindex="-1" role="dialog"
                 aria-labelledby="activation" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h2>Activate code</h2>
                            <div class="activate_desc">
                                If you recived a pre-activation code from your local reseller,<br/>
                                please click below to start product activation
                            </div>
                            <form name="activate" method="POST" action="javascript:void(0)">
                                <div>
                                    <input name="code" type="text" required="required" placeholder="Enter or paste code..." />
                                </div>
                                <button class="activate">Activate</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
