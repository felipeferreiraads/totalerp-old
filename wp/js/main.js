function hideMenu(menu, classe){
    $(menu).removeClass(classe);
}


$(document).ready(function() {

 $('body').fadeIn();

 $('[data-trigger-modal]').on('click', function() {
    $('[data-degustation-modal]').css('display', 'flex')
 });

  $('[data-close-modal]').on('click', function() {
    $('[data-degustation-modal]').fadeOut()
 });

 $('.product-slider').slick({
    prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    slidesToShow:4,
    infinite:false,        
    responsive: [
    {
      breakpoint: 1150,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        arrows: true
    }
},
{
  breakpoint: 878,
  settings: {
    slidesToShow: 2,
    slidesToScroll: 2                
}
},    
{
  breakpoint: 600,
  settings: {
    slidesToShow: 1,
    slidesToScroll: 1,
    variableWidth: true
}
}     
]
});


 $('#pageslider').slick({
    slidesToShow:1,
    infinite:true,
    fade:true,
    autoplay:true,
    autoplaySpeed:6000,
    arrows:false
});


 $('.featured .slides').slick({
    slidesToShow:6,
    infinite:false,
    variableWidth:true,
    prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
    responsive: [
    {
      breakpoint: 1250,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 4 ,
        variableWidth: false, 
    }
},            
{
  breakpoint: 800,
  settings: {
    slidesToShow: 3,
    slidesToScroll: 3,

}
},    
{
  breakpoint: 767,
  settings: {
    slidesToShow: 2,
    slidesToScroll: 2,
    variableWidth: false,
}
},    
{
  breakpoint: 567,
  settings: {
    slidesToShow: 1,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: '50%'

}
}     
]
});



    /** Mobile Netflix Accordion 

    $('.collapse').on('show.bs.collapse', function (e) {
        $('.collapse').collapse("hide")
    })

    **/

    /**tooltip**/

    $('[data-toggle="tooltip"]').tooltip();


    $('.testimonials').slick({
        slidesToShow:1,
        infinite:false,
        centerMode: true,
        centerPadding: '60px', 
        variableWidth:false,  
        speed: 500,
        fade: true,     
        prevArrow:'<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>'
    });

    $('.line').fadeIn();


    $('.close-navbar').on('click', function(){
        hideMenu('.navbar-collapse', 'show');
    });

    $('[data-toggle="popover"]').popover();   


    $( '[data-tab-toggle]' ).on( 'click', function() {
        let $id = $( this ).data( 'tab-toggle' );
        $( '[data-tab-item]').not(`[data-tab-item="${$id}"]`).fadeOut();

        $( `[data-tab-item="${$id}"]` ).fadeIn();

    } )
    $('.nav-link.dropdown-toggle').on('mouseover', function(){
        $(this).trigger('click');
    })

    $('.products-list .card').on('click', function(){
        var slug = $(this).attr('data-title');
        $(slug).addClass('active').siblings('.white').removeClass('active');
        $('.card').removeClass('active');
        $(this).addClass('active');

    });

    /** Filtering Netflix **/

    var filtered = false;

    /** Force Filtering on page load */

    var firstGroup = $('.group-title').eq(0);
    var groupCategoryId = firstGroup.data('category');
    var firstSlides = $('.'+groupCategoryId);
    $('.featured .slides').slick('slickFilter', firstSlides);  
    $('.featured .slides').slick('slickGoTo', 1);
    $('#selectedTermID').val(firstGroup.data('termid'));
    $('[data-content-category="'+groupCategoryId+'"]').fadeIn();



    $('.group-title').on('click', function(e){
        var categoryID = $(this).data('category');
        var termId = $(this).data('termid');
        var slides = $('.'+categoryID);
        $('#selectedTermID').val(termId);
        $(this).addClass('active').siblings('.group-title').removeClass('active');
        $('[data-content-category="'+categoryID+'"]').siblings('[data-content-category]').fadeOut(150);
        $('[data-content-category="'+categoryID+'"]').fadeIn();

        if(filtered === false){         
            $('.featured .slides').slick('slickFilter', slides);  
            $('.featured .slides').slick('slickGoTo', 1);
            filtered = true; 
            console.log(filtered);
        } else {
            $('.featured .slides').slick('slickUnfilter');          
            filtered = false;
            $(this).trigger('click');
            console.log(filtered);  
        }
    });

    

    /** REST API **/

    $('.flix-slide').click(function () {
        $('.flix-slide').find('.round').removeClass('open');
        $(this).find('.round').addClass('open');
        var templateUrl = object_name.templateUrl;
        var siteUrl = object_name.siteUrl;
        var showData = $('#blue-panel');
        var slide_id = $(this).data('id');

        showData.removeClass('active').addClass('open').load(templateUrl+'/templates/model.html');

        $.getJSON(siteUrl+'/wp-json/wp/v2/produtos/'+slide_id, function (data) {

            var post_title = data.title.rendered;
            var post_content = data.content.rendered;
            var post_slug = data.slug; 
            var post_link = data.link; 
            var post_video = data.acf.youtube; 
            var post_gallery = data.acf.slider;
            
            if (data.better_featured_image !== null)  {
                var post_image = data.better_featured_image.source_url;               
            };

            if(post_video !== "") {
                showData.find(".link-youtube a").html('<i class="fa fa-play-circle-o"></i> ');
            }

            showData.find('h5').html(post_title);
            showData.find('.paragraph').html(post_content);

            //showData.find('.panel-text a:last-of-type').attr('href', siteUrl+'/contato');

            if(filtered === false) {
                showData.find('.panel-text a:first-of-type').attr('href', post_link);
                //showData.find('.panel-text a:last-of-type').attr('href', post_link);
                showData.find('#_produto').val(data.id);
                showData.find('#_tipo').val(2);
            } else {
                var group_link = $('.group-title.active').data('category');
                showData.find('.panel-text a:first-of-type').attr('href', siteUrl+'/pacotes/'+group_link ).html('Detalhes do Pacote');
                //showData.find('.panel-text a:last-of-type').attr('href', siteUrl+'/pacotes/'+group_link ).html('Contratar Pacote');
                showData.find('#_produto').val( $('#selectedTermID').val() );
                showData.find('#_tipo').val(1);
            }
            
            
            $.each(post_gallery, function (i, item) {
                $('.image').prepend('<div class="panel-slide"><div style="background-image:url('+item.url+')"></div></div>');               
            });

            $('.image').slick({
                autoplay: true,
                fade:true
            });

            $('#youtube-player .modal-body').html(post_video);

            $('#youtube-player iframe').attr('id', 'hiddenplayer');

            showData.addClass('active');         

        });          

    });

    $('.wpcf7-form #time').datetimepicker({
        formatTime:'H:i',
        formatDate:'d.m.Y'
    });

});


