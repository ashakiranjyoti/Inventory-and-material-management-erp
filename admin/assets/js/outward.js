$(document).ready(function() {
    $('#purpose').change(function() {
        var purpose = $(this).val();
        if (purpose === 'Assembly' || purpose === 'Production' || purpose === 'Design' || purpose === 'Sample') {
            $('#des').val('N/A');
        } else {
            $('#des').val('');
        }
    });

    // Trigger change event on page load to set initial state
    $('#purpose').trigger('change');
});
