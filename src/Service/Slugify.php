<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $input);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}