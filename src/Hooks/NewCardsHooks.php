<?php
/**
 * Created by PhpStorm.
 * User: abador
 * Date: 18.11.20
 * Time: 10:31
 */

namespace CardGame\Hooks;


use OutputPage;
use RequestContext;
use Skin;

class NewCardsHooks {
	const MAX_UNIQUE = 1.844674407371E+19;

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( 'ext.CardGame.popup.styles' );
		$out->addModules( 'ext.CardGame.popup.scripts' );
		$beacon = RequestContext::getMain()->getRequest()->getCookie( 'wikia_beacon_id', '', '' );
//		var_dump($beacon, hexdec($_SERVER['UNIQUE_ID'])/self::MAX_UNIQUE);die;
	}
}