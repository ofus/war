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

    /** @var String[] */
    public $log;

    /** @var int */
    protected $turn;

    /** @var Deck */
    protected $deck;

    /** @var mixed */
    protected $winner = FALSE;

    public function __construct()
    {
        $this->deck = new Deck();
        $this->winner = -1;
        $this->turn = 0;
        $this->hands = array_chunk( $this->deck->getCards(), 26 ); // total cards / number of players = 26
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
        return ( empty( $this->hands[0] ) || empty( $this->hands[1] ) );
    }

    public function getTurn()
    {
        return $this->turn;
    }

    public function getScore($player)
    {
        return count( $this->hands[$player] );
    }

    /**
     * @return string or false if game not over
     */
    public function getWinner()
    {
        if ( !$this->isGameOver() ) {
            return FALSE;
        }
        if ( $this->winner == -1 ) {
            return "tie";
        }
        return "Player " . $this->winner;
    }

    /**
     * Draw another card for each player
     * @param array $pot (used in recursion)
     */
    public function draw( Array $pot = NULL )
    {
        if( $this->isGameOver() ) {
            return;
        }
        if ( is_null($pot) ) {
            $pot = Array();
        }
        $this->turn++;
        list( $card0, $card1 )= Array(
            array_pop( $this->hands[0] ),
            array_pop( $this->hands[1] ),
        );

        array_push( $pot, $card0, $card1 );
        if ($card0 != $card1) {
            $roundWinner = ($card0 > $card1) ? 0 : 1;
            $potstr = $this->cardToString($pot);
            $this->log[$this->getTurn()] = "Player $roundWinner plays "
                . $this->cardToString($card0)
                . " against "
                . $this->cardToString($card1)
                . " to win the pot: " . $potstr
            ;
            //$this->hands[$roundWinner] = $this->hands[$roundWinner] + $pot;
            $tmp = $this->hands[$roundWinner];
            $this->hands[$roundWinner] = array_merge( $tmp, $pot );
        } else {    // tie, draw again
            $this->draw( $pot );
        }
        return;
    }

    /**
     * Print hand to std output
     * @param array $cards
     */
    public function displayHand($player, $compact = FALSE)
    {
        if ($compact) {
            $values = Array(0 => "2", "3", "4", "5", "6", "7", "8", "9", "T", "J", "Q", "K", "A");
        } else {
            $values = Array(0 => "deuce", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "jack", "queen", "king", "ace");
        }
        $str = implode("",
            array_map(
                function ($cardValue) use ($values) {
                    return $values[$cardValue];
                },
                $this->hands[$player]
            )
        );

        return $str;
    }

    public function cardToString($value)
    {
        $values = Array(0 => "2", "3", "4", "5", "6", "7", "8", "9", "T", "J", "Q", "K", "A");
        if (is_array($value)) {
            $str = '';
            foreach($value as $v) {
                $str .= $values[$v];
            }
            return $str;
        }
        return $values[$value];
    }

}
