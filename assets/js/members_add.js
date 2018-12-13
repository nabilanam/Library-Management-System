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
            email: {
                rules: [
                    {
                        type: 'regExp',
                        value: '^\\S+@\\S+\\.\\S+$'
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
            },
            pro_pic: {
                rules: [
                    {
                        type: 'empty'
                    }
                ]
            },
        }
    });

$('#save_member').click(function () {
    document.getElementById('save_member').setAttribute('type', 'submit');
});