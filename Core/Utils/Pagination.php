<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Utils;

/**
 * Esta clase genera urls y calcula paginas siguientes y restantes
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Utils
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Pagination
{
    public $showing_text;

    public $resource;
    private $_cursor;
    public $items_per_page;
    public $num_results;
    public $num_results_this_page;
    public $page;
    private $num_pages;

    public function __construct($resource, $items_per_page = 10, $page = false) {
        $this->resource = $resource;
        $this->items_per_page = $items_per_page;
        $this->page = ($page) ? $page : \Core\Libs\Url::getPage();
        $this->skip = $this->items_per_page * $this->page - $this->items_per_page;
        $this->showing_text = 'Mostrando {start}-{end} de {total}';

        $this->_cursor = $resource;
        $this->num_results = count($this->_cursor);
        $this->num_pages = ceil($this->num_results/$this->items_per_page);
    }

    public function getCursor() {
        //skip
        $this->_cursor = array_slice($this->_cursor, $this->skip);
        //limit
        $this->_cursor = array_slice($this->_cursor, 0, $this->items_per_page);
        $this->num_results_this_page = count($this->_cursor);
        return $this->_cursor;
    }

    public function sort($query) {
        //$this->_cursor->sort($query);
    }

    public function getNextPage() {
        if($this->page < $this->num_pages) {
            return array('value' => $this->page+1, 'text' => 'Siguiente', 'class' => 'siguiente');
        }
        else {
            return false;
        }
    }

    public function getPreviousPage() {
        if($this->page >= 2) {
            return array('value' => $this->page-1, 'text' => 'Anterior', 'class' => 'anterior');
        }
        else {
            return false;
        }
    }

    public function getFistPage() {
        return array('value' => '1', 'text' => 'Primera', 'class' => 'primera');
    }

    public function getLastPage() {
        return array('value' => $this->num_pages, 'text' => 'Ultima', 'class' => 'ultima');
    }

    public function showing() {
        $start = $this->skip+1;
        $end = (($start+$this->items_per_page-1) > $this->num_results) ? $this->num_results : $start+$this->items_per_page-1;
        return str_replace(array('{start}', '{end}', '{total}'), array($start, $end, $this->num_results), $this->showing_text);
    }

    public function getPages() {

    }
}
?>
