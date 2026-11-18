<?php
declare(strict_types=1);

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListeRenderer;
use iutnc\deefy\render\Renderer;

class AddTrackAction extends Action {
    public function execute() : string {
        $html = "";
        $repo = DeefyRepository::getInstance();

        if (!isset($_SESSION['playlist'])) {
            return "<p>Aucune playlist courante en session. <a href='?action=default'>Sélectionnez</a> ou <a href='?action=add-playlist'>créez</a> une playlist d'abord.</p>";
        }

        $playlistCourante = unserialize($_SESSION['playlist']);
        $playlistId = (int)$playlistCourante->getID();
        $repo = DeefyRepository::getInstance();
        $html = "";

        if ($this->http_method === 'POST') {
            $nom = filter_var($_POST['title'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = (int)($_POST['duree'] ?? 0);

            // Stockage dans iutnc/audio/ (dossier public à la racine)
            $upload_dir = __DIR__ . '/../../audio/';
            $fichier = uniqid() . '.mp3';
            $tmp = $_FILES['inputfile']['tmp_name'] ?? '';

            if (($_FILES['inputfile']['error'] === UPLOAD_ERR_OK)
                && ($_FILES['inputfile']['type'] === 'audio/mpeg')
                && (substr($_FILES['inputfile']['name'], -4) === '.mp3')) {

                if (move_uploaded_file($tmp, $upload_dir . $fichier)) {
                    $chemin = 'audio/' . $fichier;
                    $podcast = new PodcastTrack($nom, $chemin);
                    $podcast->setDuree($duree);

                    $idTrack = $repo->savePodcastTrack($podcast, $chemin);
                    $repo->ajout_pistes($playlistId, $idTrack);

                    $playlistCourante = $repo->findPlaylistById($playlistId);
                    $_SESSION['playlist'] = serialize($playlistCourante);

                    $renderer = new AudioListeRenderer($playlistCourante);
                    $html .= "<p>Piste ajoutée avec succès à la playlist courante !</p>";
                    $html .= $renderer->render(Renderer::COMPACT);
                    $html .= "<p><a href='?action=add-track'>Ajouter une autre piste</a></p>";
                } else {
                    $html .= "<p>Erreur lors du déplacement du fichier audio.</p>";
                }
            } else {
                $html .= "<p>Échec du téléchargement ou format non autorisé (.mp3 uniquement).</p>";
            }
        } else {
            $nomPl = filter_var($playlistCourante->getNom(), FILTER_SANITIZE_SPECIAL_CHARS);
            $html = "<div class='audio-list' div style='text-align: center; margin-top: 20px;'>";
            $html .= "<h2>Ajouter une piste à la playlist : $nomPl</h2>";
            $html .= "<form enctype='multipart/form-data' method='POST' action='?action=add-track'>";

            $html .= "<label for='title'>Titre de la piste : </label>";
            $html .= "<input type='text' id='title' name='title' required><br><br>";

            $html .= "<label for='inputfile'>Fichier audio : </label>";
            $html .= "<input type='file' id='inputfile' name='inputfile' accept='audio/mpeg' required><br><br>";

            $html .= "<label for='duree'>Durée : </label>";
            $html .= "<input type='number' id='duree' name='duree' readonly required><br><br>";

            $html .= "<button type='submit'>Ajouter la piste</button>";
            $html .= "</form>";

            $html .= <<<HTML
            <script>
            document.getElementById('inputfile').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const audio = new Audio();
                audio.src = URL.createObjectURL(file);
                audio.addEventListener('loadedmetadata', function() {
                    document.getElementById('duree').value = Math.round(audio.duration);
                    URL.revokeObjectURL(audio.src);
                });
            });
            </script>
            HTML;
        }

        return $html;
    }
}