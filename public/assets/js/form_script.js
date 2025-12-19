// $('#c_form').submit(function(e) {
//     // e.preventDefault();
//     $('#sub').prop('disabled', true).text('Saving...');
// });

// // form two
// $('#c_form1').submit(function(e) {
//     // e.preventDefault();
//     $('#sub1').prop('disabled', true).text('Saving...');
// });


// form-handler.js
$(document).ready(function() {
    // Listen to submit event on *any* form
    $('form').on('submit', function(e) {
        var $form = $(this); // The form that triggered the event
        var formId = $form.attr('id');

        // Optional: console log the form id
        console.log("Submitting form with ID:", formId);

        // Disable the submit button inside this form
        $form.find('button[type=submit], input[type=submit]').prop('disabled', true).text('Saving...');
    });
});