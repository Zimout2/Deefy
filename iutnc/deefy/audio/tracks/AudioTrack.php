<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack {
  private string $Titre;
  private string $Nom;
  private int $duree;

  public function __construct(string $Titre, string $Nom, int $duree = 0) {
    $this->Titre = $Titre;
    $this->Nom = $Nom;
    $this->duree = $duree;
  }

  public function getTitre() :mixed {
    return $this->Titre;
  }

  public function getNom() : mixed{
    return $this->Nom;
  }

  public function getDuree() : int {
    return $this->duree;
  }

  public function setDuree(int $d) : int {
    return $this->duree = $d;
  }

  public function setTitre(string $Titre) : string {
    return $this->Titre = $Titre;
  }

  public function setNom(string $Nom) : string {
    return $this->Nom = $Nom;
  }



  public function __toString() {
    return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
  }
}