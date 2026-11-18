<?php
declare(strict_types=1);

namespace iutnc\deefy\action;
use iutnc\deefy\repository\DeefyRepository;

class DefaultAction extends Action {
  public function execute() : string {
      $pedo = DeefyRepository::getInstance();
      $play = $pedo->trouverPlaylist();
      $html = "<h1>Bienvenue !</h1>";
      if (!empty($play)) {
          $html .= "<p>Voici la liste actuelle des playlists : </p>";
          $html .= "<ul>";
          foreach ($play as $nom) {
              $id = (int)$nom['id'];
              $nom_officiel = filter_var($nom['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
              $html .= "<li><a href='?action=playlist&id=$id'>$nom_officiel</a></li>";
          }
          $html .= "</ul>";
      }

    return $html;
  }
}