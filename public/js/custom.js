$(document).ready(function () {
    $("#copy_btn").click(function(e) {

        $('#referal_link').focus();
        $('#referal_link').select();
        document.execCommand('copy');
        $('#referal_link').blur();
        console.log('Copied');
    });

});