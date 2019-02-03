function check(isChecked) {
    if (isChecked) {
        $('#allowedUsers').parent().hide();
        $('#info-allowedUsers').hide();
    } else {
        $('#allowedUsers').parent().show();
        $('#info-allowedUsers').show();
    }
}

$('#document_isPublic').change(function() {
    check(this.checked);
});

var isChecked = $('#document_isPublic').is(':checked');
check(isChecked);
