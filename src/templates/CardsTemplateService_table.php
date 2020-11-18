<div class="cards-game-main-div">
<h1>Welcome to your card collection!</h1><br>
<p>This is description of this special page with special collection of a cards! It contain all cards that you can get when visiting our wikis. Visit more wikis to unlock more fluffy animals!</p><br>
	<table width="250" border="0" cellpadding="2">
	<?php $counter = 1; ?>
		<?php foreach ( $images as $image ): ?>
				<? if ( $counter % 4 === 0 ) : ?>
					<tr>
				<? endif; ?>
				<td class="cards-game-td" align="center" valign="center">
					<? if ( $image['achived'] ) : ?>
						<img src="<?= $image['card_url']; ?>" width="200"/>
					<? else: ?>
						<img class="card-game-blurred-image" src="<?= $image['card_url']; ?>" width="200"/>
					<? endif; ?>
					<p id="card-game-card-text-main"><?= Sanitizer::encodeAttribute( $image['card_name'] ) ?></p>
					<p id="card-game-card-text-description"><?= Sanitizer::encodeAttribute( $image['card_description'] ) ?></p>
				</td>
				<? if ( $counter % 3 === 0 ) : ?>
					</tr>
				<? endif; ?>
				<?php $counter += 1; ?>
			<?php endforeach ?>
	</table>
</div>
