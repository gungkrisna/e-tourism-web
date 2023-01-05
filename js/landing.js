$(document).ready(function ($) {
    $('#categoryResults li').each(function () {
        $(this).attr('searchData', $(this).text().toLowerCase());
    });
    $('#categoryInput').on('keyup', function () {
        var dataList = $(this).val().toLowerCase();
        $('#categoryResults li').each(function () {
            if ($(this).attr('searchData').indexOf(dataList) !== -1 || dataList.length < 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $(document).ready(function ($) {
        $('#destinationResults li').each(function () {
            $(this).attr('searchData', $(this).text().toLowerCase());
        });
        $('#destinationInput').on('keyup', function () {
            var dataList = $(this).val().toLowerCase();
            $('#destinationResults li').each(function () {
                if ($(this).attr('searchData').indexOf(dataList) !== -1 || dataList.length < 1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    // Select the input element and bind a focus event
    $('#destinationInput').focus(function () {
        // When the input is focused, show the autocomplete list
        $('#destinationResults').show();
    });

    // Select the autocomplete list and bind a click event to the list items
    $('#destinationResults .rlr-autocomplete__item').click(function () {
        // When an item is clicked, set the value of the input to the text of the span element inside the clicked item
        $('#destinationInput').val($(this).find('.rlr-autocomplete__text').text());

        destinasiParam = $(this).data('id');
        destinasiId = $(this).attr('id');

        // Hide the autocomplete list
        $('#destinationResults').hide();
    });

    $(document).click(function (event) {
        // Check if the target of the click event is the input or the autocomplete list
        if (!$(event.target).closest('#destinationInput, #destinationResults').length) {
            // If the target is not the input or the autocomplete list, hide the list
            $('#destinationResults').hide();
        }
    });

    // Select the input element and bind a focus event
    $('#categoryInput').focus(function () {
        // When the input is focused, show the autocomplete list
        $('#categoryResults').show();
    });

    // Select the autocomplete list and bind a click event to the list items
    $('#categoryResults .rlr-autocomplete__item').click(function () {
        // When an item is clicked, set the value of the input to the text of the span element inside the clicked item
        $('#categoryInput').val($(this).text());
        kategoriId = $(this).attr('id');
        // Hide the autocomplete list
        $('#categoryResults').hide();
    });

    $(document).click(function (event) {
        // Check if the target of the click event is the input or the autocomplete list
        if (!$(event.target).closest('#categoryInput, #categoryResults').length) {
            // If the target is not the input or the autocomplete list, hide the list
            $('#categoryResults').hide();
        }
    }



    );

    $('#searchDestination').on('submit', function(e) {
        e.preventDefault();
        // Append the values to the URL as query parameters
        var url = this.action + '?' + destinasiParam + '=' + destinasiId + '&kategori=' + kategoriId;

        // Navigate to the URL
        window.location.href = url;
    });
});