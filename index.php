<?php
// classe de base pour tous les personnages
abstract class Character {
    public $name;
    public $marbles;

    // methodes pour gagner et perdre des billes
    abstract public function win();
    abstract public function lose();
}

// classe pour le heros du jeu
class Hero extends Character {
    public $gain;
    public $loss;
    public $scream_war;

    // constructeur pour initialiser le heros
    public function __construct($name, $marbles, $gain, $loss, $scream_war) {
        $this->name = $name;
        $this->marbles = $marbles;
        $this->gain = $gain;
        $this->loss = $loss;
        $this->scream_war = $scream_war;
    }

    // methode pour gagner des billes
    public function win() {
        $this->marbles += $this->gain;
    }

    // methode pour perdre des billes
    public function lose() {
        $this->marbles -= $this->loss;
    }

    // methode pour émettre le scream_war
    public function scream() {
        echo $this->scream_war;
    }

    // methode pour choisir aleatoirement si le nombre de billes est pair ou impair
    public function pairOuImpair() {
        return rand(0, 1) ? "pair" : "impair";
    }
}

// classe pour les ennemis du jeu
class Enemy extends Character {
    public $age;

    // constructeur pour initialiser l'ennemi
    public function __construct($name, $marbles, $age) {
        $this->name = $name;
        $this->marbles = $marbles;
        $this->age = $age = rand(0, 100);
    }

    // methode pour gagner des billes
    public function win() {
        $this->marbles += 1;
    }

    // methode pour perdre des billes
    public function lose() {
        $this->marbles -= 1;
    }
}

class Game {
    public $hero;
    public $enemies = [];

    // constructeur pour initialiser le jeu
    public function __construct($hero, $enemies) {
        $this->hero = $hero;
        $this->enemies = $enemies;
    }

    // methode pour verifier si un nombre est pair ou impair
    public function checkPairImpair($number) {
        return $number % 2 == 0 ? "pair" : "impair";
    }

    // methode pour demarrer le jeu
    public function startGame() {
        echo "Le héro choisi est <strong>" . $this->hero->name . "</strong><br>";
        echo "Vous possédez donc <strong>" . $this->hero->marbles . "</strong> billes.<br>";
        echo "En cas de victoire, vous gagnerez <strong>" . $this->hero->gain . "</strong> bille(s).<br>";
        echo "En cas de défaite, vous perdrez <strong>" . $this->hero->loss . "</strong> bille(s).<br>";
        // boucle sur chaque ennemi
        foreach ($this->enemies as $enemy) {
            // si l'ennemi a plus de 70 ans, le heros a une chance de tricher
            if ($this->hero->marbles > 0) {
                if ($enemy->age > 70) {
                    $cheat = rand(0, 1) ? true : false;
                    // si le heros triche, il remporte automatiquement les billes de l'ennemi
                    if ($cheat) {
                        echo "<br>Vous avez décidé de tricher contre un joueur de plus de 70 ans. Vous remportez automatiquement <strong>" . $enemy->marbles . "</strong> billes.<br>";
                        $this->hero->win();
                        $this->hero->marbles += $enemy->marbles;
                        continue;
                    } else {
                        echo "<br>Vous avez décidé de rester loyal contre un joueur de plus de 70 ans.";
                    }
                }
        
                // le heros fait une supposition sur le nombre de billes de l'ennemi
                $heroGuess = $this->hero->pairOuImpair();
                // le nombre reel de billes de l'ennemi est verifie
                $actual = $this->checkPairImpair($enemy->marbles);
    
                echo "<br>L'enemi a choisi de miser <strong>" . $enemy->marbles . "</strong> billes.";
                echo "<br>Vous avez choisi : <strong>" . $heroGuess . "</strong>";
                echo "<br>Le nombre réel de billes de " . $enemy->name . " est <strong>" . $actual . "</strong>";
        
                // si la supposition du heros est correcte, il gagne la manche
                if ($heroGuess == $actual) {
                    $this->hero->win();
                    $this->hero->marbles += $enemy->marbles;
                    echo "<br>Vous avez gagné cette manche ! Il vous reste <strong>" . $this->hero->marbles . "</strong> billes.<br>";
                } else {
                    // sinon, le heros perd la manche
                    $this->hero->lose();
                    $this->hero->marbles -= $enemy->marbles;
                    if ($this->hero->marbles < 0) {
                        $this->hero->marbles = 0;
                    }
                    echo "<br>Vous avez perdu cette manche ! Il vous reste <strong>" . $this->hero->marbles . "</strong> billes.<br>";
                }
            } else {
                echo "<br>Le héros n'a plus de billes. Il a perdu.<br>";
                break;
            }
            
        }
        if ($this->hero->marbles > 0) {
            echo $this->hero->scream()."<br>Vous avez gagné la partie avec <strong>" . $this->hero->marbles . "</strong> billes. Vous remportez donc 45,6 milliards de Won sud-coréen.<br>";
        }
    }
}

// initialiser les differents heros
$heroes = [
    new Hero("Seong Gi-hun", 15, 1, 2, "Ouhouuuuuuuu"),
    new Hero("Kang Sae-byeok", 25, 2, 1, "AAARRRRGHHHHH"),
    new Hero("Cho Sang-woo", 35, 3, 0, "YAAAAAAAAAAAAAAhouuuuuu")
];

// generer un nombre aleatoire pour choisir le niveau de difficulte
$randomDifficulty = rand(1, 3);

// determiner le niveau de difficulte en fonction du nombre genere
switch ($randomDifficulty) {
    case 1:
        $difficulty = "facile";
        $numEnemies = 5;
        break;
    case 2:
        $difficulty = "difficile";
        $numEnemies = 10;
        break;
    case 3:
        $difficulty = "impossible";
        $numEnemies = 20;
        break;
}

// afficher le niveau de difficulte choisi
echo "Le niveau de difficulté choisi est : <strong>" . $difficulty . "</strong><br>";

// generer les ennemis
$enemies = [];
for ($i = 0; $i < $numEnemies; $i++) {
    $enemies[] = new Enemy("enemy " . ($i + 1), rand(1, 20), $age);
}

$game = new Game($heroes[array_rand($heroes)], $enemies);
$game->startGame();

?>