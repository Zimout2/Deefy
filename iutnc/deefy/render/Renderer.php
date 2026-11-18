<?php
declare(strict_types=1);
namespace iutnc\deefy\render;


interface Renderer {
  public const COMPACT = "COMPACT";
  public const LONG = "LONG";
  
  public function render(string $selector) : string;
}