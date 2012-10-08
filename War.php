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

    public function getLog(  )
    {
        $tmp = $this->log;
        $this->log = '';
        return $tmp;
    }

    public function log($msg)
    {
        $this->log .= "#{$this->turn} SCORE: P0=" . $this->getScore( 0 ) . ", P1=" . $this->getScore( 1 ) . ")\t$msg \n";
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
     */
    public function doRound()
    {
        if( $this->isGameOver() ) {
            return FALSE;
        }
        $this->turn++;
        $card0 = $this->draw( 0 );
        $card1 = $this->draw( 1 );
        /*
        $card0 = $this->hands[0][(count($this->hands[0]) - 1)];
        $card1 = $this->hands[1][(count($this->hands[1]) - 1)];
        unset( $this->hands[0][(count($this->hands[0]) - 1)] );
        unset( $this->hands[1][(count($this->hands[1]) - 1)] );

        //array_push( $this->pot, $card0, $card1 );
        //$this->pot[] = $card0;
        //$this->pot[] = $card1;

        $this->log(
              "Player 0 plays " . self::cardToString( $card0 )
            ." Player 1 plays " . self::cardToString( $card1 )
            . PHP_EOL
        );
         */

        $this->log( "Draw: (P0)" . self::cardToString( $card0  ) . " (P1)" . self::cardToString( $card1 ) );

        if ( $card0 == $card1 ) { // if card values are a tie, there is a war
            $this->log( "WAR! with " . $this->cardToString($card0) . "s and draw again." );
            // WAR STUFF HERE
            $pot = Array( $card0, $card1 );

            for( $i = 0; $this->isGameOver() == FALSE; $i++ ) {

                $card0 = $this->draw( 0 );
                $card1 = $this->draw( 1 );
                array_push( $pot, $card0, $card1 );

                if ( ( $i % 2 == 0 ) ) {
                    continue;
                }

                $logmsg = "Draw: (P0)" . self::cardToString( $card0  ) . " (P1)" . self::cardToString( $card1 );

                if ( ( $card0 == $card1 ) ) {
                    $this->log( $logmsg . " Tie. WAR CONTINUES!" );
                    continue; // continue if tie or for every other card draw
                }

                // round won, game continues
                $winner = ($card0 > $card1) ? 0 : 1;
                $this->addCards( $winner, $pot );
                $this->log( $logmsg . " Player $winner wins round and takes the pot." );
                return TRUE;
            }
            $this->log( "a player has run out of cards and can't continue... game over!" );
            return FALSE;

        } else { // no war
            $winner = ($card0 > $card1) ? 0 : 1;
            $this->addCards( $winner, Array( $card0, $card1 ) );
            $this->log( "Player $winner wins round and collects both cards." );
        }
        return TRUE;
    }

    protected function draw( $player )
    {
        return array_pop( $this->hands[$player]  );
    }
        /*
        $potstr = $this->cardToString($this->pot);
        $this->log( "Player $roundWinner wins the pot and receives: " . $potstr );

        foreach ($this->pot as $card) {
            array_unshift($this->hands[$roundWinner], $card);
        }
        $this->pot = Array();
        */

    protected function addCards( $player, Array $cards )
    {
        foreach ($cards as $card) {
            array_unshift( $this->hands[$player], $card );
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
        $nextPosition = count( $this->hands[$player] ) - 1;
        $str .= '(next:' . $this->cardToString( $this->hands[$player][$nextPosition] ) . ') ';
        foreach ( $this->hands[$player] as $k => $cardValue ) {
            $str .= "[$k]" . $values[$cardValue] . ' ';
        }
        return rtrim( $str );
    }

    public function cardToString($value)
    {
        $values = self::getValues( TRUE );
        if (is_array($value)) {
            $str = '';
            foreach($value as $k => $v) {
                $str .= "[$k]" . $values[$v] . ' ';
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
