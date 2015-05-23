<?php
function up546E_recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                up546E_recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
function up546E_install_jbf_theme() {
    $dir = get_theme_root() . '/jbf';
	if (!file_exists($dir) and !is_dir($dir)) {
        up546E_recurse_copy(dirname(__FILE__) . '/jbf', $dir);
    } 

}

?>