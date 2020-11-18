<?php
/**
 * Created by PhpStorm.
 * User: abador
 * Date: 18.11.20
 * Time: 10:31
 */

namespace CardGame\Hooks;


use OutputPage;
use Skin;

class NewCardsHooks {
	const MAX_UNIQUE = 1.844674407371E+19;

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( 'ext.CardGame.popup.styles' );
		$out->addModules( 'ext.CardGame.popup.scripts' );
//		var_dump($beacon, hexdec($_SERVER['UNIQUE_ID'])/self::MAX_UNIQUE);die;
	}

	public static function onSkinAfterContent(  &$data, Skin $skin ) {
		$data .= "<div class=\"card-game-dialog\">
				<div class=\"wds-dialog__wrapper\">
					<div class=\"wds-dialog__title\">" . wfMessage( 'found-a-card-title' ) . "</div>
					<div class=\"wds-dialog__actions confirmation-dialog__close-wiki-dialog-footer\">
						" . wfMessage( 'found-a-card' ) . "
					</div>
					<div class=\"wds-dialog__image\">
						<img src=\"https://static.fandom-dev.pl/witkowoucp/images/a/a5/122966374_4083171198366619_9179812595432571142_o.jpg/revision/latest?cb=20201029084712\" width=\"50\" >
					</div>
					<div class=\"wds-dialog__actions\">
						<button class=\"wds-button wds-button-red\">
							Collect
						</button>
					</div>
				</div>
			</div>";
	}


}