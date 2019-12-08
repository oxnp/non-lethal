@extends('layouts.app-front')
@section('app-front-content')
    <section class="intro pcat">
        <h1>{{$product_data[0]['title']}}</h1>
        <div class="subtitle">
            {{$product_data[0]['sub_title']}}
        </div>
    </section>
    <section id="pcat" class="cat_{{$product_data[0]['id']}}">
        <div class="gbg">
            <div class="container text-center">
                <div>
                    <img src="/images/vs4.png" class="intro_img">
                </div>
                <p>
                    Video Slave 4 is the industry standard application for synchronized movie playback for your DAW. Let
                    your DAW handle the audio and use Video Slave do what it is best at and do all the heavy lifting
                    with video effortlessly. Our video engine supports a wide range of video codecs and containers so
                    you don't need to worry about platform-specific video anymore and can focus on the creative process.
                    Video Slave's feature set is designed for composers and audio professionals alike.
                </p>
                <div>
                    <a href="" class="bl_but">Download</a>
                    <a href="" class="buy_but">Buy Now</a>
                </div>
            </div>
        </div>
        <div class="whbg">
            <div class="container">
                <h2 class="text-center">Main features</h2>
                <div class="features row">
                    <div class="col-lg-3">
                        <div class="f_item">
                            <div class="f_img">
                                <img src="/images/feature1.png">
                            </div>
                            <div class="name">
                                Synchronized Playback - Simple and Powerful
                            </div>
                            <p>Video Slave offers seamless playback of movies in sync to MIDI Timecode. It works with
                                all major DAWs including Pro Tools, Logic Pro X, Nuendo, Cubase and Studio One - or any
                                other device that can generate timecode as a source.</p>
                            <p>Video Slave's sync engine provides stunningly fast locking times and tight sync
                                guaranteed: in many cases, it's better than using the video engine built into your
                                DAW.</p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="f_item">
                            <div class="f_img">
                                <img src="/images/feature2.png">
                            </div>
                            <div class="name">
                                Playback Engine with GPU-supported Rendering
                            </div>
                            <p>Leverage the power of Video Slave to playback all today's commonly used codecs including
                                ProRes, H.264, and AVC Intra. The supported container types include QuickTime and
                                others.</p>
                            <p>Stop wasting time transcoding movie clips to a format preferred by your DAW. Just drop
                                the video clip into Video Slave, and it does the rest.</p>
                            <p>Video Slave 4 gives exceptional video playback and saves you valuable time.</p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="f_item">
                            <div class="f_img">
                                <img src="/images/feature3.png">
                            </div>
                            <div class="name">
                                Video Device Support with Unrivaled Flexibility
                            </div>
                            <p>Don't be tied to simple playback through your computer monitor. Video Slave also supports
                                a range of Blackmagic Design devices including their DeckLink, UltraStudio and Intensity
                                products.</p>
                            <p>Video Slave displays the video on all attached devices as well as the inbuilt player view
                                at the same time and ensures that all the displays are in perfect sync. Also, if you're
                                using a projector, Video Slave offers a handy projection delay compensation.</p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="f_item">
                            <div class="f_img">
                                <img src="/images/feature4.png">
                            </div>
                            <div class="name">
                                Customizable Movie Export
                            </div>
                            <p>An essential part of any workflow is the ability to export movies for different
                                scenarios. Ever needed to export a movie with an added piece of music for the director?
                                Alternatively, export a video with your programmed visual cues to send to the scoring
                                stage?</p>
                            <p>Now you can with Video Slave's flexible movie exporting features. Video Slave 4 offers
                                you the flexibility to select output options of video and audio codec, include or remove
                                audio tracks, and export range.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gbg">
            <div class="container">
                <div class="story row align-items-center">
                    <div class="col-md-7">
                        <img src="/images/Doucet_Studio.jpg">
                    </div>
                    <div class="col-md-5">
                        <div class="title">
                            Christopher Doucet
                        </div>
                        <div class="subtitle">
                            Composer for film and TV
                        </div>
                        <div class="desc">
                            <p>I think there are certain things that just make sense, and end up becoming the industry
                                standard. [...] Video Slave is kind of the same thing for me; I personally think it’s
                                going to be a must-have if you’re working to video. Especially with all the different
                                file formats and video encoding; all the different variables that can happen."</p>
                        </div>
                        <a class="readmore" href="#">Read full story <img src="/images/blue_arr.png"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="whbg">
            <div class="container">
                <h2>Other features</h2>

                <div class="other">
                    <div class="item row align-items-center">
                        <div class="col-md-4">
                            <img src="/images/other1.jpg">
                        </div>
                        <div class="col-md-8">
                            <div class="title">
                                Overlays to transform your daily work
                            </div>
                            <div class="minidesc">
                                Video Slave 4 is the industry standard application for synchronized movie playback for
                                your
                                DAW. Let your DAW handle the audio and use Video Slave do what it is best at and do all
                                the
                                heavy lifting with video effortlessly. Our video engine supports a wide range of video
                                codecs and containers so you don't need to worry about platform-specific video anymore
                                and
                                can focus on the creative process. Video Slave's feature set is designed for composers
                                and
                                audio professionals alike.
                            </div>
                        </div>
                    </div>
                    <div class="item row align-items-center">
                        <div class="col-md-4">
                            <img src="/images/other2.jpg">
                        </div>
                        <div class="col-md-8">
                            <div class="title">
                                Professional audio capabilities
                            </div>
                            <div class="minidesc">
                                Video Slave will playback all audio tracks a movie contains with up to 8 channels per
                                track.
                                Additionally, you can create audio tracks and drag audio files to them the same way you
                                can
                                in your DAW. Video Slave will draw audio waveforms and offer all audio-related features
                                you
                                are used to on your workstation such as flexible output routing and standard audio
                                controls
                                such as solo, mute, volume and pan on an individual track basis.
                            </div>
                        </div>
                    </div>
                    <div class="item row align-items-center">
                        <div class="col-md-4">
                            <img src="/images/other3.jpg">
                        </div>
                        <div class="col-md-8">
                            <div class="title">
                                Ease your workflow with timelines
                            </div>
                            <div class="minidesc">
                                <p>No matter if you're working on a reel-based feature film, commercials or a whole
                                    season
                                    of a TV or web series, Video Slave's timeline feature has you covered!</p>
                                <p>A project in Video Slave can contain several timelines, and each timeline can hold
                                    several video and audio tracks. Use one movie per timeline or all reels of a film,
                                    use
                                    several video tracks in one timeline or only one and instead use separate timelines
                                    -
                                    whatever aids your workflow. Edit video and audio regions to easily insert a
                                    rendered
                                    scene or have one timeline for a reel-wise and another for a tied version of the
                                    same
                                    film for presentation purposes - all in the same project and with minimal
                                    effort.</p>
                            </div>
                        </div>
                    </div>
                    <div class="item row align-items-center">
                        <div class="col-md-4">
                            <img src="/images/other4.jpg">
                        </div>
                        <div class="col-md-8">
                            <div class="title">
                                Plays nice with others
                            </div>
                            <div class="minidesc">
                                <p>Video Slave 4 works well with other software solutions such as The Cargo Cult's
                                    Spanner
                                    3. The integration allows users to display pan positions from Spanner directly in
                                    Video
                                    Slave.</p>
                                <p>We will be adding more integrations with other software solutions very soon!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="feature_list_but">
                    <span class="tog vis">See a full list of features and system requirements</span>
                    <span class="tog">Hide feature list</span>
                </a>
                <div class="full_list">
                    <div class="versions text-right">
                        <div class="row">
                            <div class="col-md-3 offset-6">
                                <div class="vs">Video Slave 4 Standard</div>
                            </div>
                            <div class="col-md-3">
                                <div class="vs bl">Video Slave 4 Pro</div>
                            </div>
                        </div>
                    </div>
                    <div class="fl_group">
                        <div class="fl_head">Movie playback</div>
                        <div class="row fl_item">
                            <div class="col-md-6">Synchronized movie playback with scrubbing support using MTC and MMC
                            </div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Support for all of the most common codecs and containers</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Support for movies in the MXF container (Op1A and OpAtom)</div>
                            <div class="col-md-3"><span class="empty"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Projects with timelines</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Flexible movie export to H.264 and Pro Res</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                    </div>
                    <div class="fl_group">
                        <div class="fl_head">Video device support</div>
                        <div class="row fl_item">
                            <div class="col-md-6">Blackmagic Device Support with output resolutions up to Full HD</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Blackmagic/AJA Device Support with output resolutions up to 4K</div>
                            <div class="col-md-3"><span class="empty"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Video reference/genlock support</div>
                            <div class="col-md-3"><span class="empty"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Syphon Framework Support</div>
                            <div class="col-md-3"><span class="empty"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                    </div>
                    <div class="fl_group">
                        <div class="fl_head">Overlays / visual events</div>
                        <div class="row fl_item">
                            <div class="col-md-6">Timecode overlay</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Streamer, Marker, Flutter events</div>
                            <div class="col-md-3"><span class="full"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Visual event import (CSV, ADR Studio XML, SRT/STL)</div>
                            <div class="col-md-3"><span class="empty"></span></div>
                            <div class="col-md-3"><span class="full"></span></div>
                        </div>
                    </div>
                    <div class="fl_group">
                        <div class="fl_head">System requirements</div>
                        <div class="row fl_item">
                            <div class="col-md-6">Supported OS</div>
                            <div class="col-md-6 gs"><span class="text">Mac OS X 10.10 and later</span></div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Minimum Requirements</div>
                            <div class="col-md-3"><span class="text">Intel Mac with 4 GB RAM, HDD, 512 MB VRAM</span>
                            </div>
                            <div class="col-md-3"><span class="text">Intel Mac with 4 GB RAM, HDD, 512 MB VRAM</span>
                            </div>
                        </div>
                        <div class="row fl_item">
                            <div class="col-md-6">Recommended Requirements</div>
                            <div class="col-md-3"><span class="text">Intel Mac with 8 GB RAM, SSD, 1 GB VRAM</span>
                            </div>
                            <div class="col-md-3"><span class="text">Intel Mac with 8 GB RAM, SSD, 1 GB VRAM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gbg get_start">
            <div class="container">
                <h2 class="text-center">
                    Get started quickly!
                </h2>
                <div class="row">
                    <div class="col-md-3">
                        <a href="#">
                            <img src="/images/land1.jpg">
                            <span class="name text-center">
                                Getting Started with Video Slave 4
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#">
                            <img src="/images/land2.jpg">
                            <span class="name text-center">
                                DAW MTC/MMC Setup
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#">
                            <img src="/images/land3.jpg">
                            <span class="name text-center">
                                Network MIDI Setup
                            </span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#">
                            <img src="/images/land4.jpg">
                            <span class="name text-center">
                                Editing VS 4
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="whbg pricing">
            <div class="container">
                <h2 class="text-center">Purchase options</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="price">
                            <img src="/images/vs4_icon.png">
                            <div class="lic_type">
                                SUBSCRIPTION LICENSE
                            </div>
                            <div class="sum">US$17.99</div>
                            <div class="sub_sum">per month</div>
                            <div class="feats">
                                <div>With 1 year plan (includes major upgrades)</div>
                                <div>Uses NLA licensing system with online activations</div>
                            </div>
                            <div class="buybut">
                                <a>SUBSCRIBE NOW</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="price">
                            <img src="/images/vs4_icon.png">
                            <div class="lic_type">
                                Perpetual license
                            </div>
                            <div class="sum">US$339.00</div>
                            <div class="sub_sum">One time purchase</div>
                            <div class="feats">
                                <div>Includes all 4.x updates</div>
                                <div>Uses the PACE licensing system with machine or iLok licensing</div>
                            </div>
                            <div class="buybut">
                                <a>BUY NOW</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bgpanel">
                    <div class="text">
                        <h4>Download your fully functional demo* of Video Slave TODAY</h4>
                        * Demo software is video watermarked and runs for 14 days.
                    </div>
                    <a class="download_but" href="#">Download</a>
                </div>
            </div>
        </div>
    </section>
@endsection
