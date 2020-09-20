<?php namespace IsraelPost;

use Symfony\Component\DomCrawler\Crawler;

class Reader extends ServiceAbstract{
    private array $countries;
    private array $countriesMap = [
        'תורכיה' => 'טורקיה',
        'שוויצריה' => 'שווייץ',
        'שבדיה' => 'שוודיה',
        "צ'כיה" => 'צ׳כיה',
        'סיישל (איים)' => 'איי סיישל',
        'הונג-קונג' => 'הונג קונג (אזור מנהלי מיוחד של סין)',
        'בלרוס' => 'בלארוס',
        'בוסניה - הרצגובינה' => 'בוסניה והרצגובינה',
        'ארה"ב (ארצות הברית)' => 'ארצות הברית',
        "אזרבאיג'ן" => 'אזרבייג׳ן',
        "צ'ילה" => 'צ׳ילה'
    ];

    public function __construct() {
        $this->countries = include './vendor/umpirsky/country-list/data/he/country.php';

        parent::__construct('https://www.israelpost.co.il');
    }

    /**
     * @throws IsraelPostException
     */
    public function countries(string $file, string $selector) {
        $html       = file_get_contents($file);
        $crawler    = new Crawler($html);
        $data       = [];

        $crawler->filter("{$selector} > option")->each(function(Crawler $node) use (&$data) {
            $country    = trim($node->text());
            $code       = array_search($country, $this->countries);
            $value      = (int)$node->attr('value');

            if (!$code && isset($this->countriesMap[$country])) {
                $code = array_search($this->countriesMap[$country], $this->countries);
            }

            if ($code) {
                $data[$code] = ['country' => $country, 'value' => $value];
            }
        });

        return $data;
    }
}
