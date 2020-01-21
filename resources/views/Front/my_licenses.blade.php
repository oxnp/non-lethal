@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro lic">
        <h1>My licenses</h1>
    </section>
    <section id="licenses">
        <div class="container-fluid" id="license-tables">
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
                        @if(Auth::user()->role_id == 1)
                            <th>
                                Notes
                            </th>
                        @endif
                        </thead>
                        <tbody>
                        @foreach($value['licenses'] as $license)

                            <tr>
                                <td>
                                    <code data-html="true" data-toggle="tooltip" data-placement="top"
                                          title="{{$license['status_title']}}"
                                          class="hasTip {{$license['status']}}">{{$license['serial']}}{{$license['ilok']}}</code>
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
                                    @if(is_array($license['upgrade_targets']))
                                        <select id="{{$license['select_id']}}_upgrade_select" class="upgrade_select"
                                                name="{{$license['select_id']}}_upgrade_select"
                                                data-upgradeserial="{{$license['serial']}}"
                                                data-upgradeilok="{{$license['ilok']}}">
                                            @foreach($license['upgrade_targets'] as $target)
                                                <option @if(!empty($target['disable'])) disabled="disabled"
                                                        @endif value="{{$target['value']}}">{{$target['text']}}</option>
                                            @endforeach
                                        </select>
                                    @else {!! $license['upgrade_targets']!!}
                                    @endif
                                </td>

                                <td>
                                    <a data-toggle="modal"
                                       data-target="#{{$license['serial']}}{{$license['ilok']}}" href="">
                                        Notes
                                    </a>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @foreach($value['licenses'] as $license)
                        <div class="modal fade" id="{{$license['serial']}}{{$license['ilok']}}"
                             tabindex="-1" role="dialog"
                             aria-labelledby="{{$license['serial']}}{{$license['ilok']}}"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form name="noteform" method="POST"
                                              action="{{route('user-notes')}}">
                                            {{csrf_field()}}
                                            <input type="hidden" name="license_id"
                                                   value="{{$license['license_id']}}"/>
                                            <textarea class="form-control" name="user_notes"
                                                      value="{{$license['user_notes']}}">{{$license['user_notes']}}</textarea>
                                            <input class="btn btn-primary" type="submit"
                                                   value="Edit notes"/>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="total text-center">
                        Total license count: {{count($value)}}
                    </div>
                    <a class="dlinks">
                        Download Links
                    </a>

                    <div class="download_block">
                        <div class="container-fluid latest">
                            <div class="head_row row align-items-center">
                                <div class="col-md-8">
                                    Most recent
                                </div>
                                <div class="col-md-4">
                                    Notes
                                </div>
                            </div>
                            @foreach($value['downloads_links']['latest'] as $item)
                                <div class="body_row row">
                                    <div class="col-md-8 justify-content-between">
                                        <div class="prod_title">
                                            {{$item['zip']['name']}}
                                        </div>
                                        <div class="d_link">
                                            <a target="_blank" href="{{$item['zip']['link']}}">Download</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="release" target="_blank" href="{{$item['changelog']}}">Release notes</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="container-fluid legacy">
                            <div class="head_row row align-items-center">
                                <div class="col-md-8">
                                    Legacy
                                </div>
                                <div class="col-md-4">
                                    Notes
                                </div>
                            </div>
                            @foreach($value['downloads_links']['legacy'] as $item)
                                <div class="body_row row">
                                    <div class="col-md-8 justify-content-between">
                                        <div class="prod_title">
                                            {{$item['zip']['name']}}
                                        </div>
                                        <div class="d_link">
                                            <a target="_blank" href="{{$item['zip']['link']}}">Download</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="release" target="_blank" href="{{$item['changelog']}}">Release notes</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
                <div class="modal fade" id="release" tabindex="-1" role="dialog"
                     aria-labelledby="release" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body text-center">

                            </div>
                        </div>
                    </div>
                </div>
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
                            <form name="activate" method="POST" action="{{route('fulfillment')}}">
                                {{csrf_field()}}
                                <div>
                                    <input type="hidden" value="{{Auth::user()->email}}" name="email"/>
                                    <input type="hidden" value="{{Auth::ID()}}" name="user_id"/>
                                    <input name="code" type="text" required="required"
                                           placeholder="Enter or paste code..."/>
                                </div>
                                <div class="response"></div>
                                <button class="activate">Activate</button>
                            </form>
                            <script>
                                $('form[name="activate"]').submit(function (e) {
                                    e.preventDefault();
                                    var form = $(this);
                                    var url = form.attr('action');
                                    $.ajax({
                                        method: 'POST',
                                        url: url,
                                        data: form.serialize(),
                                        success: function (data) {
                                            $('.response').text(data.text);
                                            setTimeout(function () {
                                                $('.response').text('');
                                            }, 5000)
                                            if (data.status == true) {
                                                setTimeout(function () {
                                                    window.location.reload();
                                                }, 3000)
                                            }
                                        }
                                    })
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })
        $(function () {
            $('[data-toggle="modal"]').click(function () {
                jQuery(jQuery(this).attr('data-target')).modal('show');
            })
        })
        $('a.release').click(function (e) {
            var src = $(this).attr('href');
            console.log(src);
            e.preventDefault();
            $('#release').modal('show');
            $('#release .modal-body').load(encodeURI(src));

        })
    </script>
@endsection
