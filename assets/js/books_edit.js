// dropdown
$('.ui.dropdown')
    .dropdown({
        allowAdditions: false,
        fullTextSearch: true
    });
$('.ui.dropdown.authors')
    .dropdown({
        allowAdditions: true,
    });
$('.ui.dropdown.publisher')
    .dropdown({
        allowAdditions: true,
    });

// validation
$('.ui.form')
    .form({
        keyboardShortcuts: false,
        fields: {
            title: ['minLength[3]', 'empty'],
            edition: 'empty',
            copies: 'empty',
            price: 'empty',
            publish_year: 'empty',
            pages: 'empty',
            authors: 'empty',
            shelf: 'empty',
            publisher: 'empty',
            source: 'empty',
            condition: 'empty',
            category: 'empty'
        }
    });

$('#save_book').click(function () {
    document.getElementById('save_book').setAttribute('type', 'submit');
});