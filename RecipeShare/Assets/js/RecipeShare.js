$(document).ready(function() {
    $('#search_text_input').focus(function() {
        if (window.matchMedia("(min-width: 800px)").matches) {
            $(this).animate({ width: '250px' }, 500);
        }
    });
    $('#button_holder').on('click', function() {
            document.search_form.submit();
        })
        //button for profile posts
    $('#submit_profile_post').click(function() {

        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_submit_profile_post.php",
            data: $('form.profile_post').serialize(),
            success: function(msg) {
                $("#post_form").modal('hide');
                location.reload();
            },
            error: function() {
                alert('Failure');
            }
        });

    });

});

function getUsers(value, user) {
    $.post("includes/handlers/ajax_friend_search.php", { query: value, userLoggedIn: user }, function(data) {
        $(".results").html(data);
    });
}

function getLiveSearchUsers(value, user) {

    $.post("includes/handlers/ajax_search.php", { query: value, userLoggedIn: user }, function(data) {

        if ($(".search_results_footer_empty")[0]) {
            $(".search_results_footer_empty").toggleClass("search_results_footer");
            $(".search_results_footer_empty").toggleClass("search_results_footer_empty");
        }

        $('.search_results').html(data);
        $('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");

        if (data == "") {
            $('.search_results_footer').html("");
            $('.search_results_footer').toggleClass("search_results_footer_empty");
            $('.search_results_footer').toggleClass("search_results_footer");
        }

    });

}