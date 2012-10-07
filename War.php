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
    protected $log;

    /** @var int */
    protected $turn;

    /** @var Deck */
    protected $deck;

    /** @var mixed */
    protected $winner;

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
            $this->winner = ( $playerScore[0] > $playerScore[1] ) ? 0 : 1;
            $leader = $this->winner;
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

    public function getLog( $turn = NULL )
    {
        if ( is_null( $turn ) ) {
            return $this->log;
        }
        return $this->log[$turn];
    }

    public function log($msg)
    {
        if ( $this->log[$this->turn] != '' ) {
            throw new Exception('should not be a log here yet');
        }
        $this->log[$this->turn] = $msg;
        return $this;
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
        $this->getPlayerAtLead();
        if ( $this->winner === -1 ) {
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
        $card0 = array_pop( $this->hands[0] );
        $card1 = array_pop( $this->hands[1] );

        array_push( $pot, $card0, $card1 );
        if ($card0 != $card1) {
            $roundWinner = ($card0 > $card1) ? 0 : 1;
            $potstr = $this->cardToString($pot);
            $this->log[$this->turn] = "Player $roundWinner plays "
                . $this->cardToString($card0)
                . " against "
                . $this->cardToString($card1)
                . " to win the pot: " . $potstr
            ;
            $tmp = $this->hands[$roundWinner];
            $this->hands[$roundWinner] = array_merge( $tmp, $pot );
        } else {    // tie, draw again
            $this->log[$this->turn] = "Players tie with " . $this->cardToString($card0) . "s and draw again.";
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
        $values = self::getValues( $compact );
        $str = implode(" ",
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
        $values = self::getValues();
        if (is_array($value)) {
            $str = '';
            foreach($value as $v) {
                $str .= $values[$v];
            }
            return $str;
        }
        return $values[$value];
    }

    public static function getValues($compact = FALSE){
        if ( $compact == TRUE ) {
            return Array(0 => "2", "3", "4", "5", "6", "7", "8", "9", "T", "J", "Q", "K", "A");
        }
        return Array(0 => "deuce", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "jack", "queen", "king", "ace");
    }

}
