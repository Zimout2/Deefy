<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlists;
use iutnc\deefy\render\AudioListeRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\repository\DeefyRepository;


class addPlaylistAction extends Action {
  public function execute() : string {
    $html = "";
    $repo = DeefyRepository::getInstance();
      if ($this->http_method === 'POST') {
          $nom = filter_var($_POST['nom'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
          $repo = DeefyRepository::getInstance();

          // Création et enregistrement en BD (récupère l'id auto-incrémenté)
          $playlist = new Playlists($nom, []);
          $playlist = $repo->saveEmptyPlaylist($playlist);

          // Elle devient la playlist courante en session
          $_SESSION['playlist'] = serialize($playlist);

          $renderer = new AudioListeRenderer($playlist);
          $html = "<p>Playlist <strong>$nom</strong> créée avec succès !</p>";
          $html .= $renderer->render(Renderer::COMPACT);
          $html .= "<p><a href='?action=add-track'>Ajouter une piste à cette playlist</a></p>";

          return $html;
      }

      $html = "<div class='audio-list' div style='text-align: center; margin-top: 20px;'>";
      $html .= "<h2>Créer une playlist vide</h2>";
      $html .= "<form method='POST' action='?action=add-playlist'>";
      $html .= "<label for='nom'>Nom de la playlist : </label>";
      $html .= "<input type='text' id='nom' name='nom' required>";
      $html .= "<button type='submit'>Créer</button>";
      $html .= "</form>";

      return $html;
  }
}