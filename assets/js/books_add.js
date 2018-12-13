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
            cover_photo: 'empty',
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

// isbn
let elm_isbn = document.getElementById("isbn");

elm_isbn.addEventListener("keyup", function (event) {
    let isbn = elm_isbn.value.split("-").join("");

    if (event.keyCode === 13) {
        let elm_title = document.getElementById("title");
        let elm_subtitle = document.getElementById("subtitle");
        let elm_publish_year = document.getElementById("publish_year");
        let elm_pages = document.getElementById("pages");

        let response = fetch('https://www.googleapis.com/books/v1/volumes?q=isbn:' + isbn);
        response.then(function (data) {
            data.json().then(function (json) {
                json = json["items"][0]["volumeInfo"];

                $("#authors").empty();
                // $("#publisher").empty();
                $('#title').empty();
                $('#subtitle').empty();
                $('#publish_year').empty();
                $('#pages').empty();
                $('a.ui.label').remove();

                elm_title.value = json["title"] || '';
                elm_subtitle.value = json["subtitle"] || '';
                elm_publish_year.value = json["publishedDate"] === undefined ? '' : json["publishedDate"].substring(0, 4);
                elm_pages.value = json["pageCount"] || '';

                let option = document.createElement('option');
                option.value = json["publisher"] || '';
                option.innerText = json["publisher"] || '';
                option.selected = true;
                document.getElementById("publisher").append(option);

                let authors = json["authors"] || '';
                $.each(authors, function (index, name) {
                    option = document.createElement('option');
                    option.value = name;
                    option.innerText = name;
                    option.selected = true;
                    document.getElementById("authors").append(option);
                });

                console.log(json["categories"][0]);
            })
        });
    }
});