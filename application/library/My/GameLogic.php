<?php

class My_GameLogic {

  private $_opts = array();
  private $_grid = null;

  function __construct(array $opts) {
    $this->_setDefaults($opts);
    this->_grid = new My_Grid($this->opts['width'], $this->opts['height']);

    $this->grid->generateCells($this->opts['random'], $this->opts['rand_max']);
    if (!empty($this->opts['template'])) {
      $this->setTemplate($this->opts['template']);
    }

  }

  private function _setDefaults(array $opts) {
      $defaults = [
        'template' => NULL,
        'rand_max' => 5,
        'random' => TRUE,
        'cell' => 'O',
        'empty' => '&nbsp;',
      ];
      if (isset($opts['template']) && !isset($opts['random'])) {
        // Disable random when template is set.
        $opts['random'] = FALSE;
      }
      $opts += $defaults;
      $this->_opts += $opts;
  }

  /**
     * Processes a new generation for all cells.
     *
     * Base on these rules:
     * 1. Any live cell with fewer than two live neighbours dies, as if by needs caused by underpopulation.
     * 2. Any live cell with more than three live neighbours dies, as if by overcrowding.
     * 3. Any live cell with two or three live neighbours lives, unchanged, to the next generation.
     * 4. Any dead cell with exactly three live neighbours will come to life.
     */
    private function newGeneration() {
      $cells = &$this->grid->cells;
      $kill_queue = $born_queue = [];
      for ($y = 0; $y < $this->grid->getHeight(); $y++) {
        for ($x = 0; $x < $this->grid->getWidth(); $x++) {
          // All cell activity is determined by the neighbor count.
          $neighbor_count = $this->getAliveNeighborCount($x, $y);
          if ($cells[$y][$x] && ($neighbor_count < 2 || $neighbor_count > 3)) {
            $kill_queue[] = [$y, $x];
          }
          if (!$cells[$y][$x] && $neighbor_count === 3) {
            $born_queue[] = [$y, $x];
          }
        }
      }
      foreach ($kill_queue as $c) {
        $cells[$c[0]][$c[1]] = 0;
      }
      foreach ($born_queue as $c) {
        $cells[$c[0]][$c[1]] = 1;
      }
    }

    /**
       * Gets living neighbors for a cell at given coordinates.
       *
       * @param int $x
       * @param int $y
       *
       * @return int
       *   Returns the number of alive neighbors for this cell.
       */
    private function getAliveNeighborCount($x, $y) {
        $alive_count = 0;
        for ($y2 = $y - 1; $y2 <= $y + 1; $y2++) {
          if ($y2 < 0 || $y2 >= $this->grid->getHeight()) {
            // Out of range.
            continue;
          }
          for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {
            if ($x2 == $x && $y2 == $y) {
              // Current cell spot.
              continue;
            }
            if ($x2 < 0 || $x2 >= $this->grid->getWidth()) {
              // Out of range.
              continue;
            }
            if ($this->grid->cells[$y2][$x2]) {
              $alive_count += 1;
            }
          }
        }
        return $alive_count;
    }

    public function setTemplate($name) {
        $template = $name . '.txt';
        $path = 'templates/' . $template;
        $file = fopen($path, 'r');
        $centerX = (int) floor($this->grid->getWidth() / 2) / 2;
        $centerY = (int) floor($this->grid->getHeight() / 2) / 2;
        $x = $centerX;
        $y = $centerY;
        while ($c = fgetc($file)) {
          if ($c == 'O') {
            $this->grid->cells[$y][$x] = 1;
          }
          if ($c == "\n") {
            $y++;
            $x = $centerX;
          }
          else {
            $x++;
          }
        }
        fclose($file);
    }

    /**
     * Renders the grid in the terminal window.
     */
    private function render() {
      foreach ($this->_grid->cells as $y => $row) {
        foreach ($row as $x => $cell) {
          /** @var Cell $cell */
          print ($cell ? $this->_opts['cell'] : $this->_opts['empty']);
        }
        // Done with the row.
        print "<br>";
      }
    }

?>
