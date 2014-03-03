require([
    'jquery',
    'bootstrap'
], function(
        $,
        Bootstrap
        ) {
    $(function() {
        $('.newManif').click(function() {
            $.ajax({
                type: 'GET',
                url: urlAjoutManifestation,
                success: function(msg) {
                    $('.formManif').html(msg);
                }
            });
        });
    });
});
