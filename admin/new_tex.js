function allowedCharsOnly(str, allowed_chars){
    var return_str = "";
    for (var i = 0; i<str.length; i++){
        var c = str.charAt(i);
        var x = allowed_chars.indexOf(c);
        if (x != -1){
            return_str += c;
        }
    }
    return return_str;
}

function removeAllButLast(string, token) {
    var parts = string.split(token);
    if (parts[1]===undefined)
        return string;
    else
        return parts.slice(0,-1).join('') + token + parts.slice(-1)
}

function nextInDOM(_selector, _subject) {
    // From http://stackoverflow.com/a/12873187/2488994 by techfoobar
    var next = getNext(_subject);
    while(next.length != 0) {
        var found = searchFor(_selector, next);
        if(found != null) return found;
        next = getNext(next);
    }
    return null;
}
function getNext(_subject) {
    if(_subject.next().length > 0) return _subject.next();
    return getNext(_subject.parent());
}
function searchFor(_selector, _subject) {
    if(_subject.is(_selector)) return _subject;
    else {
        var found = null;
        _subject.children().each(function() {
            found = searchFor(_selector, $(this));
            if(found != null) return false;
        });
        return found;
    }
    return null; // will/should never get here
}

function strContains(needle, haystack) {
    return haystack.toLowerCase().indexOf(needle) >= 0;
}
  

var go = function(){

    // Disable enter key
    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $("#texture-maps").change(function() {
        var fileList = document.getElementById("texture-maps").files;
        var html = "";
        $.each(fileList, function(i, f){
            var fname = f['name'];
            var warn = "";
            if (!strContains('.png', fname)){
                warn += "Not a PNG file; "
            }
            var invalidate = new RegExp('_[0-9]k.[a-zA-Z]');
            if (invalidate.test(fname)){
                warn += "Filename must not include the resolution; "
            }
            // fname = fname.replace(/\.png/, "");
            html += "<li>"+fname;
            if (warn){
                html += ' <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>';
                html += warn;
                html += "</b>";
            }
            html += "</li>";
        });
        $("#map-list").html(html)
    });

    function previewUploaded(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#sphere-render-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#sphere-render").change(function() {
        previewUploaded(this);
        $("#sphere-render-preview-wrapper").removeClass("hidden");
    });

    function previewUploadedImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#sphere-render-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#sphere-render").change(function() {
        previewUploadedImg(this);
        $("#sphere-render-preview-wrapper").removeClass("hidden");
    });

    // Click functions
    $(".show-tooltip").click(function() {
        var tooltip = nextInDOM(".tooltip", $(this));
        // var tooltip = $(this).nextAll(".tooltip")[0];
        console.log(tooltip);
        if (tooltip.hasClass("hidden")){
            tooltip.removeClass("hidden");
        }else{
            tooltip.addClass("hidden");
        }
    });

    $('#auto-slug').click(function() {
        if($("#form-slug").is(":disabled")){
            $("#form-slug").prop("disabled", false);
        }else{
            $("#form-slug").prop("disabled", true);
            autoSlug();
        }
    });

    $('.cat-option').click(function() {
        var newCat = $(this).html();
        var currentCats = $("#form-cats").val().replace(/;/, ",");
        if (currentCats == ""){
            $("#form-cats").val(newCat);
        }else{
            var currentCatsArr = currentCats.split(",");
            var newCats = [];
            for (var i=0; i<currentCatsArr.length; i++){
                var cat = currentCatsArr[i].trim();
                if (cat != newCat){
                    newCats.push(cat);
                }
            }
            newCats.push(newCat);
            $("#form-cats").val(newCats.join(", "));
        }
    });

    $('.tag-option').click(function() {
        var newTag = $(this).html();
        var currentTags = $("#form-tags").val().replace(/;/, ",");
        if (currentTags == ""){
            $("#form-tags").val(newTag);
        }else{
            var currentTagsArr = currentTags.split(",");
            var newTags = [];
            for (var i=0; i<currentTagsArr.length; i++){
                var tag = currentTagsArr[i].trim();
                if (tag != newTag){
                    newTags.push(tag);
                }
            }
            newTags.push(newTag);
            $("#form-tags").val(newTags.join(", "));
        }
    });


    // Form changes
    var validateSlug = function(str){
            str = str.toLowerCase().replace(/ /g, "_");
            return allowedCharsOnly(str, "qwertyuiopasdfghjklzxcvbnm_-0123456789");
    }
    var autoSlug = function(){
        if ($("#auto-slug").is(":checked")){
            var name = $('#form-name').val();
            $("#form-slug").val(validateSlug(name));
            $('#form-slug-actual').val($('#form-slug').val());
        }
    }
    $('#form-name').keyup(autoSlug);
    $('#form-slug').change(function(){
        $('#form-slug').val(validateSlug($('#form-slug').val()));
        $('#form-slug-actual').val($('#form-slug').val());
    });

    var validateDateTime = function(str){
        str = str.replace(/\\/g, "\/");
        str = str.replace(/-/g, "\/");
        str = allowedCharsOnly(str, "0123456789:/ ");
        return str;
    }
    var validateDatePublished = function(str){
        str = validateDateTime(str);
        if (str == ""){
            return "Immediately";
        }
        return str;
    }
    $('#form-date-published').change(function(){
        $('#form-date-published').val(validateDatePublished($('#form-date-published').val()));
    });

    var validateTagsCats = function(str){
        str = str.replace(/;/g, ",");
        str = str.replace(/, /g, ",");
        str = str.replace(/,/g, ", ");
        return str;
    }
    $('#form-cats').change(function(){
        $('#form-cats').val(validateTagsCats($('#form-cats').val()));
    });
    $('#form-tags').change(function(){
        $('#form-tags').val(validateTagsCats($('#form-tags').val()));
    });
};

$(document).ready(go);
