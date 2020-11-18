<?php

use Fandom\Includes\Api\FandomApiBase;
use Fandom\Includes\Util\RequestUtilityService;

class ApiGetCard extends FandomApiBase {

	public function run(): void {
	}

	protected function getAllowedParams() {
		return [
			'card_id' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true,
			],
		];
	}

	public function mustBePosted() {
		return true;
	}

	public function needsToken() {
		return !$this->getRequestUtilityService()->isInternalRequest() ? 'csrf' : false;
	}

	public function isWriteMode() {
		return true;
	}

	public function isInternal() {
		return false;
	}

	private function getRequestUtilityService(): RequestUtilityService {
		return new RequestUtilityService( $this->getRequest() );
	}
}
