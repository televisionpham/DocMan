jQuery(document).ready(function () {
    jQuery('.datepicker').datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true
    });

    jQuery('#cong_van_upload_file_button').click(function() {
        formField = jQuery('#cong_van_upload_file').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {   
        var count = jQuery('#attachments > div').length;
        var id = 'cong_van_den_custom_content_toan_van_' + count;
        attachmentElement = '<div><label for="cong_van_den_custom_content_toan_van">' + html +'</label>' +
        '<input type="hidden" name="' + id + '" id="' + id +'"/></div>';
        jQuery('#attachments').append(attachmentElement);
        jQuery("#" + id).val(html);
        tb_remove();
    }
});