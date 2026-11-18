<?php
declare(strict_types=1);

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlists;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class DeefyRepository {
  private \PDO $pdo;
  private static ?DeefyRepository $instance = null;
  private static array $config = [ ];

  private function __construct(array $conf) {
    $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
  }

  public static function getInstance(){
    if (is_null(self::$instance)) {
      self::$instance = new DeefyRepository(self::$config);
    }
    return self::$instance;
  }
  
  public static function setConfig(string $file) {
    $conf = parse_ini_file($file);
    if ($conf === false) {
      throw new \Exception("Error reading configuration file");
    }
    self::$config = ['dsn'=>$conf['driver'].":host=".$conf['host'].";dbname=".$conf['database'],'user'=>$conf['username'],'pass'=>$conf['password']];
  }

  public function findPlaylistById(int $id): ?Playlists {
    $query = "SELECT nom from playlist where id = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    $playlist = new Playlists($row['nom'], [], $id);
    $playlist->setID($id);
      $queryTracks = "SELECT t.* FROM track t
                        INNER JOIN playlist2track p2t ON t.id = p2t.id_track
                        WHERE p2t.id_pl  = :id";
      $stmtTracks = $this->pdo->prepare($queryTracks);
      $stmtTracks->execute(['id' => $id]);

      while ($t = $stmtTracks->fetch(\PDO::FETCH_ASSOC)) {
          if ($t['type'] === 'A') {
              $track = new AlbumTrack($t['titre'], $t['filename'], $t['titre_album'] ?? '', (int)($t['numero_album'] ?? 0));
          } else {
              $track = new PodcastTrack($t['titre'], $t['filename']);
          }
          $track->setDuree((int)$t['duree']);
          $playlist->ajout_piste($track);
      }
    return $playlist;
  }
  public function saveEmptyPlaylist(Playlists $pk): Playlists {
    $query = "INSERT INTO playlist (nom) VALUES (:nom)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['nom' => $pk->nom]);
    $pk->setID((int)$this->pdo->lastInsertId());
    return $pk;
  }

  public function savePistePLaylist(track $p) : track {
    $query = "INSERT INTO track (titre, filename) VALUES (:titre, :filename)";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['titre' => $p->titre, 'filename' => $p->filename]);
    $p->setID($this->pdo->lastInsertid  ());
    return $p;
  }

  public function ajout_pistes(int $id_playlist, int $id_track) : void {
      $queryCount = "SELECT COUNT(*) FROM playlist2track WHERE id_pl = :id_pl";
      $stmtCount = $this->pdo->prepare($queryCount);
      $stmtCount->execute(['id_pl' => $id_playlist]);
      $noPiste = (int)$stmtCount->fetchColumn() + 1;

      $query = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (:id_pl, :id_track, :no_piste)";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute([
          'id_pl'    => $id_playlist,
          'id_track' => $id_track,
          'no_piste' => $noPiste
      ]);
  }

  public function trouverPlaylist() : array {
      $query = "select id, nom from playlist";
      $stmt = $this->pdo->query($query);
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

    public function savePodcastTrack(PodcastTrack $p, string $filename): int {
        $query = "INSERT INTO track (titre, duree, filename, type) VALUES (:titre, :duree, :filename, 'P')";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'titre'    => $p->getTitre(),
            'duree'    => $p->getDuree(),
            'filename' => $filename
        ]);
        return (int)$this->pdo->lastInsertId();
    }

}