$(document).ready(function () {
    /*
    
    Sort dropdown

    */

    $('.rlr-js-dropdown-item').on('click', function (e) {
        e.preventDefault();

        var sortBy = $(this).attr('id');

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
            url: 'filtered_blog.php',
            data: data,
            success: function (response) {
                // Update the product list on the page with the new data
                $('#filteredBlog').html(response);

                // Update the UI to reflect the new sorting
                $('.rlr-js-dropdown-item').removeClass('active');
                $('.rlr-js-dropdown-button').text(sortBy == 'DESC' ? 'Terbaru' : 'Terlama');
                $(e.target).addClass('active');
            }
        });
    });

    /*

    Search Field

    */

    $('#search').on('keydown', function (e) {
        if (e.keyCode === 13 && $(this).val().trim() !== '') {
            var keyword = $(this).val().trim();

            var currentUrl = new URL(location.href);
            var searchParams = new URLSearchParams(currentUrl.search);

            searchParams.set("keyword", keyword);

            currentUrl.search = searchParams.toString();
            window.history.pushState({}, '', currentUrl.toString());

            var searchParams = new URLSearchParams(location.search);

            var data = {};
            for (var [key, value] of searchParams) {
                data[key] = value;
            }

            $.ajax({
                url: 'filtered_blog.php',
                data: data,
                success: function (response) {
                    // Update the product list on the page with the new data
                    $('#filteredBlog').html(response);
                    document.title = keyword + " - E-Tourism";
                }
            });
        } else if (e.keyCode === 13) {
            window.location.href = "../blog";
        }
    });

});