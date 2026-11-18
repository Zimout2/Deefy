<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\audio\tracks\AudioTrack;

abstract class AudiotrackRenderer implements Renderer{
  protected AudioTrack $track;

  public function __construct(AudioTrack $track) {
    $this->track = $track;
  }

  public function __toString() {
    return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
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

  abstract public function renderCompact() : string;

  abstract public function renderLong() : string;

}