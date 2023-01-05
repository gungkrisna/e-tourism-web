$(document).ready(function () {
  $(document).on('click', '#copySharableLink', function (e) {
    e.preventDefault();
    var link = $('#sharable-link').val();
    // Use the `copy` method to copy the value of the `sharable-link` element
    navigator.clipboard.writeText(link).then(function () {
      console.log('Copied link to clipboard');
    }, function (err) {
      console.error('Failed to copy: ', err);
    });
  });

  $('#wishlistBtn').on('click', function (e) {
    e.preventDefault();
    console.log("Wishlist clicked");
  })


  /*

    [MODAL HANDLER]

  */

  $(window.location.hash).fadeIn(100, 'linear');

  $('#addReviewModalBtn').on('click', function (e) {
    e.preventDefault();
    $('#addReviewModal').fadeIn(100, 'linear');
  });
  $('#closeAddReviewModalBtn').on('click', function (e) {
    e.preventDefault();
    $('#addReviewModal').fadeOut(100, 'linear');
  });
  $(document).click(function (e) {
    if ($(e.target).is('#addReviewModal')) {
      $('#addReviewModal').fadeOut(100, 'linear');
    }
  });

  $('#showReviewModalBtn').on('click', function (e) {
    e.preventDefault();
    $('#showReviewModal').fadeIn(100, 'linear');
  });
  $('#closeShowReviewModalBtn').on('click', function (e) {
    e.preventDefault();
    $('#showReviewModal').fadeOut(100, 'linear');
  });
  $(document).click(function (e) {
    if ($(e.target).is('#showReviewModal')) {
      $('#showReviewModal').fadeOut(100, 'linear');
    }
  });
  if (location.href.indexOf('#showReviewModal') != -1) {
    e.preventDefault();
    $('#showReviewModal').fadeIn(100, 'linear');
  };


  $(document).on('click', '.button-report-review', function (e) {
    e.preventDefault();
    $('#id_ulasan').val($(this).data('id-ulasan'));
    $('#reportReviewModal').fadeIn(100, 'linear');
  });

  $(document).on('click', '#closeReportReviewModalBtn', function (e) {
    e.preventDefault();
    $('#reportReviewModal').fadeOut(100, 'linear');
  });

  $(document).click(function (e) {
    if ($(e.target).is('#reportReviewModal')) {
      $('#reportReviewModal').fadeOut(100, 'linear');
    }
  });

  /*

    [REVIEW STAR HANDLER]

  */

  $('#stars li').on('mouseover', function () {
    var onStar = parseInt($(this).data('value'), 10);

    $(this).parent().children('li.star').each(function (e) {
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });

  }).on('mouseout', function () {
    $(this).parent().children('li.star').each(function (e) {
      $(this).removeClass('hover');
    });
  });


  $('#stars li').on('click', function () {
    var onStar = parseInt($(this).data('value'), 10);
    var stars = $(this).parent().children('li.star');

    $('#rating').val(onStar);

    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }

    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }

  });
});


/*

  [ALL REVIEW]

*/

let param = {
  refresh: true,
};

function getReviews() {
  if (param.refresh) {
    $('.review-wrapper').empty();
  }

  $.ajax({
    url: 'getReviews',
    data: param,
    success: function (response) {
      $('.review-wrapper').append(response);
    }
  });
}

/*
    
    Sort dropdown

*/

$('.rlr-js-dropdown-item').on('click', function (e) {
  e.preventDefault();
  var sortBy = $(this).text();

  param.refresh = true;
  param.sort = sortBy;
  getReviews(param);
});

/*

  Rating checkbox

*/

// Set up event handler for checkbox changes
$('.rating-checkbox').change(function () {
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

  param.refresh = true;
  param.stars = ratingString;
  getReviews(param);
});

$('#searchReview').on('keydown', function (e) {
  if (e.keyCode === 13 && $(this).val().trim() !== '') {
    param.refresh = true;
    param.keyword = $(this).val().trim();
    getReviews(param);
  }
});