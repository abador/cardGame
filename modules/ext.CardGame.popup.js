if(mw.config.get('cgCardFound')){
	$(".card-game-dialog").show();
}
var $collectCard = $("#collectCard");
$collectCard.click(function() {
	if ( $collectCard.hasClass('wds-button-disabled') ) {
		return;
	}
	$collectCard.addClass('wds-button-disabled');
	$collectCard.removeClass('wds-button-red');
	var api = new mw.Api();
	api.post(
		{
			action: "getcard",
			card_id: mw.config.get('cgCard')['card_id'],
			card_token: mw.config.get('cgToken')
		}
	)
		.done(function( msg ) {
			$(".card-game-dialog__actions").html(mw.message( 'found-a-card-collected' ).plain());
		});
});
