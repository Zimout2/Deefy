<?php
declare(strict_types=1);

namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AlbumTrack extends AudioTrack{

  private string $Album;
  private int $numerodePiste;

  public function __construct(string $Titre, string $Nom, string $Album, int $numerodePiste, int $d = 0) {
    parent::__construct($Titre, $Nom, $d);

    $this->Album = $Album;
    $this->numerodePiste = $numerodePiste;
  }

  public function getAlbum() : mixed{
    return $this->Album;
  }

  public function getnumeroPiste() :mixed{
    return $this->numerodePiste;
  }

  public function setAlbum(string $Album) : string {
    return $this->Album = $Album;
  }

  public function setnumerodePiste(int $numerodePiste) : int {
    if ($numerodePiste < 0) {
      throw new \Exception("Valeur interdite");
    }
    return $this->numerodePiste = $numerodePiste;
  }

  public function __toString() : string {
    return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
  }

}