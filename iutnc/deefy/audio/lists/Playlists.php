<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class Playlists extends AudioList{

  public function __construct(string $nom, array $liste, int $id = null) {
    parent::__construct($nom, $liste, $id);
  }

  public function ajout_piste(AudioTrack $piste) : void {
    $pistes = $this->getListes();
    $pistes[] = $piste;
    $this->setListes($pistes);

    $this->setNb($this->getNbpistes() + 1);

    $this->setDuree($this->getDuree() + ($piste->getDuree() ?? 0));
  }

  public function suppr_pistes(int $p) : void {
    $pistes = $this->getListes();

    if (isset($pistes[$p])) {
        $dureePiste = $pistes[$p]->getDuree();
        $this->setDuree($this->getDuree() - $dureePiste);

        unset($pistes[$p]);
        $pistes = array_values($pistes);

        $this->setListes($pistes);
        $this->setNb($this->getNbpistes() - 1);
    }
  }

  public function getTrack() {
    return $this->listes;
  }

  public function ajout_piste_liste(array $pistes) : void {
    foreach($pistes as $piste) {
      $boo = false;

      foreach($this->getListes() as $piste_exist) {
        if ($piste_exist === $piste) {
          $boo = true;
          break;
        }
      }

      if (!$boo) {
        $this->ajout_piste($piste);
      }
    }
  }
}