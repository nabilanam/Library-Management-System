// validation
$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            first_name: {
                rules: [
                    {
                        type: 'minLength',
                        value: '3'
                    }
                ]
            },
            last_name: {
                rules: [
                    {
                        type: 'minLength',
                        value: '3'
                    }
                ]
            },
            user_type: {
                rules: [
                    {
                        type: 'empty'
                    }
                ]
            },
            permanent_address: {
                rules: [
                    {
                        type: 'empty'
                    }
                ]
            }
        }
    });

$('#edit_member').click(function () {
    document.getElementById('edit_member').setAttribute('type', 'submit');
});