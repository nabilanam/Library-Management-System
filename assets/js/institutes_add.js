$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            name: ['minLength[3]', 'empty'],
            address: 'empty',
            email: 'empty',
            logo: 'empty'
        }
    });

$('#save_institute').click(function () {
    document.getElementById('save_institute').setAttribute('type', 'submit');
});