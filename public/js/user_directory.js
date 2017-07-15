/**
 *
 *
 *
 *
 * User  Directory Object
 * @type {{search: UserDirectory.search}}
 */

var UserDirectory = {

    search: function (term, token) {

        if (term.length == 0) {
            alert('Please enter valid term . nothing is submited');
            return;
        }

        $.ajax({
            type: 'POST',
            url: config.routes,
            cache: false,
            data: {
                "term": term,
                '_token': token
            },
            success: function (data) {
                console.log(data);
            }
        });


    }


};


$('.search').click(function () {

    UserDirectory.search(
        $('.search-input').val(),
        $('._token').val()
    );

});


