<?php

class Deck
{

    /* 
    La carte en indice 0 est la carte du dessous du deck
    Le 2 correspond à la carte 2 et 14 correspond à l'As
    */
  
    public $couleur = ['Pique', 'Carreau', 'Trefle', 'Coeur'];
    public $numero = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    public $deck = [];

    /*
    Crée un tableau de tableaux qui comporte une numéro et une couleur
    */

    public function __construct()
    {
            $this->create();
    }

    protected function create()
    {     
        foreach($this->couleur as $coul) {
            foreach($this->numero as $index) {
            $this->deck[] = $this->createCard($coul, $index);
            }
        }
        return $this;
    }
    
    /*
    Crée une carte (tableau avec un numéro et une couleur)
    */

    public function createCard($coul, $index)
    {
      return ['coul' => $coul, 'index' => $index];
    }

    /*
    Retourne toutes les cartes d'un deck
     */

    public function printDeck()
    {
      foreach($this->deck as $carte) {
        $this->printCard($carte);
      }
	  return $this;
    }
    
    /*
    Affiche une carte
    */

    public function printCard($carte)
    {
      $coul = '';
      if ($carte['coul'] == 'Pique') {
        $coul = ' de Pique ';
      }
      if ($carte['coul'] == 'Carreau') {
        $coul = ' de Carreau ';
      }
      if ($carte['coul'] == 'Trefle') {
        $coul = ' de Trefle ';
      }
      if ($carte['coul'] == 'Coeur') {
        $coul = ' de Coeur ';
      }
      
      $rank = $carte['index'];
      switch ($rank) {
        case 11:
          $rank = 'Valet';
          break;
         case 12:
          $rank = 'Reine';
          break;
        case 13:
          $rank = 'Roi';
          break;
        case 14:
          $rank = 'As';
          break;
     }
      
      echo 'Carte : ' . $rank . $coul . "\n";
    }

    /*
	Mélange le deck
    */
    
	public function shuffleDeck()
	{
		shuffle($this->deck);
		return $this;
    }
    
    /*
	Retourne la carte du dessus et la retire du paquet
    */
    
	public function getCarteDessus()
	{
	  $carte = array_pop($this->deck);
	  return $carte;
    }
    
    /*
	Retourne le nombre de cartes dans un paquet
    */
    
	public function sizeofDeck() 
	{
		return count($this->deck);
    }
    
    /*
	Sépare le deck initial en 2 mains pour les joueurs
    */
    
	public function deal($hand1, $hand2)
	{
		$nbcartes = $this->sizeofDeck();

		for($i = 0; $i < $nbcartes;) {
			$hand1->deck[] = $this->getCarteDessus();
			$i++;
			$hand2->deck[] = $this->getCarteDessus();
			$i++;
		}
		return;
    }
    
    /*
    Ajoute des cartes en-dessous du deck et retourne le nombre de cartes ajoutées
    */

    public function ajoutEnDessous($morecartes)
    {
        $nbcartes = $morecartes->sizeofDeck();
        for($i = 0; $i < $nbcartes; $i++) {
                $this->ajoutCarteEnDessous($morecartes->getCarteDessus());
            }
        return $nbcartes;
    }

    /* 
    Ajoute la carte en argument en-dessous du deck
    */

    public function ajoutCarteEnDessous ($carte)
    {
        array_unshift($this->deck, $carte);
        
        return;
    }

    /*
    Ajoute la carte en argument au-dessus du deck
    */

    public function ajoutCarteAuDessus ($carte)
    {
        $this->deck[] = $carte;
        
        return;
    }

} //fin de la classe Deck

class Main extends Deck
{
    public function __construct()
    {
        return $this;
    }
  
} //fin de la classe Main

function tour($joueur1, $joueur2, $pile)
{
    //Vérifie si les joueurs ont les cartes pour jouer
    if ($joueur1->sizeofDeck() == 0) {
        echo "Joueur 1 n'a plus de cartes \n";
        return;
    }
    if ($joueur2->sizeofDeck() == 0) {
        echo "Joueur 2 n'a plus de cartes \n";
        return;
    }
    
    //Joue la carte du haut sur la pile
    $carte1 = $joueur1->getCarteDessus();
    $pile->ajoutCarteAuDessus($carte1);
    echo 'Joueur 1 joue ';  $joueur1->printCard($carte1);

    $carte2 = $joueur2->getCarteDessus();
    $pile->ajoutCarteAuDessus($carte2);
    echo 'Joueur 2 joue ';  $joueur2->printCard($carte2);
    
    //Mélange la pile pour la redistribution
    $pile->shuffleDeck(); 
    
    //Compare les cartes et annonce le gagnant
    //Ajoute les cartes de la pile en-dessous du deck du gagnant
    //Si les cartes ont la même valeur, on repart pour un tour 
    if ($carte1['index'] > $carte2['index']) {
        echo "Joueur 1 gagne cette bataille \n";
        $joueur1->ajoutEnDessous($pile);
    } elseif ($carte2['index'] > $carte1['index']) {
        echo "Joueur 2 gagne cette bataille \n";
        $joueur2->ajoutEnDessous($pile);
    } else {
        echo "Egalité, on recommence \n";
        bataille ($joueur1, $joueur2, $pile);
    }
  
  return;
}

