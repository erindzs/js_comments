function request(event, form) {
    event.preventDefault();
    let url = form.getAttribute('action'),
        data = new FormData(form);

    let fields = form.querySelectorAll('input, textarea');
    for (let field of fields) {
        field.textContent = '';
        field.value = '';
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      display(this.responseText);
    };
    xhttp.open("POST", url);

    xhttp.send(data);
}

function display(text) {
    let data = JSON.parse(text);

    if (data.status == "success") {
        displayComments([data.entry]);
        console.log("request complited");
    }
}

function load(url, callback = false) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        let response_object = JSON.parse(this.responseText);
        if (response_object.status == 'success') {
            let data = response_object.data;

            if (callback) {
                callback(data);
            }
            //displayComments(data);
        }
    };
    xhttp.open("GET", url);
    xhttp.send();
}

let comments_block = document.querySelector('.comments__entries');

function displayComments(comments) {
    for (const [id, entry] of Object.entries(comments)) {
        if (entry.id == undefined) {
            entry.id = id;
        }

        let template = comments_block.querySelector('.template');
        let comment = template.cloneNode(true);
        comment.classList.remove('template');
        comment.querySelector('pre').textContent = entry.message;
        comment.querySelector('.name').textContent = entry.name;
        if (entry.time) {
            let date = new Date(entry.time * 1000);
            let month = (date.getMonth()+1);
            month = (month >= 10) ? month : "0" + month;
            let minutes = date.getMinutes();
            minutes = (minutes >= 10) ? minutes : "0" + minutes;

            comment.querySelector('.time').textContent = date.getDate() +
                "." + month +
                "." + date.getFullYear() +
                " " + date.getHours() +
                ":" + minutes;
        }

        comment.setAttribute('data-id', entry.id);
        comment.querySelector('.remove').setAttribute('href', 'https://unobserved-eights.000webhostapp.com/app/api.php?action=remove&remove=' + entry.id);
        comment.querySelector('.update').setAttribute('href', 'https://unobserved-eights.000webhostapp.com/app/api.php?action=update&id=' + entry.id);
        comments_block.append(comment);
    }
}

function removeComment(event, element) {
    event.preventDefault();
    let url = element.getAttribute('href');
    load(url, function () {
        element.parentElement.remove();
    });
}

function editComment(event, element) {
    event.preventDefault();
    element.textContent = 'save';
    let comment_block = element.parentElement;
    comment_block.classList.add('edit');

    let name_element = comment_block.querySelector('.name');
    let name_input = document.createElement('input');
    name_input.value = name_element.textContent;
    name_input.classList.add('input_name');
    comment_block.prepend(name_input);

    let message_element = comment_block.querySelector('pre');
    let message_textarea = document.createElement('textarea');
    message_textarea.textContent = message_element.textContent;
    message_textarea.classList.add('textarea_message');
    comment_block.insertBefore(message_textarea, message_element);

    element.setAttribute('onclick', 'updateComment(event, this)');
}

function updateComment(event, element) {
    event.preventDefault();
    let url = element.getAttribute('href');
    let comment_block = element.parentElement;
    let name_input = comment_block.querySelector('.input_name');
    let name_element = comment_block.querySelector('.name');

    let message_textarea = comment_block.querySelector('.textarea_message');
    let message_element = comment_block.querySelector('pre');
    url = url + "&name=" + name_input.value + "&message=" + message_textarea.value;

    load(url, function () {
        element.textContent = 'edit';
        element.parentElement.classList.remove('edit');
        name_element.textContent = name_input.value;
        name_input.remove();

        message_element.textContent = message_textarea.value;
        message_textarea.remove();
        element.setAttribute('onclick', 'editComment(event, this)');
    });
}

load('https://unobserved-eights.000webhostapp.com/app/api.php?action=get', displayComments);



