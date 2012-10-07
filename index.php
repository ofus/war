<?php

require_once('Deck.php');
require_once('War.php');

?><pre>
/**
    Code Challenges:

    WAR:
    Design a class definition for a deck of playing cards, then write a simple program to simulate two
    players playing the game of War. The game and rules are described here: http://www.pagat.com/war/

    war.html

    The program should:
    * Shuffle the virtual deck before every game
    * Deal 26 cards to each player
    * Display the cards that were played for each turn, who was the winner, and the running score.

 */

<?php

$game = new War();
while ( !$game->isGameOver() ) {
    echo "Turn #" . $game->getTurn() . PHP_EOL;
    echo "\tPlayer 0 current cards (" . $game->getScore(0) . "):\t" . $game->displayHand(0, TRUE) . PHP_EOL;
    echo "\tPlayer 1 current cards (" . $game->getScore(1) . "):\t" . $game->displayHand(1, TRUE) . PHP_EOL;
    echo "Log: " . $game->getLog( $game->getTurn() ) . "\n";
    $game->draw();
}
echo "Winner: " . $game->getWinner() . PHP_EOL;
echo "Game over on turn #" . $game->getTurn() . PHP_EOL;