/** player **/

var player;
var hiddenplayer;

function onYouTubeIframeAPIReady() {
    player = new YT.Player( 'player', {
      events: { 
        'onReady' : onPlayerReady, 
        'onStateChange': onPlayerStateChange        
    }
});
    hiddenplayer = new YT.Player( 'hiddenplayer', {
      events: { 
        'onReady' : onHiddenPlayerReady, 
        'onStateChange': onHiddenPlayerStateChange        
    }
});
    modulesplayer = new YT.Player( 'video-modulares', {
      events: { 
        'onReady' : onHiddenPlayerReady, 
        'onStateChange': onHiddenPlayerStateChange        
    }
});
}

function onPlayerStateChange(event) {
    var d = $('#player');    
    switch(event.data) {
      case -1:
      d.addClass('active');
      break;      
      case 1:
      d.addClass('active');
  }
}

function onHiddenPlayerStateChange(event) {
    var d = $('#hiddenplayer');    
    switch(event.data) {
      case -1:
      d.addClass('active');
      break;      
      case 1:
      d.addClass('active');
  }
}

function onPlayerReady(e) {

    $("#playVideo").on("click", function() {
        player.playVideo();
        $(this).fadeOut(600);   
    });   
}


function onHiddenPlayerReady(event) {

    $('#youtube-player').on('shown.bs.modal', () => hiddenplayer.playVideo());

    $('#youtube-player .closeMe').on('click', () => modulesplayer.stopVideo())
}









