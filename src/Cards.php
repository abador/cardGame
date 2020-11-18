<?php

namespace CardGame;

use F;
use MediaWiki\MediaWikiServices;
use SpecialPage;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class Cards
 *
 * @package \CardGame
 */
class Cards extends SpecialPage {

    public function __construct() {
        parent::__construct( 'Cards', '', false );
    }

    public function execute( $subpage ) {
        $user = \RequestContext::getMain()->getUser();
        //todo: fix dirty hack, getId() and getActorId returns 0
        $userId = $user->mId;

        $out = $this->getOutput();

        $userImages = $this->getUserImages( $userId );
        $images = $this->getAllImages( $userImages );

        $tmplData = [
            'images' => $images
        ];
        $html = $this->generateContent( $tmplData );
        $out->addHTML( $html );

    }

    /**
     * @return \Wikimedia\Rdbms\IDatabase
     * @throws \ConfigException
     */
    public function getDbConnection(): IDatabase {
        $services = MediaWikiServices::getInstance();
        $sharedDB = $services->getMainConfig()->get( 'ExternalSharedDB' );
        return $services
            ->getDBLoadBalancerFactory()
            ->getExternalLB( $sharedDB )
            ->getConnection( DB_REPLICA );
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
}
