$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            name: ['minLength[3]', 'empty'],
            address: 'empty',
            email: 'empty'
        }
    });

$('#edit_institute').click(function () {
    document.getElementById('edit_institute').setAttribute('type', 'submit');
});