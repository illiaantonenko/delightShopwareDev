<?php

namespace RevanSloganOfTheDay\Components;

class SloganPrinter
{
    public function getSlogan(){
        $slogans = [
            'An apple a day keeps the doctor away',
            'Let’s get ready to rumble',
            'A rolling stone gathers no moss',
            'Every apple does get better everyday'
        ];
        return array_rand(array_flip($slogans));
    }
}