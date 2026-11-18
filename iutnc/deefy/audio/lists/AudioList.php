<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use Exception;

class AudioList {
  protected string $nom;
  protected int $nbpistes, $duree;
  protected array $listes;
  protected ?int $id = null;


  public function __construct(string $nom, array $pistes = [], int $id = null) {
    $this->nom = $nom;
    $this->listes = $pistes;
    $this->nbpistes = count($this->listes);
    $this->duree = 0;
    foreach($this->listes as $piste) {
      $dureePiste = method_exists($piste, 'getDuree') ? $piste->getDuree() : 0;
      $this->duree += $dureePiste;
    }
    $this->id = $id;
  }

  public function __get(string $propriete) {
    if (property_exists($this, $propriete)) {
      return $this->$propriete;
    }
    throw new \Exception("Propriété inconnue ou inaccessible : $propriete");
  }

  public function getNom() : string {
    return $this->nom;
  }

  public function getNbpistes() : int {
    return $this->nbpistes;
  }

  public function setNb(int $nb) : void {
    $this->nbpistes = $nb;
  }

  public function getDuree() : int {
    $dureeTotale = 0;
    foreach ($this->listes as $piste) {
        $dureeTotale += $piste->getDuree();
    }
    return $dureeTotale;
}

  public function setDuree(int $du) : void {
    $this->duree += $du;
  }

  public function getListes() : array {
    return $this->listes;
  }

  public function setListes(array $l) : void {
    $this->listes = $l;
  }

  public function setID(int $id): void {
      $this->id = $id;
  }
  public function getID(): ?int {
      return $this->id;
  }
}