$(document).ready(function () {
    /*
    Rating Checkbox
    */

    // Set up event handler for checkbox changes
    $('.rating-checkbox').change(function () {
        updateRatingList();
    });

    function updateRatingList() {
        // Get the selected ratingList from the checkboxes
        var ratingString = "";
        $('.rating-checkbox:checked').each(function () {
            var rating = $(this).val();
            if (rating) {
                if (ratingString.length > 0) {
                    ratingString += ",";
                }
                ratingString += rating;
            }
        });

        // Get the current URL and create a new search parameters object from it
        var currentUrl = new URL(location.href);
        var searchParams = new URLSearchParams(currentUrl.search);

        // Set the rating parameter
        searchParams.set("rating", ratingString);

        // Update the URL in the address bar and add a new entry to the browser's history
        currentUrl.search = searchParams.toString();
        window.history.pushState({}, '', currentUrl.toString());

        // Get all the parameters from the URL
        var searchParams = new URLSearchParams(location.search);

        // Convert the search params to an object
        var data = {};
        for (var [key, value] of searchParams) {
            data[key] = value;
        }

        // Send an AJAX request to the server to retrieve the updated product list
        $.ajax({
            url: 'filtered_search.php',
            data: data,
            success: function (response) {
                // Update the product list on the page with the new data
                $('#filteredSearch').html(response);
            }
        });
    }


    /*

    Kategori Checkbox

    */

    $('.kategori-checkbox').change(function () {
        updateKategoriList();
    });

    function updateKategoriList() {
        // Get the selected kategori from the checkboxes
        var kategoriString = "";
        $('.kategori-checkbox:checked').each(function () {
            var kategori = $(this).val();
            if (kategori) {
                if (kategoriString.length > 0) {
                    kategoriString += ",";
                }
                kategoriString += kategori;
            }
        });

        // Get the current URL and create a new search parameters object from it
        var currentUrl = new URL(location.href);
        var searchParams = new URLSearchParams(currentUrl.search);

        // Set the kategori parameter
        searchParams.set("kategori", kategoriString);

        // Update the URL in the address bar and add a new entry to the browser's history
        currentUrl.search = searchParams.toString();
        window.history.pushState({}, '', currentUrl.toString());

        // Get all the parameters from the URL
        var searchParams = new URLSearchParams(location.search);

        // Convert the search params to an object
        var data = {};
        for (var [key, value] of searchParams) {
            data[key] = value;
        }

        // Send an AJAX request to the server to retrieve the updated product list
        $.ajax({
            url: 'filtered_search.php',
            data: data,
            success: function (response) {
                // Update the product list on the page with the new data
                $('#filteredSearch').html(response);
            }
        });
    }

    /*
    
    Sort dropdown

    */

    $('.rlr-js-dropdown-item').on('click', function (e) {
        e.preventDefault();

        var sortBy = $(this).text();
        
        // Get the current URL and create a new search parameters object from it
        var currentUrl = new URL(location.href);
        var searchParams = new URLSearchParams(currentUrl.search);

        // Set the sort parameter
        searchParams.set("sort", sortBy);

        // Update the URL in the address bar and add a new entry to the browser's history
        currentUrl.search = searchParams.toString();
        window.history.pushState({}, '', currentUrl.toString());

        // Get all the parameters from the URL
        var searchParams = new URLSearchParams(location.search);

        // Convert the search params to an object
        var data = {};
        for (var [key, value] of searchParams) {
            data[key] = value;
        }

        // Make an AJAX request to the server to update the sorting
        $.ajax({
            url: 'filtered_search.php',
            data: data,
            success: function (response) {
                // Update the product list on the page with the new data
                $('#filteredSearch').html(response);

                // Update the UI to reflect the new sorting
                $('.rlr-js-dropdown-item').removeClass('active');
                $('.rlr-js-dropdown-button').text(sortBy);
                $(e.target).addClass('active');
            }
        });
    });

    /*

    Search Field

    */

    $('#search').on('keydown', function (e) {
        if (e.keyCode === 13 && $(this).val().trim() !== '') {
         var query = $(this).val().trim();

        // Get the current URL and create a new search parameters object from it
        var currentUrl = new URL(location.href);
        var searchParams = new URLSearchParams(currentUrl.search);

        // Set the query parameter
        searchParams.set("query", query);

        // Update the URL in the address bar and add a new entry to the browser's history
        currentUrl.search = searchParams.toString();
        window.history.pushState({}, '', currentUrl.toString());

        // Get all the parameters from the URL
        var searchParams = new URLSearchParams(location.search);

        // Convert the search params to an object
        var data = {};
        for (var [key, value] of searchParams) {
            data[key] = value;
        }

        // Send an AJAX request to the server to retrieve the updated product list
        $.ajax({
            url: 'filtered_search.php',
            data: data,
            success: function (response) {
                // Update the product list on the page with the new data
                $('#filteredSearch').html(response);
                document.title = query + " - E-Tourism";
            }
        });
        } else if (e.keyCode === 13) {
            window.location.href = "../search";
        }
      });

});