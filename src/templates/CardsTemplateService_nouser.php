<div>
<h1>Please select a user to trade with</h1><br>
<div class="cards-game-main-div">
	<?php if ( is_null($target) || $target->getId() == 0 ) { ?>
		<div style="color:red;">
			This user doesn't exist
		</div>
	<?php } ?>
	<form method="get" >
		<label for="user">Username:</label><input type="text" name="user" id="user" \>
		<input type="hidden" name="card" value="<?=$card->card_id; ?>">
		<input type="submit" value="Check User">
	</form>

</div>
</div>
