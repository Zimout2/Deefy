<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListeRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\repository\DeefyRepository;


class DisplaysPlaylistAction extends Action
{
    public function execute(): string {
        $html = "";
        $repo = DeefyRepository::getInstance();
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $playlist = $repo->findPlaylistById($id);
            if ($playlist !== null) {
                $_SESSION['playlist'] = serialize($playlist);
            }
        } elseif (isset($_SESSION['playlist'])) {
            $sessionPl = unserialize($_SESSION['playlist']);
            // On recharge depuis la BD via son ID pour être sûr d'avoir les dernières pistes
            $playlist = $repo->findPlaylistById($sessionPl->getID());
            if ($playlist !== null) {
                $_SESSION['playlist'] = serialize($playlist);
            }
            if ($playlist === null) {
                return "<p>Aucune playlist courante sélectionnée. <a href='?action=default'>Choisir une playlist</a></p>";
            }

            $renderer = new AudioListeRenderer($playlist);
            $html = $renderer->render(Renderer::COMPACT);

            $html .= "<div class='audio-list' div style='text-align: center; margin-top: 20px;'>";
            $html .= "<p><a href='?action=add-track'>Ajouter une piste à cette playlist</a></p>";

        }
        return $html;
    }
}