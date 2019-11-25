@extends('layouts.app-admin')
@extends('layouts.app-admin-leftsidebar')
@extends('layouts.app-admin-header')
@section('content')
    <div class="row formgroup">
        <form action="{{route('buyers.store')}}" method="POST">
            {{csrf_field()}}
            <div class="col-lg-3 form-group">
                <div class="form-group">
                    <label class="control-label">First name</label>
                    <input class="form-control" type="text" value="" name="first"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Last name</label>
                    <input class="form-control" type="text" value="" name="last"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Company name</label>
                    <input class="form-control" type="text" value="" name="company"/>
                </div><br />
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input class="form-control" type="text" value="" name="email"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Emails BCC</label>
                    <textarea rows="5" class="form-control" value="" type="text"  name="bcc_emails"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input class="form-control" type="text" value="" name="phone"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Website</label>
                    <input class="form-control" type="text" value="" name="website"/>
                </div>
            </div>
            <div class="col-lg-3 col-lg-offset-1">
                <div class="form-group">
                    <label class="control-label">Street 1</label>
                    <input class="form-control" type="text" value="" name="street1"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Street 2</label>
                    <input class="form-control" type="text" value="" name="street2"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Postal code/ZIP</label>
                    <input class="form-control" type="text" value="" name="zip"/>
                </div>
                <div class="form-group">
                    <label class="control-label">City</label>
                    <input class="form-control" type="text" value="" name="city"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Country</label>
                    <input class="form-control" type="text" value="" name="country"/>
                </div>
                <div class="form-group">
                    <label class="control-label">State/Province</label>
                    <input class="form-control" type="text" value="" name="state"/>
                </div>
            </div>
            <div class="col-lg-3 col-lg-offset-1">
                <div class="form-group">
                    <label class="control-label">Notes</label>
                    <textarea rows="15" class="form-control summernote" type="text" value="" name="notes"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <input class="btn btn-primary" type="submit">
            </div>
        </form>
    </div>
@stop
