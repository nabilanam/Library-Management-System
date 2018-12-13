// validation
$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            email: {
                rules: [
                    {
                        type: 'regExp',
                        value: '^\\S+@\\S+\\.\\S+$'
                    }
                ]
            }
        }
    });

$('#reset').click(function () {
    document.getElementById('reset').setAttribute('type', 'submit');
});