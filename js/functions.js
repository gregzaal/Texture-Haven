var click_functions = function(){

    // Grid option menus
    $('.grid-option').click(function() {
        var dropdown = $(this).children('.dropdown');
        if (dropdown.css('visibility') == 'hidden') {
            dropdown.css({'visibility': 'visible', 'opacity': '1'});
            $('.grid-option').not(this).children('.dropdown').css({'visibility': 'hidden', 'opacity': '0'});
        }else{
            dropdown.css({'visibility': 'hidden', 'opacity': '0'});
        }
    });

    // Problem text
    $('.problem-wrapper').children().mouseenter(function() {
        $(this).parent().children('.problem').addClass("problem-hover");
    });
    $('.problem-wrapper').children().mouseleave(function() {
        $(this).parent().children('.problem').removeClass("problem-hover");
    });

    // Navbar Mobile
    $('#navbar-toggle').click(function() {
        var navbar = $('#navbar');
        if (navbar.css("display") != "none"){
            navbar.css("display", "none");
        }else{
            navbar.css("display", "block");
        }
    });

    // Sidebar Mobile
    $('#sidebar-toggle').click(function() {
        var sidebar = $('#sidebar');
        if (sidebar.css("display") != "none"){
            sidebar.animate({'left': "-200px"}, 200, function(){
                sidebar.css("display", "none");
            });
        }else{
            sidebar.css("display", "block");
            sidebar.animate({'left': "0"}, 200);
        }
    });

    // Lightbox
    $('.lightbox-trigger').click(function() {
        $('#lightbox-img').attr("src", "");
        $('#lightbox-wrapper').removeClass("hide");
        $('#lightbox-img').attr("src", $(this).attr("lightbox-src"));
        
        if ($("#artwork-name").length){  // Gallery
            $("#artwork-name").html($(this).attr("artwork-name"));
            $("#author-name").html($(this).attr("author-name"));
            $("#author-link").attr("href", $(this).attr("author-link"));
            $("#tex-used-name").html($(this).attr("tex-used-name"));
            $("#tex-used-link").attr("href", $(this).attr("tex-used-link"));

            if ($(this).attr("author-link") == "#"){
                $("#author-link").addClass("hide-link");
            }else{
                $("#author-link").removeClass("hide-link");
            }

            if ($(this).hasClass("gallery-click")){
                $.post("click.php", {id: $(this).attr("gallery-id")});
                console.log("click!");
            }
        }

        if ($("#href-dlbp-pretty").length){  // Backplates
            $("#href-dlbp-pretty").attr("href", $(this).attr("dlbp-pretty"));
            $("#href-dlbp-plain").attr("href", $(this).attr("dlbp-plain"));
            $("#href-dlbp-raw").attr("href", $(this).attr("dlbp-raw"));
            $("#href-dlbp-pretty").attr("download", $(this).attr("dlbp-pretty").substring($(this).attr("dlbp-pretty").lastIndexOf('/')+1));
            $("#href-dlbp-plain").attr("download", $(this).attr("dlbp-plain").substring($(this).attr("dlbp-plain").lastIndexOf('/')+1));
            $("#href-dlbp-raw").attr("download", $(this).attr("dlbp-raw").substring($(this).attr("dlbp-raw").lastIndexOf('/')+1));
        }
    });
    $('#lightbox-close, #lightbox-wrapper').click(function() {
        $('#lightbox-wrapper').addClass("hide");
        $('#lightbox-img').attr("src", $(this).attr("lightbox-src"));
    });
    $('#href-dlbp-pretty, #href-dlbp-plain, #href-dlbp-raw').click(function(evt) {
        evt.stopPropagation();  // Prevent lightbox closing after downloading backplate
    });

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
                $(this).children().children('.map-preview-icon').attr('src', '/files/site_images/icons/loading.svg');
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
        $("#map-preview-resolution-select").html("<img src='/files/site_images/icons/loading.svg' />")
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
    });
};

var on_load = function(){

    // Push footer to bottom
    var h = $("#header").height();
    var f = $("#footer").height();
    var css = "calc(100vh - "+h+"px - "+f+"px)";
    $('#push-footer').css("min-height", css);

};

$(document).ready(click_functions);
$(document).ready(on_load);
