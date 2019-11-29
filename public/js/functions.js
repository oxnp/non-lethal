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
        items: 3,
        loop:true,
        nav: true,
        dots: false,
        navText: ["<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>","<span></span><svg width=\"10\" height=\"17\" viewBox=\"0 0 10 17\">\n" +
        "<use xlink:href=\"#arrow\" x=\"0\" y=\"0\" />\n" +
        "</svg>"]
    })
    $('a.problems').click(function(){
        $(this).toggleClass('opened');
        $(this).next('.other').slideToggle();
    })
    $('form[name="support"] input[type="file"]').change(function () {
        $('.file_val').text('');
        $('.file_val').append($(this).val());
    })
    $('.topmenu li.parent>a').click(function(e){
        e.preventDefault();
        $(this).parent().find('ul').slideToggle();
    })
});
