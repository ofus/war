<?php

class Deck
{
    protected $cards;

    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Assign cards to the deck and shuffle them
     */
    protected function initialize()
    {
        $this->cards = array_map(
            function ($card_index) {
                return $card_index % 13;
            },
            range(0, 51)
        );
        shuffle($this->cards);
        return $this->cards;
    }

    /**
     * Reshuffle and deal cards to the players
     * @param int $num_players
     * @return array[] Set of cards for each player
     */
    public function deal()
    {
        shuffle( $this->cards );
        return array_chunk( $this->cards, 52 / 2 );
    }

    public function executeTurn(&$hand0, &$hand1)
    {
        for ($pot = Array(); count($hand0) > 0 && count($hand1) > 0; ) {
            $card0 = array_pop($hand0);
            $card1 = array_pop($hand1);
            $pot = array_merge($pot, Array($card0, $card1));
            if ($card0 == $card1) {
                continue;
            }
            // one is larger, award the pot to this player
            if ($card0 > $card1) {
                $hand0 = array_merge($hand0, $pot);
                return 0;
            }
            $hand1 = array_merge($hand1, $pot);
            return 1;
        }

        if ( count($hand0) < 1) {
            return 1;
        }
        return 0;

    }

    /**
     * Print hand to std output
     * @param array $cards
     */
    public static function displayHand(Array $cards)
    {
        $str = implode(" ", array_map(
            function ($cardValue) {
                $values = Array(0 => "deuce", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "jack", "queen", "king", "ace");
                return $values[$cardValue];
            },
            $cards )
        );

        return $str;
    }

}
