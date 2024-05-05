<?php

namespace App\Service;

use Snipe\BanBuilder\CensorWords;

class ProfanityFilter {
    private $censor;

    public function __construct() {
        $this->censor = new CensorWords(); // Ensure CensorWords class is available and properly namespaced
    }

    public function filterText(string $text): string {
        $result = $this->censor->censorString($text);
        return $result['clean'];
    }
}
