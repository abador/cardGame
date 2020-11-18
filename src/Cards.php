<?php

namespace CardGame;

use SpecialPage;

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
        $out = $this->getOutput();

        $html = "<div>CardGame</div>";
        $out->addHTML( $html );

    }
}
