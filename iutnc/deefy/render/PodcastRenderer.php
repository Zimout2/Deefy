<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\audio\tracks\PodcastTrack as PT;


class PodcastRenderer extends AudioTrackRenderer{

  public function __construct(PT $pod) {
    parent::__construct($pod);
  }

  public function renderCompact() : string {
    $titre = $this->track->getTitre();
    $fichier = $this->track->getNom();

    $html = "<div class='track-compact'>";
    $html .= "<p>Le titre de la piste est : <strong>$titre</strong></p> ";
    $html .= "<p></p>";
    $html .= "<audio controls src='$fichier'></audio>";
    $html .= "</div>";

    return $html;
  }

  public function renderLong() : string {
    $titre = $this->track->getTitre();
    $fichier = $this->track->getNom();
    $artiste = $this->track->Artiste ?? 'Artiste Inconnue';
    
    $html = "<div class='track-long'>";
    $html .= "<h2>Le titre du morceau est : $titre</h2>";
    $html .= "<p>Artiste : $artiste</p>";
    $html .= "<audio controls src='$fichier'>Votre navigateur ne supporte pas l'audio.</audio>";
    $html .= "</div>";

    return $html;
  }
}