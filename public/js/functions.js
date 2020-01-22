$(document).ready(function () {
    $('.slider, .user_stories').owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        dots: false,
        navText: ["<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>","<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>"]
    });
    $('.news_slider').owlCarousel({
        loop:true,
        nav: true,
        dots: false,
        navText: ["<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>","<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>"],
        responsive : {
            0:{
                items: 1
            },
            992:{
                items: 3
            }
        }

    })
    $('a.problems').click(function(){
        $(this).toggleClass('opened');
        $(this).next('.other').slideToggle();
    })
    $('form[name="support"] input[type="file"]').change(function () {
        $('.file_val').text('');
        var names = [];
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            names.push($(this).get(0).files[i].name+' <br /> ');
        }
        $('.file_val').append(names);
    })
    $('.topmenu li.parent>a').click(function(e){
        e.preventDefault();
        $(this).parent().find('ul').slideToggle();
    })
    $('.mobmenu').click(function () {
        $(this).toggleClass('toggled');
        $('.mmenu').slideToggle();
    })
    $('a.feature_list_but').click(function () {
        let hided = $('.tog:not(.vis)');
        $('.tog.vis').removeClass('vis');
        $(hided).addClass('vis');
        $('.full_list').slideToggle();
    })
    $('.tbs a').click(function () {
        let target = $(this).attr('data-tg');
        $('.tbs a').removeClass('active');
        $(this).addClass('active');
        $('#subs,#perp').hide();
        $('#'+target+'').show();
    })
    $('a.dlinks').click(function () {
        $(this).closest('.item').find('.download_block').slideToggle();
    })
});
