<div>
<h1>Welcome to your card collection!</h1><br>
<p>This is description of this special page with special collection of a cards. Visit more wikis to unlock more fluffy animals!</p><br>
	<div class="cards-game-main-div">
		<?php foreach ( $images as $image ): ?>
			<div class="cards-game-td" >
				<? if ( $image['achived'] ) : ?>
					<img class="card-game-image" src="<?= $image['card_url']; ?>" width="200"/>
				<? else: ?>
					<img class="card-game-blurred-image" src="<?= $image['card_url']; ?>" width="200"/>
				<? endif; ?>
				<p id="card-game-card-text-main"><?= Sanitizer::encodeAttribute( $image['card_name'] ) ?></p>
				<p id="card-game-card-text-description"><?= Sanitizer::encodeAttribute( $image['card_description'] ) ?></p>
			</div>
		<?php endforeach ?>
	</div>
</div>
