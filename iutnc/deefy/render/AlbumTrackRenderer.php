<?php
declare(strict_types=1);

namespace iutnc\deefy\render;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\audio\tracks\AlbumTrack;


class AlbumTrackRenderer implements Renderer{
  private AlbumTrack $Album;

  public function __construct(AlbumTrack $A) {
    $this->Album = $A;
  }

  public function render(string $selector) : string {

    switch($selector) {
      case self::COMPACT:
        return $this->renderCompact();
      case self::LONG:
        return $this->renderLong();
      default:
        return $this->renderCompact();
    }
  }

  private function renderCompact() : string {
    $titre = $this->Album->getTitre();
    $fichier = $this->Album->getNom();

    $html = "<div class='track-compact'>";
    $html .= "<p></p>";
    $html .= "<p>Le titre est : <strong>$titre</strong></p>";
    $html .= "<audio controls src='$fichier'></audio>";
    $html .= "</div>";
    return $html;
  }

  private function renderLong() : string {
    $titre = $this->Album->getTitre();
    $fichier = $this->Album->getNom();
    $artiste = $this->Album->Artiste ?? 'Artiste Inconnue';
    $html = "";

    $html .= "<div style='text-align: center; margin-top: 20px;'>";
    $html .= "<div class='track-long'>";
    $html .= "<p></p>";
    $html .= "<span><strong>$titre</strong></span> ";
    $html .= "";
    $html .= "<audio controls src='$fichier'>Votre navigateur ne supporte pas l'audio.</audio>";
    $html .= "</div>";
    $html .= "<p></p>";

    return $html;
  }
}