<?php

namespace CardGame;

use F;
use FandomServices;
use MediaWiki\MediaWikiServices;
use SpecialPage;
use User;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class Cards
 *
 * @package \CardGame
 */
class Cards extends SpecialPage {

	const SUBPAGE_TRADE = "Trade";

    public function __construct() {
        parent::__construct( 'Cards', '', true );
    }

    public function execute( $subpage ) {
    	switch ( $subpage ) {
			case null :
				$this->showCardList();
				break;
			case self::SUBPAGE_TRADE :
				if ( $this->getRequest()->getMethod() == 'POST' ) {
					$this->tradeWithUser();
				} else {
					$this->showTrade();
				}
				break;
			default:
				$this->showCardList();
		}
    }

	public function tradeWithUser() {
		$targetUser = User::newFromName( $this->getRequest()->getVal( 'user' ) );
		$targetUser->load();
		$cardId = $this->getRequest()->getVal( 'card' );
		$this->update($cardId, $targetUser->getId());
		$html = F::app()->renderPartial(
			CardsTemplateService::class,
			'traded',
			[]
		);
		$out = $this->getOutput();
		$out->addHTML( $html );
	}

	public function update( $cardId, $userId ) {
    	$dbw = $this->getDbConnection( DB_MASTER );
		$table = $dbw->tableName( 'card_game.card_owners' );

		$values = [
			'user_id' => $userId
		];
		$conds = [
			'card_id' => $cardId,
		];
		$sql = "UPDATE $table SET " . $dbw->makeList( $values, Database::LIST_SET );

		if ( $conds !== [] && $conds !== '*' ) {
			$sql .= " WHERE " . $dbw->makeList( $conds, Database::LIST_AND );
		}
		$sql .= ' LIMIT 1';
		return $dbw->query( $sql, __METHOD__ );
	}

	public function showTrade() {
    	global $wgUser;
		$userId = $wgUser->getId();
		$avatars = [ $userId ];
		$avatarProvider = FandomServices::getUserAvatarProvider();
		$cardId = $this->getRequest()->getVal( 'card' );
		$targetUser = User::newFromName( $this->getRequest()->getVal( 'user' ) );
		if ( $targetUser ) {
			$targetUser->load();
		}
		if ( $targetUser && $targetUser->getId() != 0 ) {
			$avatars[] = $targetUser->getId();
		}
		$out = $this->getOutput();
		$out->addModuleStyles( 'ext.CardGame.trade.styles' );
		$out->addModuleStyles( 'ext.CardGame.table.styles' );
		$card = $this->getCardForUser( $userId, $cardId );
		$tmplData = [
			'card' => $card,
			'target' => $targetUser,
			'you' => $wgUser,
			'avatars' => $avatarProvider->getAvatarsForUserIds( $avatars, 200 )
		];
		$html = $this->generateTradePage( $tmplData );
		$out->addHTML( $html );
	}

	public function showCardList() {
		$user = \RequestContext::getMain()->getUser();
		//todo: fix dirty hack, getId() and getActorId returns 0
		$userId = $user->mId;

		$out = $this->getOutput();
		$out->addModuleStyles( 'ext.CardGame.table.styles' );
		$out->addModules( 'ext.CardGame.table.scripts' );

		$userImages = $this->getUserImages( $userId );
		$images = $this->getAllImages( $userImages );

		$tmplData = [
			'images' => $images
		];
		$html = $this->generateContent( $tmplData );
		$out->addHTML( $html );
	}


	/**
	 * @param int $con
	 * @return \Wikimedia\Rdbms\IDatabase
	 * @throws \ConfigException
	 */
    public function getDbConnection( $con = DB_REPLICA ): IDatabase {
        $services = MediaWikiServices::getInstance();
        $sharedDB = $services->getMainConfig()->get( 'ExternalSharedDB' );
        return $services
            ->getDBLoadBalancerFactory()
            ->getExternalLB( $sharedDB )
            ->getConnection( $con );
    }

	/**
	 *
	 * @return Object|bool
	 * @throws \ConfigException
	 */
	public function getCardForUser( int $userId, int $cardId ) {
		$dbr = $this->getDbConnection();
		$result = $dbr->select(
			[ 'c' => 'card_game.card',  'co' => 'card_game.card_owners' ],
			[
				'c.*',
			],
			[
				'co.user_id' => $userId,
				'c.card_id' => $cardId
			],
			__METHOD__,
			[
				'LIMIT' => 1,
			],
			[
				'co' => [ 'INNER JOIN', ' co.card_id = c.card_id' ],
			]
		);
//		var_dump($result);die;
		return $result->fetchObject();
	}

	/**
	 *
	 * @return array
	 * @throws \ConfigException
	 */
	public function getAllImages( array $userImages ): array {
		$dbr = $this->getDbConnection();
		$res = $dbr->select(
			[ 'card_game.card' ],
			[ '*' ],
			[],
			__METHOD__,
			[ 'LIMIT 30' ]
		);

		$images = [];
		foreach ( $res as $row ) {
			$achived = false;
			if ( in_array( $row->card_id, $userImages ) ) {
				$achived = true;
			}
			$images[] = [
				'card_id' => $row->card_id,
				'card_name' => $row->card_name,
				'card_url' => $row->card_url,
				'card_description' => $row->card_description,
				'wiki_id' => $row->wiki_id,
				'achived' => $achived,
			];
		}
		return $images;
	}

    public function getUserImages( int $userId ): array {
        $dbr = $this->getDbConnection();
        $res = $dbr->select(
            [ 'card_game.card_owners' ],
            [ 'card_id' ],
            [ 'user_id' => $userId ]
        );

        $images = [];
        foreach ( $res as $row ) {
            $images[] = $row->card_id;
        }
        return $images;
    }

	public function generateContent( array $tmplData ): string {
		return F::app()->renderPartial(
			CardsTemplateService::class,
			'table',
			$tmplData
		);
	}

	public function generateTradePage( array $tmplData ): string {
		if ( !$tmplData['target'] || $tmplData['target']->getId() == 0 ) {
			return F::app()->renderPartial(
				CardsTemplateService::class,
				'nouser',
				$tmplData
			);
		}
		return F::app()->renderPartial(
			CardsTemplateService::class,
			'trade',
			$tmplData
		);
	}
}
