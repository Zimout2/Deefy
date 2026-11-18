<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DisplaysPlaylistAction;


class Dispatcher {
    protected string $action;
    protected array $actions = [];

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void {
        $html = "";
        if (isset($this->actions[$this->action])) {
            $class = $this->actions[$this->action];
            $play = new $class();
            $html = $play->execute();
            $this->renderPage($html);
        } else {
            $this->renderPage("</h2>Erreur 404</h2><br><br><p>Action non trouvé</p>");
        }
    }

    public function addRoute(string $action, string $class) : self {
        $this->actions[$action] = $class;
        return $this;
    }

    private function renderPage(string $html): void {
        $contenuHaut = ($this->action === 'default') ? $html : '';
        $contenuBas  = ($this->action !== 'default') ? $html : '';
        $page = <<<HTML
        <!DOCTYPE html>
        <html lang='en'>
            <head>
                <meta charset='utf-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>
                <title>My Page</title>
            </head>
        <body>
        $contenuHaut
            <p><a href="?action=default">Accueil</a></p>
            <p></p>
            <a href="?action=add-playlist">Ajout d'une playlist</a>
            <p></p>
            <p><a href="?action=add-track">Ajout de piste</a></p>
            <p></p>
            <p><a href="?action=playlist">Affichage de la playlist</a></p>
            $contenuBas
        </body>
        </html>
        HTML;
        echo $page;
    }
}