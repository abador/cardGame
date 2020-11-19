console.log(mw.config.get( 'wgServer'));
$( document ).ready(function() {
    $(".card-game-image, .card-game-blurred-image").click(function() {
        $("#tradeCard").val($(this).data('id'));
        $(".card-game-details-dialog").show();

        let currentURL = $(this).attr('src');
        $("#popup-image").attr("src", currentURL);

    });

    $("#closeCardPopupDetails").click(function() {
        $(".card-game-details-dialog").hide();
    });

    $("#tradeCard").click(function() {
        location.href = mw.config.get( 'wgServer') + "/wiki/Special:Cards/Trade?card=" + $(this).val();
    });
});
