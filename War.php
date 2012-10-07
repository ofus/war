<?php
/**
 * Author: Andrew Joseph
 * Date: 10/6/12
 * Time: 8:37 PM
 */
class War
{
    /** @var Array */
    protected $hands;

    /** @var Deck */
    protected $deck;

    /** @var mixed */
    protected $winner = FALSE;

    public function __construct()
    {
        $this->deck = new Deck();
        $this->winner = -1;
        $this->hands = array_chunk( $this->deck->getCards(), 26 ); // total cards / number of players = 26
        return $this;
    }

    /**
     * Get player in lead.  In a tie for lead, just get first player.
     * @return int
     */
    public function getPlayerAtLead()
    {
        $playerScore[0] = count( $this->hands[0] );
        $playerScore[1] = count( $this->hands[1] );
        if ( $playerScore[0] == $playerScore[1] ) {
            $this->winner = -1;
            $leader = 0;
        } else {
            $this->winner = $leader = ( $playerScore[0] > $playerScore[1] ) ? 0 : 1;
        }
        return $leader;
    }
    public function isGameOver()
    {
        return ( empty($this->hands[0]) || empty($this->hands[1]) );
    }

    public function executeTurn()
    {
        for ( $pot = Array(); ; ) {
            list( $card0, $card1 )= Array(
                array_pop( $this->hands[0] ),
                array_pop( $this->hands[1] ),
            );
            array_push( $pot, $card0, $card1 );
            if ($card0 == $card1) { // tie, move to next card
                if( $this->isGameOver() ) {
                    return;
                }
                continue;
            }

            $roundWinner = ($card0 > $card1) ? 0 : 1;
            $this->hands[$roundWinner] = array_merge($this->hands[$roundWinner], $pot);
        }

        // which hand is bigger
        //$biggerHand = ( count($this->hands[0]) >= count($this->hands[1]) ) ? $this->hands[0] : $this->hands[1];
        $biggerHand = 0;
        $smallerHand = 1;
        if ( count($this->hands[0]) > count($this->hands[1]) ) {
            $biggerHand = 1;
            $smallerHand = 0;
        }

        // check if all remaining cards in smaller hand will tie with larger hand, if true larger hand holder wins
        $largerHandCardsInPlay = array_slice( $this->hands[$biggerHand], count($this->hands[$smallerHand]) );
        if ( $largerHandCardsInPlay == $this->hands[$smallerHand] ) {
            $this->hands[$biggerHand] = array_merge( $this->hands[$biggerHand], $this->hands[$smallerHand] );
            $this->hands[$smallerHand] = Array();
        }
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
