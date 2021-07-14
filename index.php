<!doctype html>
<link rel="stylesheet" href="style.css">

<div id="app">
    <section class="comments">
    <h1>Comments</h1>
    <form class="comments__form" action="https://unobserved-eights.000webhostapp.com/app/api.php?action=add" method="post" onsubmit="request(event, this);">
        <div class="form_block">
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
        </div>

        <div class="form_block">
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="7"></textarea>
        </div>
        <button type="submit">Submit</button>
    </form>

    <div class="comments__entries">
        <div class="comment template" data-id="">
            <span class="name" contenteditable></span>
            <span class="time"></span>
            <pre></pre>
            <a class="remove" href="" onclick="removeComment(event, this)">x</a>
            <a class="update" href="" onclick="editComment(event, this)">edit</a>
        </div>
    </div>
    </section>
</div>


<script src="script.js"></script>