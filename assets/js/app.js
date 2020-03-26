/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require('bootstrap')
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

var $collectionHolder;

// setup an "add a user" link
var $addUserButton = $('<button type="button" class="add_user_link">Add a user</button>');
var $newLinkLi = $('<li></li>').append($addUserButton);

$(document).ready(function() {
    // Get the ul that holds the collection of users
    $collectionHolder = $('ul.users');

    // add the "add a user" anchor and li to the users ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addUserButton.on('click', function(e) {
        // add a new user form (see next code block)
        addUserForm($collectionHolder, $newLinkLi);
    });
});

function addUserForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your users field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a user" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}
