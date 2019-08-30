define(['jquery', 'core/str', 'core/notification'], 
    function($, str, notification) {


        var confirmDelete = function(href) {
            // Put whatever you like here. $ is available
            // to you as normal.
            $(".deleteentry").click(function() {
        str.get_strings([
            {key: 'delete'},
            {key: 'title', component: 'tool_kholland'},
            {key: 'yes'},
            {key: 'no'}
        ]).done(function(s) {
                notification.confirm(s[0], s[1], s[2], s[3], function() {
                    window.location.href = href;
                });
            }
        ).fail(notification.exception);

            });
        }
});
