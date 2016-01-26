function attachMediaUploader(postId, key) {
    jQuery('#' + key + '_button').click(function() {
    text_element = jQuery('#' + key).attr('name');
    button_element = jQuery('#' + key + '_button').attr('name');
    tb_show('', 'media-upload.php?post_id=' + postId + '&type=image&TB_iframe=true');
    return false;
});
  
window.send_to_editor = function(html) {
    var self_element = text_element;
    imgurl = jQuery('img', html).attr('src');
    jQuery('#' + self_element).val(imgurl);
        tb_remove();
    };
}

//ColorPicker
jQuery(document).ready(function($) {
    $('#colorpicker').hide();
    $('#colorpicker').farbtastic('#bckgrnd_color');

    $('#bckgrnd_color').click(function() {
        $('#colorpicker').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
});