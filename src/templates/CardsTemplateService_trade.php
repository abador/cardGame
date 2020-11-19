<div>
<h1>Welcome to card trade!</h1><br>
	<p>You are about to trade a card with another user</p>
	<div class="card-trade-steps" >
		<div class="card-trade-step">
			<div class="card-trade-step__avatar">
				<img class="card-trade-step__image" src="<?= $avatars[$you->getID()]; ?>" alt="<?= $you->getName(); ?>"
					 title="<?= $you->getName(); ?>" itemprop="image">
				<div class="card-trade-step__name">
					<?= $you->getName(); ?>
				</div>
			</div>
		</div>
		<div class="card-trade-step">
			<img class="card-trade-step__arrow" src="https://i.pinimg.com/564x/f7/4d/30/f74d3003e8f46b56021e42c6515a831d
		.jpg" />
		</div>
		<div class="card-trade-step">
			<div class="cards-game-td" >
				<img class="card-game-image" src="<?= $card->card_url; ?>" width="200"/>
				<p id="card-game-card-text-main"><?= Sanitizer::encodeAttribute( $card->card_name ) ?></p>
				<p id="card-game-card-text-description"><?= Sanitizer::encodeAttribute( $card->card_description ) ?></p>
			</div>
		</div>
		<div class="card-trade-step">
			<img class="card-trade-step__arrow" src="https://i.pinimg.com/564x/f7/4d/30/f74d3003e8f46b56021e42c6515a831d
		.jpg" />
		</div>
		<div class="card-trade-step">
			<div class="card-trade-step__avatar">
				<img class="card-trade-step__image" src="<?= $avatars[$target->getID()]; ?>" alt="<?= $target->getName(); ?>"
					 title="<?= $target->getName(); ?>" itemprop="image">
				<div class="card-trade-step__name">
					<?= $target->getName(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="wds-dialog__actions card-trade-step__actions">
		<form method="post" >
			<input type="submit" id="trade" class="wds-button card-trade-step__trade"
				   value="Trade" >
		</form>
	</div>
</div>
