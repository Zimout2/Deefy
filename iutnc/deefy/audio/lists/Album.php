<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

class Album extends AudioList {
  protected string $artiste;
  protected string $date_sortie;

  public function __construct(string $artiste, string $date_sortie, string $nom, array $pistes) {
    parent::__construct($nom, $pistes);
    $this->artiste = $artiste;
    $this->date_sortie = $date_sortie;
  }

  public function setArtiste(string $artiste) : void {
    $this->artiste = $artiste;
  }

  public function setDate(string $d) : void {
    $this->date_sortie = $d;
  }
}