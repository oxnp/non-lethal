@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <form name="delete_buyer" style="float: right" action="{{route('buyers.destroy',$buyer['id'])}}" method="POST">
        {{csrf_field()}}
        <input name="_method" type="hidden" value="DELETE">
        <input type="submit" class="btn btn-danger" value="DELETE BUYER" />
    </form>
    <script>
        $('form[name="delete_buyer"]').submit(function (e) {
            var status = confirm("Click OK to continue?");
            if(status == false){
                return false;
            }
            else{
                return true;
            }
        })
    </script>
    <h1>Buyer</h1>

    <div class="row formgroup">
        <form action="{{route('buyers.update',$buyer['id'])}}" method="POST">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="PUT">
            <div class="col-lg-3 form-group">
                <input type="hidden" value="{{$buyer['id']}}" name="id"/>
                <div class="form-group">
                    <label class="control-label">Assigned user</label>
                    <input class="form-control" disabled="disabled" type="text" value="{{$buyer['user_id']}}" name="user_id"/>
                </div>
                <div class="form-group">
                    <label class="control-label">First name</label>
                    <input class="form-control" type="text" value="{{$buyer['first']}}" name="first"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Last name</label>
                    <input class="form-control" type="text" value="{{$buyer['last']}}" name="last"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Company name</label>
                    <input class="form-control" type="text" value="{{$buyer['company']}}" name="company"/>
                </div><br />
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="text" value="{{$buyer['email']}}" name="email"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Emails BCC</label>
                    <textarea rows="5" class="form-control" type="text" value="{{$buyer['bcc_emails']}}" name="bcc_emails">{{$buyer['bcc_emails']}}</textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input class="form-control" type="text" value="{{$buyer['phone']}}" name="phone"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Website</label>
                    <input class="form-control" type="text" value="{{$buyer['website']}}" name="website"/>
                </div>
            </div>
            <div class="col-lg-3 col-lg-offset-1">
                <div class="form-group">
                    <label class="control-label">Street 1</label>
                    <input class="form-control" type="text" value="{{$buyer['street1']}}" name="street1"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Street 2</label>
                    <input class="form-control" type="text" value="{{$buyer['street2']}}" name="street2"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Postal code/ZIP</label>
                    <input class="form-control" type="text" value="{{$buyer['zip']}}" name="zip"/>
                </div>
                <div class="form-group">
                    <label class="control-label">City</label>
                    <input class="form-control" type="text" value="{{$buyer['city']}}" name="city"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Country</label>
                    <input class="form-control" type="text" value="{{$buyer['country']}}" name="country"/>
                </div>
                <div class="form-group">
                    <label class="control-label">State/Province</label>
                    <input class="form-control" type="text" value="{{$buyer['state']}}" name="state"/>
                </div>
            </div>
            <div class="col-lg-3 col-lg-offset-1">
                <div class="form-group">
                    <label class="control-label">Notes</label>
                    <textarea rows="15" class="form-control summernote" type="text" value="{{$buyer['notes']}}" name="notes">{{$buyer['notes']}}</textarea>
                </div>
            </div>
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
                <a href="{{route('buyers.index')}}" class="btn btn-primary">Close</a>
            </div>
        </form>
    </div>
@stop
