<div>
<h1>Welcome to your card collection!</h1><br>
<p>This is description of this special page with special collection of a cards. Visit more wikis to unlock more fluffy animals!</p><br>
	<div class="cards-game-main-div">
		<?php foreach ( $images as $image ): ?>
			<div class="cards-game-td" >
				<? if ( $image['achived'] ) : ?>
					<img data-id="<?= $image['card_id']; ?>" class="card-game-image" src="<?= $image['card_url']; ?>"
						 width="200"/>
				<? else: ?>
					<img class="card-game-blurred-image" src="<?= $image['card_url']; ?>" width="200"/>
				<? endif; ?>
				<p id="card-game-card-text-main"><?= Sanitizer::encodeAttribute( $image['card_name'] ) ?></p>
				<p id="card-game-card-text-description"><?= Sanitizer::encodeAttribute( $image['card_description'] ) ?></p>
			</div>
		<?php endforeach ?>
	</div>
</div>


<div class="card-game-details-dialog" >
	<div class="wds-dialog__wrapper card-game-details-dialog__wrapper">
		<div class="wds-dialog__title card-game-dialog__title"> </div>
		<div class="wds-dialog__content card-game-dialog__content confirmation-dialog__close-wiki-dialog-footer">
			<img id="popup-image" class="card-game-dialog__card" src="' . $image['card_url'] . '">
		</div>
		<div class="wds-dialog__actions card-game-dialog__actions">
			<button id="tradeCard" value="" class="wds-button card-game-dialog__button-margin">Trade</button>
			<button id="closeCardPopupDetails" class="wds-button card-game-dialog__button-margin">Close</button>
		</div>
	</div>
</div>
