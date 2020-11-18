<?php
namespace CardGame\Api;

use ApiBase;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

class ApiGetCard extends ApiBase {

	protected function getAllowedParams() {
		return [
			'card_id' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true,
			],
			'card_token' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			],
		];
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function isInternal() {
		return false;
	}
	public function execute() {
		global $wgUser;
		if ( $wgUser->isAnon() ) {
			$this->getResult()->addValue( null, 'success', false );
			return;
		}
		$params = $this->extractRequestParams();
		$token = $params['card_token'];
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$card = $cache->get( $token );
		if ( !$card ) {
			$this->getResult()->addValue( null, 'success', false );
			return;
		}
		$this->getResult()->addValue( null, 'success', true );
		$cache->delete( $token );
		$this->getDbConnection()->insert(
			'card_game.card_owners',
			[
				'card_id' => $params['card_id'],
				'user_id' => $wgUser->getId()
			],
			__METHOD__
		);
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
			->getConnection( DB_MASTER );
	}
}
