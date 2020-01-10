@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro support">
        <h1>Get in touch</h1>
    </section>
    <section id="support">
        <div class="container">
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                We speak English and German.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" method="POST" name="support">
                @csrf
                <div class="form-group row flex-nowrap">
                    <label for="name" class="col-md-2 col-form-label">{{ __('Your name') }} *</label>
                    <div class="col">
                        <input placeholder="Your name" id="name" type="text" class="form-control" name="name" required
                               autocomplete="name"
                               autofocus>
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="email" class="col-md-2 col-form-label">{{ __('Email') }} *</label>
                    <div class="col">
                        <input placeholder="Your email address" id="email" type="email" class="form-control"
                               name="email" required autocomplete="email">
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="product" class="col-md-2 col-form-label">{{ __('Product') }}</label>
                    <div class="col">
                        <select id="product" class="form-control" name="product">
                            <option selected>-- Please choose a product</option>
                            <option value="Video Slave">Video Slave</option>
                            <option value="ADR Master">ADR Master</option>
                            <option value="Snapshot">Snapshot</option>
                            <option value="DAWLink">DAWLink</option>
                            <option value="MIDI Remote">MIDI Remote</option>
                            <option value="Timecode Calculator">Timecode Calculator</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="subject" class="col-md-2 col-form-label">{{ __('Subject') }}</label>
                    <div class="col">
                        <select id="subject" class="form-control" name="subject">
                            <option selected value="Question">Question</option>
                            <option value="Bug report">Bug report</option>
                            <option value="Feature request">Feature request</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="message" class="col-md-2 col-form-label">{{ __('Message') }} *</label>
                    <div class="col">
                        <textarea class="form-control" rows="8" placeholder="Your detailed message" id="message"
                                  name="message" required></textarea>
                    </div>
                </div>
                <div class="form-group row flex-nowrap">
                    <label for="attachment" class="col-md-2 col-form-label">{{ __('Attachment') }}</label>
                    <div class="col">
                        <div class="attach">
                        <label for="attachment">Choose files</label>
                        <span>If you want to send us a screenshot, please attach it here.<br />
                            Supported file formats are: .pdf | .jpg | .png</span>
                        <input type="file" multiple="multiple" name="attachment[]" id="attachment"/>
                        </div>
                        <div class="file_val"></div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="m-auto" type="submit">Submit</button>
                </div>
            </form>
            <div class="alert alert-secondary" role="alert">
                <img src="/images/info_icon.png"> Before sending an inquiry, please be sure to visit the <a
                    href="/knowledge-base" class="alert-link">Knowledge Base</a>
            </div>
        </div>
    </section>
@endsection
