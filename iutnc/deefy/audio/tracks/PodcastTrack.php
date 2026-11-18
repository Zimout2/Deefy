<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyValueException;

class PodcastTrack extends AudioTrack{
  public function __construct(string $Titre, string $chemin, int $duree = 0) {
    parent::__construct($Titre, $chemin, $duree);
  }
}
