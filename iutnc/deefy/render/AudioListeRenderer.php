<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\render\AlbumTrackRenderer;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class AudioListeRenderer implements Renderer {
  private AudioList $list;
  public function __construct(AudioList $list) {
    $this->list = $list;
  }

  public function render(string $selector = "") : string {
    $nom = $this->list->getNom();
    $nbPistes = $this->list->getNbpistes();
    $dureeTotale = $this->list->getDuree();
    $pistes = $this->list->getListes();

    $html = "<div class='audio-list' div style='text-align: center; margin-top: 20px;'>";
    $html .= "<h2>Le nom de la playlist est : $nom </h2>";
    $html .= "<div class='tracks-container'>";

    foreach ($pistes as $piste) {
      if ($piste instanceof AlbumTrack) {
        $trackRenderer = new AlbumTrackRenderer($piste);
      } elseif ($piste instanceof PodcastTrack) {
        $trackRenderer = new PodcastRenderer($piste);
      }
      $html .= "<p></p>";
      $html .= $trackRenderer->render(Renderer::COMPACT);
    }
    $html .= "</div>";
    $html .= "<div class='list-footer'>";
    $html .= "<p>Nombre de pistes : " . $nbPistes . "</p>";
    $html .= "<p>Durée totale : " . $dureeTotale . " secondes</p>";
    $html .= "</div>";
    $html .= "</div>";

    return $html;
  }

}