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

    protected $pot = Array();

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

    public function getLog( $turnNumber = NULL )
    {
        if ( is_null( $turnNumber ) ) {
            return $this->log;
        }
        return $this->log[$turnNumber];
    }

    public function log($msg)
    {
        $this->log[$this->turn] .= $msg;
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

    protected function newTurn()
    {
        $this->turn++;
        $this->log[$this->turn] = '';
    }

    /**
     * Draw another card for each player
     */
    public function draw()
    {
        if( $this->isGameOver() ) {
            return;
        }
        $this->newTurn();
        $c0 = $this->hands[0][(count($this->hands[0]) - 1)];
        $c1 = $this->hands[1][(count($this->hands[1]) - 1)];
        $card0 = array_pop( $this->hands[0] );
        $card1 = array_pop( $this->hands[1] );
        if ($c0 != $card0 || $c1 != $card1) {
            throw new Exception( 'picking the wrong card for some reason ');
        }

        $this->log(
              "Player 0 plays " . self::cardToString( $card0 )
            ." Player 1 plays " . self::cardToString( $card1 )
            . PHP_EOL
        );
        array_push( $this->pot, $card0, $card1 );
        if ($card0 != $card1) {
            $roundWinner = ($card0 > $card1) ? 0 : 1;
            $potstr = $this->cardToString($this->pot);
            $this->log( "Player $roundWinner wins the pot and receives: " . $potstr );
            $tmp = $this->hands[$roundWinner];

            $this->hands[$roundWinner] = array_merge( $tmp, $this->pot );


            $this->pot = Array();
        } else {    // tie, draw again
            $this->log( "Players tie with " . $this->cardToString($card0) . "s and draw again." );
        }
    }

    /**
     * Print hand to std output
     * @param array $cards
     */
    public function displayHand($player, $compact = FALSE)
    {
        $values = self::getValues( $compact );
        $str = '';
        foreach ( $this->hands[$player] as $cardValue ) {
            $str .= $values[$cardValue] . ' ';
        }
        return rtrim( $str );

        $hand = $this->hands[$player];
        array_walk(
            $hand,
            function ($cardValue) use ($values) {
                return $values[$cardValue];
            }
        );
        $str = implode( " ", $hand );

        return $str;

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
        $values = self::getValues( TRUE );
        if (is_array($value)) {
            $str = '';
            foreach($value as $v) {
                $str .= $values[$v] . ' ';
            }
            return rtrim($str);
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
