<?php

namespace CardGame;

use WikiaService;

/**
 * Class CardsTemplateService
 *
 * Keep this class even if it's empty.
 * It allows to render HTML templates.
 *
 * @package \CardGame
 */
class CardsTemplateService extends WikiaService {
	public function table() {
		$images = $this->getVal( 'images' );

		$this->response->setValues( [
			'images' => $images
		] );
	}
	public function trade() {
		$card = $this->getVal( 'card' );
		$target = $this->getVal( 'target' );
		$you = $this->getVal( 'you' );
		$avatars = $this->getVal( 'avatars' );

		$this->response->setValues( [
			'card' => $card,
			'target' => $target,
			'you' => $you,
			'avatars' => $avatars
		] );
	}
	public function nouser() {
		$card = $this->getVal( 'card' );
		$target = $this->getVal( 'target' );

		$this->response->setValues( [
			'card' => $card,
			'target' => $target
		] );
	}
	public function traded() {
	}
}
