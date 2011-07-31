<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

namespace Core\Utils;

/**
 * Sube ficheros a al sistema de archivos del framework
 * 
 * @package Mierdium Framework
 * @subpackage Core
 * @category Utils
 * @author Victor Calzón <victor@victorcalzon.com>
 */
class Uploader
{
    public $path;
    private $tempStore;
    public $overwrite_on_exist;

    public $filetype;
    public $filename;
    public $name;
    public $directory;
    public $use_date;
    public $fullpath;


    public function __construct() {
        $this->path = ROOT_PATH.'/public/uploads/';
        $this->overwrite_on_exist = false;
        $this->tempStore = \App\Config::get('tempStore');
    }

    public function file(array $args, $force_upload = true, $force_database = true) {
        $this->filetype = (isset($args['filetype'])) ? $args['filetype'] : null;
        $this->filename = time();
        $this->name = (isset($args['name'])) ? $args['name'] : null;
        $this->tempname = (isset($args['tempname'])) ? $args['tempname'] : null;
        $this->directory = (isset($args['directory'])) ? $args['directory'] : null;
        $this->use_date = (isset($args['use_date'])) ? $args['use_date'] : false;
        $this->fullpath = ($this->use_date) ? $this->path.$this->directory.$this->generate_date_directory().'/' : $this->path.$this->directory;
        if($force_upload)
            if(!$this->upload())
                return false;
        if($force_database)
            if(!$this->to_database())
                return false;
        return true;
    }

    public function upload() {
        if(!is_dir($this->fullpath))
            $this->generate_directory();
        return move_uploaded_file($this->tempname, $this->fullpath.$this->filename);
    }

    private function generate_directory() {
        return @\mkdir($this->fullpath, 0777, true);
    }

    private function generate_date_directory() {
        return date("d-m-Y");
    }

    private function to_database() {        
        return \App\Models\Archivo::alta($this->filename, $this->name, $this->fullpath, $this->filetype);
    }
}
?>