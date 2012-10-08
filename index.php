<?php

require_once('Deck.php');
require_once('War.php');

?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
    <title>war</title>
</head>
<body>
<pre>
/**
    Code Challenges:

    WAR:
    Design a class definition for a deck of playing cards, then write a simple program to simulate two
    players playing the game of War. The game and rules are described here: <a href="http://www.pagat.com/war/war.html">http://www.pagat.com/war/war.html</a>

    The program should:
    * Shuffle the virtual deck before every game
    * Deal 26 cards to each player
    * Display the cards that were played for each turn, who was the winner, and the running score.

 */
</pre>

    <h1>Codexorz</h1>
    <a href="index.php.html">index.php</a><br />
    <a href="Deck.php.html">Deck.php</a><br />
    <a href="War.php.html">War.php</a><br />

    <h1>Output</h1>
<pre>
<?php

$game = new War();
while ( $game->doRound() ) {
    echo $game->getLog();
    //sleep(1);

    //$turnNumber = $game->getTurn();
    //echo "Turn #$turnNumber\n";
    /*
    echo "\tPlayer 0 score(" . $game->getScore(0) . ")\tcurrent cards " . $game->displayHand(0, TRUE) . PHP_EOL;
    echo "\tPlayer 1 score(" . $game->getScore(1) . ")\tcurrent cards " . $game->displayHand(1, TRUE) . PHP_EOL;
    */
}
echo "Winner: " . $game->getWinner() . PHP_EOL;
echo "Game over on turn #" . $game->getTurn() . PHP_EOL;

?>
</pre>
</body>
</html>