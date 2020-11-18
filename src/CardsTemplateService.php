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
}
