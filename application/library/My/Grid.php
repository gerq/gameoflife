<?php

class My_Grid
{

	private $width;
  private $height;
  public $cells = [];

  public function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;
  }

	// generate table, randomize for some tests
  public function generateCells($randomize, $rand_max = 10) {
    for ($x = 0; $x < $this->width; $x++) {
      for ($y = 0; $y < $this->height; $y++) {
        if ($randomize) {
          $this->cells[$y][$x] = $this->getRandomState($rand_max);
        }
        else {
          $this->cells[$y][$x] = 0;
        }
      }
    }
    return $this;
  }

	// count live cells
  public function countLiveCells() {
    $count = 0;
    foreach ($this->cells as $y => $row) {
      foreach ($row as $x => $cell) {
        if ($cell) {
          $count++;
        }
      }
    }
    return $count;
  }

	// get wdth
  public function getWidth() {
    return $this->width;
  }

	// get height
  public function getHeight() {
    return $this->height;
  }

	// get random
  private function getRandomState($rand_max = 1) {
    return rand(0, $rand_max) === 0;
  }

}
