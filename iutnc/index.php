<?php
declare(strict_types=1);
session_start();
require_once 'vendor/autoload.php';

use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplaysPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddTrackAction;
use iutnc\deefy\repository\DeefyRepository;


$app = new Dispatcher();

$app->addRoute('default', DefaultAction::class)
    ->addRoute('playlist', DisplaysPlaylistAction::class)
    ->addRoute('add-playlist', AddPlaylistAction::class)
    ->addRoute('add-track', AddTrackAction::class);
DeefyRepository::setConfig(__DIR__ . '/deefy/config.ini');
$app->run();