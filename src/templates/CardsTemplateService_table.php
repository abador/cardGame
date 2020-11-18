<div>
	<?php foreach ( $images as $image ): ?>
		<tr>
			<td>
				<? if ( $image['achived'] ) : ?>
					<img src="<?= $image['card_url']; ?>" width="200"/>
				<? else: ?>
					<img src="<?= $image['card_url']; ?>" width="200" style="filter: blur(5px)"/>
				<? endif; ?>
			</td>
			<td>
				<p><?= Sanitizer::encodeAttribute( $image['card_name'] ) ?></p>
			</td>
			<td>
				<p><?= Sanitizer::encodeAttribute( $image['card_description'] ) ?></p>
			</td>
		</tr>
	<?php endforeach ?>
</div>
