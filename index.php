<?php
/**
    Code Challenges:

    WAR:
    Design a class definition for a deck of playing cards, then write a simple program to simulate two
    players playing the game of War. The game and rules are described here: http://www.pagat.com/war/

    war.html

    The program should:
    • Shuffle the virtual deck before every game
    • Deal 26 cards to each player
    • Display the cards that were played for each turn, who was the winner, and the running score.

 */

require_once('Deck.php');
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
$deck = new Deck();

$hands = $deck->deal();
$winner = NULL;
for ($i = 0; count($hands[0]) > 0 && count($hands[1]) > 0; $i++) {
    echo "Round #$i\n\tPlayer 0\t" . $deck->displayHand($hands[0]) . "\n\tPlayer 1\t" . $deck->displayHand($hands[1]) . PHP_EOL;
    $winner = $deck->executeTurn($hands[0], $hands[1]);
    echo "Player $winner wins the pot!\n";
}
echo "Player $winner WINS!\n";

