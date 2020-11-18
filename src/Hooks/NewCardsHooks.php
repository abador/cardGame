<?php
/**
 * Created by PhpStorm.
 * User: abador
 * Date: 18.11.20
 * Time: 10:31
 */

namespace CardGame\Hooks;


use MediaWiki\MediaWikiServices;
use OutputPage;
use Skin;
use Wikimedia\Rdbms\IDatabase;

class NewCardsHooks {
	const MAX_UNIQUE = 1.844674407371E+19;
	const PROBABILITY = 0;
	const DISABLED_NAMESPACES = [ NS_SPECIAL, NS_MEDIA, NS_TEMPLATE ];

	private static function cardFound( ?OutputPage $out ): bool {
		global $wgUser;
		if ( $wgUser->isAnon() ) {
			return false;
		}
		if ( is_null( $out ) ) {
			return false;
		}
		$title = $out->getTitle();
		$token = $_SERVER['UNIQUE_ID'];
		if ( !is_null( $title ) && $title->inNamespaces( self::DISABLED_NAMESPACES ) ) {
			return false;
		}
		$probability = hexdec( $token ) / self::MAX_UNIQUE ;
		if ( $probability > self::PROBABILITY ) {
			$card = self::getCard( $probability*100 );
			$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
			$cache->set( $token, $card, 360 );
			$out->addJsConfigVars( [
				'cgCardFound' => true,
				'cgCard' => $card,
				'cgToken' => $token
			] );
			return true;
		}
		return false;
	}

	private static function getCard( float $probability ): array {
		return self::getByProbability( $probability );
	}

	/**
	 * @return \Wikimedia\Rdbms\IDatabase
	 * @throws \ConfigException
	 */
	public static function getDbConnection(): IDatabase {
		$services = MediaWikiServices::getInstance();
		$sharedDB = $services->getMainConfig()->get( 'ExternalSharedDB' );
		return $services
			->getDBLoadBalancerFactory()
			->getExternalLB( $sharedDB )
			->getConnection( DB_REPLICA );
	}

	/**
	 *
	 * @return array|bool
	 * @throws \ConfigException
	 */
	public static function getByProbability( float $probability ) {
		$dbr = self::getDbConnection();
		$res = $dbr->select(
			[ 'card_game.card' ],
			[ '*' ],
			[ "frequency > $probability" ],
			__METHOD__,
			[ 	'ORDER BY' => 'RAND()',
				 'LIMIT' => 1 ]
		);
		return $res->fetchRow();
	}

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
        global $wgTitle;
        if ( !is_null( $wgTitle ) && $wgTitle->isSpecial( 'Cards' ) ) {
            $out->addModuleStyles( 'ext.CardGame.table.styles' );
        }
        if ( !self::cardFound( $out ) ) {
        	return true;
		}

        $out->addModuleStyles( 'ext.CardGame.popup.styles' );
        $out->addModules( 'ext.CardGame.popup.scripts' );
        return true;
	}

	public static function onSkinAfterContent(  &$data, Skin $skin ) {
		$token = $_SERVER['UNIQUE_ID'];
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$card = $cache->get( $token );
		if ( !$card ) {
			return true;
		}
		$data .= '<div class="card-game-dialog" style="display:none;">
				<div class="wds-dialog__wrapper card-game-dialog__wrapper">
					<div class="wds-dialog__title card-game-dialog__title">'
				 . wfMessage( 'found-a-card-title' , $card['card_name'] ) .
				 	'<img class="card-game-dialog__icon"
					src="https://static.fandom-dev.pl/abador8/images/2/2a/Win.gif/revision/latest/scale-to-width-down/100?cb=20201118193607" />
					</div>
					<div 
					class="wds-dialog__content card-game-dialog__content confirmation-dialog__close-wiki-dialog-footer">
						<img class="card-game-dialog__card" src="' . $card['card_url'] . '">' . wfMessage( 'found-a-card' ) . '
					</div>
					<div class="wds-dialog__actions card-game-dialog__actions">
						<button id="collectCard" class="wds-button wds-button-red" data-id="' . $card['card_id'] . '">
							Collect
						</button>
					</div>
				</div>
			</div>';
		return true;
	}


}
