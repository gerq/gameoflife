<?php

class My_Game {

  private $_opts = array();
  private $_grid = null;

  function __construct(array $opts) {
    $this->_setDefaults($opts);
    $this->_grid = new My_Grid($this->_opts['width'], $this->_opts['height']);

    $this->_grid->generateCells($this->_opts['random'], $this->_opts['rand_max']);
    if (!empty($this->_opts['template'])) {
      $this->setTemplate($this->_opts['template']);
    }

  }

  private function _setDefaults(array $opts) {
      $defaults = [
        'template' => NULL,
        'rand_max' => 5,
        'random' => TRUE,
        'cell' => 'O',
        'empty' => '&#9632;',
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
    public function newGeneration() {
      $cells = &$this->_grid->cells;
      $kill_queue = $born_queue = [];
      for ($y = 0; $y < $this->_grid->getHeight(); $y++) {
        for ($x = 0; $x < $this->_grid->getWidth(); $x++) {
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
          if ($y2 < 0 || $y2 >= $this->_grid->getHeight()) {
            // Out of range.
            continue;
          }
          for ($x2 = $x - 1; $x2 <= $x + 1; $x2++) {
            if ($x2 == $x && $y2 == $y) {
              // Current cell spot.
              continue;
            }
            if ($x2 < 0 || $x2 >= $this->_grid->getWidth()) {
              // Out of range.
              continue;
            }
            if ($this->_grid->cells[$y2][$x2]) {
              $alive_count += 1;
            }
          }
        }
        return $alive_count;
    }

    public function setTemplate($name) {
        $template = $name . '.txt';
        $path = APPLICATION_PATH . '/../templates/' . $template;
        $file = fopen($path, 'r');
        $centerX = (int) floor($this->_grid->getWidth() / 2) / 2;
        $centerY = (int) floor($this->_grid->getHeight() / 2) / 2;

        $x = $centerX;
        $y = $centerY;
        while ($c = fgetc($file)) {
          if ($c == 'O') {
            if(isset($this->_grid->cells[$y][$x])) {
              $this->_grid->cells[$y][$x] = 1;
            }
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

    public function addExtraPoints($points) {
      foreach ($points as $key => $p) {
        if(isset($this->_grid->cells[$p[0]][$p[1]])) {
          $this->_grid->cells[$p[0]][$p[1]] = 1;
        }
      }
    }

    /**
     * Renders the grid
     */
    public function render() {
      print '<pre>';
      foreach ($this->_grid->cells as $y => $row) {
        foreach ($row as $x => $cell) {
          /** @var Cell $cell */
          print ($cell ? $this->_opts['cell'] : $this->_opts['empty']);
        }
        // Done with the row.
        print "<br>";
      }
    }

    /**
     * Get the grid data for the API call
     */
    public function getTable() {
      return $this->_grid->cells;
    }

    public function setTable(array $cells) {
      $this->_grid->cells = $cells;
    }

}

?>
