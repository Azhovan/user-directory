/**
 *
 *
 *
 *
 * User  Directory Object
 * @type {{search: UserDirectory.search}}
 */

var UserDirectory = {

    /**
     * query elastic server by given term
     * @param term
     * @param token
     */
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
                var response = JSON.parse(data);
                if (typeof response.response.status !== 'undefined')
                    if (response.response.status == config.error) {
                        alert("Your search did not match any documents");
                    } else {
                        UserDirectory.parsData(data);
                    }
            }
        });
    },


    /**
     * add friend to user list
     * @param id
     * @param token
     */
    addFriend: function (id) {

        if (!id || 0 === id.length) {
            alert("Fatal Error. Cannot read the data")
        }

        $.ajax({
            type: 'POST',
            url: config.add_friend_route,
            cache: false,
            data: {
                "id": id,
                "_token": $('._token').val()
            },
            success: function (data) {
                var response = JSON.parse(data);
                if (response.response.status == config.error) {
                    $('.modal-header').text('Ops...');
                    $('.modal-title').text('Your action failed');
                    $('.modal-body').text('Please note that you cannot add yourself or you can just add every person one time !');
                    //$('.card').fadeIn('fast');
                } else {
                    $('.modal-header').text('Great !');
                    $('.modal-title').text('user added to your list successfully .');
                    $('.modal-body').text('You successfully added this person .');
                }
            }
        });
    },


    /**
     * make data human readable and show in table
     * @param collection
     */
    parsData: function (collection) {

        var data = JSON.parse(collection);

        var table = $('<table></table>').addClass('"table table-hover"').css({width: "100%", "margin-top": "30"});

        var thead = $('<thead></thead>');
        var tr = $('<tr></tr>');

        var thName = $('<th>Name</th>');
        var thEmail = $('<th>Email</th>');
        var thAge = $('<th>Age</th>');
        var thRow = $('<th>#</th>');
        var thAddToFriends = $('<th></th>');
        var thViewProfile = $('<th></th>');

        tr.append(thRow);
        tr.append(thName);
        tr.append(thEmail);
        tr.append(thAge);
        tr.append(thAddToFriends);
        tr.append(thViewProfile);

        thead.append(tr);

        table.append(thead);
        var tbody = $('<tbody></tbody>');

        for (var i = 0; i < data.length; i++) {
            row = $('<tr></tr>');

            url = config.view_profile_route + '/' + data[i]['id'];

            rowDataRow = $('<td width="50px"></td>').text((i + 1));
            rowDataName = $('<td></td>').text(data[i]['name']);
            rowDataEmail = $('<td></td>').text(data[i]['email']);
            rowDataAge = $('<td></td>').text(data[i]['age']);
            rowDataAddFriend = $('<td><button class="btn btn-amber btn-sm add_friend" data-toggle="modal" data-target="#myModal" id=" ' + data[i]['id'] + ' "> Add as friend</button></td>');
            rowDataViewProfile = $('<td><a href=" ' + url + '"><button class="btn btn-cyan btn-sm view_profile" id="' + data[i]['id'] + '"> View Profile</button></a></td>');

            row.append(rowDataRow);
            row.append(rowDataName);
            row.append(rowDataEmail);
            row.append(rowDataAge);
            row.append(rowDataAddFriend);
            row.append(rowDataViewProfile);
            tbody.append(row);

        }
        table.append(tbody);

        // output result
        $('.search-result').html('').append(table);

        // binding
        $('.add_friend').each(function (index, element) {
            $(element).click(function () {
                UserDirectory.addFriend(
                    $(this).attr('id')
                )

            });

        })

    }


};


$('.search').click(function () {

    UserDirectory.search(
        $('.search-input').val(),
        $('._token').val()
    );

});




