var i = 0;
var still_loading = false;
var next_image = function(direction) {
    if (direction > 0){
        i++;
    }else{
        i--;
    }

    // Wrap
    if (i == images.length){
        i = 0;
    }else if(i == -1){
        i = images.length -1;
    }

    var b_next = $('#banner-img-a');
    var b_prev = $('#banner-img-b');
    if ($('#banner-img-b').hasClass('hide')){
        b_next = $('#banner-img-b');
        b_prev = $('#banner-img-a');
    }

    var url = "/files/site_images/landing/"+images[i][0];
    b_next.css('background', "url("+url+") no-repeat center center");
    var tmp_img = new Image();
    tmp_img.src = url;
    if (still_loading == false){
        still_loading = true;
        tmp_img.onload = function(){
            still_loading = false;
            b_next.children('.banner-img-credit').html("Render by "+images[i][1]);

            // Ensure nice fade without white flash
            b_next.css('z-index', "12");
            b_prev.css('z-index', "11");

            b_next.removeClass('hide');
            setTimeout(function(){
                b_prev.addClass('hide');
            }, 300);
        };
    }
}

var now_playing = null;
var on_load = function(){
    images = [
    ["1.jpg", "Rob Tuytel"],
    ["2.jpg", "Rob Tuytel"],
    ["3.jpg", "Rob Tuytel"],
    ["4.jpg", "Rob Tuytel"],
    ["5.jpg", "Rob Tuytel"],
    ];

    setTimeout(function(){
        now_playing = setInterval("next_image(1)", 5000);
    }, 3000);

    $('.banner-img-paddle').click(function() {
        clearInterval(now_playing);
    });
    $('#banner-img-paddle-l').click(function() {next_image(-1)});
    $('#banner-img-paddle-r').click(function() {next_image(1)});
};

$(document).ready(on_load);
