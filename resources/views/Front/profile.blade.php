@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro lic">
        <h1>My Profile</h1>
    </section>
    <section class="profile">
        <div class="container">
            <form action="{{route('profile-update')}}" method="POST">
                <input type="hidden" name="_method" value="PUT" />
                {{csrf_field()}}
                <div class="prof_group">
                    <div class="group_title">
                        My Profile
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="name">Name</label>
                        </div>
                        <div class="col-md-4">
                            <input name="name" value="{{$user->name}}" type="text" placeholder="Name" />
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="username">Username *</label>
                        </div>
                        <div class="col-md-4">
                            <input name="username" value="{{$user->username}}" required="required" type="text" placeholder="Username" />
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="password">Password</label>
                        </div>
                        <div class="col-md-4">
                            <input name="password" type="password" placeholder="Password" />
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="password_confirmation">Confirm Password</label>
                        </div>
                        <div class="col-md-4">
                            <input name="password_confirmation" type="password" placeholder="Confirm Password" />
                        </div>
                    </div>
                </div>
                <div class="prof_group">
                    <div class="group_title">
                        User Profile
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="last">Last name *</label>
                        </div>
                        <div class="col-md-4">
                            <input name="last" value="{{$buyers['last']}}" required="required" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="first">First name *</label>
                        </div>
                        <div class="col-md-4">
                            <input name="first" value="{{$buyers['first']}}" required="required" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="company">Company</label>
                        </div>
                        <div class="col-md-4">
                            <input name="company" value="{{$buyers['company']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="street1">Street (1)</label>
                        </div>
                        <div class="col-md-4">
                            <input name="street1" value="{{$buyers['street1']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="street2">Street (2)</label>
                        </div>
                        <div class="col-md-4">
                            <input name="street2" value="{{$buyers['street2']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="zip">Postal code/ZIP</label>
                        </div>
                        <div class="col-md-4">
                            <input name="zip" value="{{$buyers['zip']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="city">City</label>
                        </div>
                        <div class="col-md-4">
                            <input name="city" value="{{$buyers['city']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="country">Country</label>
                        </div>
                        <div class="col-md-4">
                            <input name="country" value="{{$buyers['country']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="state">State/Province</label>
                        </div>
                        <div class="col-md-4">
                            <input name="state" value="{{$buyers['state']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="phone">Phone</label>
                        </div>
                        <div class="col-md-4">
                            <input name="phone" value="{{$buyers['phone']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                    <div class="row align-items-center d-none">
                        <div class="col-md-3">
                            <label for="website">Website</label>
                        </div>
                        <div class="col-md-4">
                            <input name="website" value="{{$buyers['website']}}" type="text" placeholder="No information entered" />
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="m-auto" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </section>
@endsection