function bataille ($joueur1, $joueur2, $pile)
{
    //Vérifie s'il reste des cartes aux 2 joueurs
    if ($joueur1->sizeofDeck() == 0) {
        echo "Joueur 1 n'a plus de cartes ! \n";
        return;
    }
    if ($joueur2->sizeofDeck() == 0) {
        echo "Joueur 2 n'a plus de cartes ! \n";
        return;
    }
    
    //Chaque joueur met la carte du dessus de son paquet
    $pile->ajoutCarteAuDessus($joueur1->getCarteDessus());
    $pile->ajoutCarteAuDessus($joueur2->getCarteDessus());
    //Le tour reprend normalement
    tour($joueur1, $joueur2, $pile);
    
    return;
}

do{
    echo "Bienvenue dans la super Bataille de la NWS !! \n";
    echo "1 - Jouer (mode auto)\n";
    echo "2 - Jouer (mode manuel)\n";
    echo "3 - Règles \n";
    echo "4 - Quitter \n";

    $choix = readline();

    switch($choix){
        case 1:
            jeuAuto();
        case 2:
            jeuManu();
        case 3:
            echo "On distribue les 52 cartes aux joueurs (peut se jouer à deux) qui les rassemblent en paquet devant eux.

            Chacun tire la carte du dessus de son paquet et la pose sur la table.
            
            Celui qui a la carte la plus forte ramasse les autres cartes.
            
            L'as est la plus forte carte, puis roi, dame, valet, 10, etc.
            
            Lorsque deux joueurs posent en même temps deux cartes de même valeur il y a 'bataille'. Lorsqu'il y a 'bataille' les joueurs tirent la carte suivante et la posent, face cachée, sur la carte précédente. Puis ils tirent une deuxième carte qu'ils posent cette fois-ci face découverte et c'est cette dernière qui départagera les joueurs.
            
            Le gagnant est celui qui remporte toutes les cartes.";
        case 4:
            echo "A bientot !";
    }

}while($choix != 4);


function jeuManu(){

    //Création du deck et mélange
    $startingdeck = (new Deck())->shuffleDeck()->printDeck();

    //Distribue 2 mains
    echo "Distribution des mains ... \n";
    $joueur1 = new Main();  //Création d'une main
    $joueur2 = new Main();  //Création d'une main
    $startingdeck->deal($joueur1, $joueur2);


    //Création d'une main pour les cartes jouées (pile de jeu)
    $pile = new Main();


    //Bataille tant que les joueurs ont une main
    while (($joueur1->sizeofDeck() > 0) && ($joueur2->sizeofDeck() > 0)) {

        echo "Rentrer O pour tirer une carte \n";
        $input = readline();
        
        if($input == "O"){
            tour ($joueur1, $joueur2, $pile);
            echo 'Joueur 1 a ' . $joueur1->sizeofDeck() . " cartes \n";
            echo 'Joueur 2 a ' . $joueur2->sizeofDeck() . " cartes \n";
        }else{
            echo "Veuillez rentrer le caractère demandé (O) \n";
        }
    }
    
    if ($joueur1->sizeofDeck() > 0) {
        echo 'Joueur 1 a gagné !!';
    } else {
        echo 'Joueur 2 a gagné !!';
    }
    exit;
}

function jeuAuto(){

    //Création du deck et mélange
    $startingdeck = (new Deck())->shuffleDeck()->printDeck();

    //Distribue 2 mains
    echo "Distribution des mains ... \n";
    $joueur1 = new Main();  //Création d'une main
    $joueur2 = new Main();  //Création d'une main
    $startingdeck->deal($joueur1, $joueur2);


    //Création d'une main pour les cartes jouées (pile de jeu)
    $pile = new Main();


    //Bataille jusqu'à ce que les joueurs n'aient plus de mains
    while (($joueur1->sizeofDeck() > 0) && ($joueur2->sizeofDeck() > 0)) {
        tour ($joueur1, $joueur2, $pile);
        echo 'Joueur 1 a ' . $joueur1->sizeofDeck() . " cartes \n";
        echo 'Joueur 2 a ' . $joueur2->sizeofDeck() . " cartes \n";
    }
    
    if ($joueur1->sizeofDeck() > 0) {
        echo 'Joueur 1 a gagné !!';
    } else {
        echo 'Joueur 2 a gagné !!';
    }
    exit;
}

exit;

?>