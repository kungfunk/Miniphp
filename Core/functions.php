<?php
/**
 * This file is part of Mierdium Framework.
 *
 * (c) Victor Calzón <victor@victorcalzon.com>
 */

function is_ajax() {
    return(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function printr($var) {
    echo '<pre>' . htmlspecialchars(print_r($var, true)) . '</pre>';
}

function redirect($url = null) {
    if(is_null($url)) $url = $_SERVER['PHP_SELF'];
    header("Location: $url");
}

function home() {
    redirect(DOC_URL);
}

function css($file, $label = false) {
    return ($label) ? '<link rel="stylesheet" type="text/css" href="'.DOC_URL.'/static/css/'.$file.'" />' : DOC_URL.'/static/css/'.$file;
}

/**
 * Devuelve un include válido para archivos js o la ruta completa de ese archivo. El nombre del archivo puede ser una URL.
 *
 * @param array $files array de archivos a cargar, cada elemento será a su vez un array con los elementos: 'file' -> nombre del archivo y 'tag' -> true para que devuelva solo la ruta completa del archivo. false para el include
 *
 * @param string $files [FUNCIONALIDAD ANTIGUA] nombre del archivo
 * @param boolean $tag [FUNCIONALIDAD ANTIGUA] true para que devuelva solo la ruta completa del archivo. false para el include
 */
function js($files, $tag=false) {
    if(is_array($files)){
        $retorno = false;
        foreach ($files as $file){
            if(!isset($file['tag'])){
                $file['tag'] = false;
            }
            if(strrpos($file['file'],'://')){
                $retorno .= ($file['tag']) ? '<script type="text/javascript" src="'.$file["file"].'" /></script>' : $file["file"];
            }else{
                $retorno .= ($file['tag']) ? '<script type="text/javascript" src="'.DOC_URL.'/static/js/'.$file["file"].'" /></script>' : DOC_URL.'/static/js/'.$file["file"];
            }
        }
        return $retorno;
    }else{
        if(strrpos($files,'://')){
            return ($files) ? '<script type="text/javascript" src="'.$files.'" /></script>' : $files;
        }else{
            return ($files) ? '<script type="text/javascript" src="'.DOC_URL.'/static/js/'.$files.'" /></script>' : DOC_URL.'/static/js/'.$files;
        }
    }
}

function image($file, $subdir = null) {
    return ($subdir) ? DOC_URL.'/static/images/'.$subdir.'/'.$file : DOC_URL.'/static/images/'.$file;
}

// wrapper para la traduccion de cadenas
function __($string, $language = null, $useCache = true) {
    $i18n = I18n::getI18n();
    $i18n->useCache = $useCache;
    if($language) $i18n->setLanguage($language);
    echo $i18n->translate($string);
}

function getFiles($directory, $exempt = array('.','..','.ds_store','.svn'),&$files = array()) {
    if(false == ($handle = @opendir($directory)))
        throw new \Core\Libs\Fail('Error al aceder al directorio '.$directory, 11);
    while(false !== ($resource = readdir($handle))) {
        if(!in_array(strtolower($resource),$exempt)) {
            if(is_dir($directory.$resource.'/'))
                array_merge($files,
                    $this->getFiles($directory.$resource.'/',$exempt,$files));
            else
                $files[] = array('real_path' => $directory.$resource, 'filename' => $resource, 'directory' => str_replace($this->path, '', $directory));
        }
    }
    closedir($handle);
    return $files;
}
?>
