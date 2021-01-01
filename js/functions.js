var click_functions = function(){

    // Map previews
    $('.map-preview').click(function() {
        var preview_img = $("#map-preview-img");
        $('.map-preview-icon').attr('src', '/files/site_images/icons/eye.svg')
        if ($(this).hasClass("map-preview-active")){
            $(this).removeClass("map-preview-active");
            $("#map-preview-allmaps").addClass("map-preview-active");
            preview_img.addClass("hide");
        }else{
            $(".map-preview").removeClass("map-preview-active");  // Previously active buttons
            var slug = $("#page-data").attr('slug');
            var map_type = $(this).attr("map");
            $("#page-data").attr('map_type', map_type);
            $("#map-preview-resolution-select").html('640p');
            $(this).addClass("map-preview-active");
            if (map_type == "all"){
                preview_img.addClass("hide");
            }else{
                $(this).children().children('.map-preview-icon').attr('src', '/core/img/icons/loading.svg');
                $(".map-preview-zoom").css('color', 'rgb(246, 246, 246)');
                preview_img.css('background-size', "100%");
                var new_img = "/files/tex_images/map_previews/"+slug+"/"+map_type+".jpg";
                $('<img/>').attr('src', new_img).on('load', function(){  // Load image in dummy tag
                    $(this).remove();
                    preview_img.removeClass("hide");
                    $('.map-preview-icon').attr('src', '/files/site_images/icons/eye.svg');
                    preview_img.css("background-image", "url(\""+new_img+"\")");
                });

                var resolutions = JSON.parse($('#page-data').html())[map_type];
                var r_list = "";
                resolutions.forEach(function(r){
                    r_list += "<li class='propt'>"+r+"</li>";
                }) ;
                $("#map-preview-resolution-list").html(r_list);
            }
        }
    });
    // Zoom preview
    $('.map-preview-zoom').click(function() {
        var d = 1.5;
        if ($(this).html() == "-"){
            d = 1/d;
        }
        var preview_img = $("#map-preview-img");
        var size = preview_img.css('background-size').split("%")[0];
        size = Math.max(10, Math.min(1000, size*d));
        if (80 < size && size < 120){
            size = 100;
        }
        $(".map-preview-zoom").css('color', 'rgb(246, 246, 246)')
        if (size > 100){
            $("#map-preview-zoom-in").css('color', 'rgb(155, 214, 61)');
        }else if (size < 100){
            $("#map-preview-zoom-out").css('color', 'rgb(155, 214, 61)');
        }
        preview_img.animate({'background-size': size+"%"},200);
    });
    $("#map-preview-img").mousemove(function(e) {
        var size = $(this).css('background-size').split("%")[0];
        if (size != 100){
            var offset = $(this).offset();
            var relX = (e.pageX - offset.left)/$(this).width()*100;
            var relY = (e.pageY - offset.top)/$(this).height()*100;
            $(this).css('background-position', relX+"% "+relY+"%");
        }
    });
    $('#map-preview-resolution-select').click(function() {
        $('#map-preview-resolution-list').removeClass('hidden');
    });
    $('#map-preview-resolution-list').on('click', 'li', function() {
        var res = $(this).html();
        var slug = $("#page-data").attr('slug');
        var map_type = $("#page-data").attr('map_type');
        var preview_img = $("#map-preview-img");
        var new_img = "/files/textures/jpg/"+res+"/"+slug+"/"+slug+"_"+map_type+"_"+res+".jpg";
        $("#map-preview-resolution-select").html("<img src='/core/img/icons/loading.svg' />")
        $('<img/>').attr('src', new_img).on('load', function(){  // Load image in dummy tag
            $(this).remove();
            $("#map-preview-resolution-select").html(res);
            preview_img.css("background-image", "url(\""+new_img+"\")");
        });
    });
    $('#map-preview-resolution-list,#map-preview-img').mouseleave(function() {
        $('#map-preview-resolution-list').addClass('hidden');
    });

    // Download menu
    $('.map-download').click(function() {
        var this_menu = $(this).parent().children(".res-menu");
        this_menu.parent().siblings().children(".res-menu").addClass("hide");
        this_menu.parent().siblings().children(".map-download").removeClass("map-download-active");
        if (this_menu.hasClass("hide")){
            this_menu.removeClass("hide");
            $(this).addClass("map-download-active");
        }else{
            this_menu.addClass("hide");
            $(this).removeClass("map-download-active");
        }
    });
    // Download track
    $('.dl-btn').click(function() {
        $.post("dl_click.php", {id: $(this).attr("id"), fhash: $(this).attr("fhash")});
        ga('send', 'event', 'Downloads', 'texture download', $(this).attr("id")+"+"+$(this).attr("fhash"));
    });
};

$(document).ready(click_functions);
