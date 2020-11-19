$( document ).ready(function() {
    $("img").click(function() {
        $(".card-game-details-dialog").show();

        let currentURL = $(this).attr('src');
        $("#popup-image").attr("src", currentURL);

    });

    $("#closeCardPopupDetails").click(function() {
        $(".card-game-details-dialog").hide();
    });
});
