// validation
$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            pass1: ['minLength[6]', 'empty'],
            pass2: ['match[pass1]']
        }
    });

$('#save_member').click(function () {
    document.getElementById('save_member').setAttribute('type', 'submit');
});