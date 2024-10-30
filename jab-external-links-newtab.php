<?php
/*
Plugin Name: JAB External Link New Tab
Plugin URI: http://www.juarbo.com/jab-external-link-new-tab/
Description: Todos los enlaces externos de tu sitio se abriran en una nueva ventana, ya que este plugin agrega <strong>target="_blank"</strong> a todos estos enlaces.
Author: Julian Bohorquez
Version: 1.3
Author URI: http://www.juarbo.com
*/

$jab_imagen_nueva_ventana = true;

function jab_nombre_dominio_de_url($uri){
	preg_match("/^(http:\/\/)?([^\/]+)/i", $uri, $matches);
	$host = $matches[2];
	preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
	return $matches[0];	   
}

function jab_tal_imagen($uri) {
	
	$cadena=strtolower($uri);
	if ((strpos($cadena, '.jpg') > 0) || (strpos($cadena, '.gif') > 0) || (strpos($cadena, '.png')  > 0))
	{
		return true;		 
	} 
	return false; 
} 

function jab_parse_link_externo($matches){
    global $jab_imagen_nueva_ventana;

	if (($jab_imagen_nueva_ventana && jab_tal_imagen($matches[3])) || ( jab_nombre_dominio_de_url($matches[3]) != jab_nombre_dominio_de_url($_SERVER["HTTP_HOST"]) )) {
		return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . ' target="_blank">' . $matches[5] . '</a>';	 
	} else {
		return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . '>' . $matches[5] . '</a>';
	}
}
 
function jab_link_externo($text) {	

	$pattern = '/<a (.*?)href="(.*?)\/\/(.*?)"(.*?)>(.*?)<\/a>/i';
	$text = preg_replace_callback($pattern,'jab_parse_link_externo',$text);
	 
	return $text;
}


add_filter('the_content', 'jab_link_externo', 999);
add_filter('the_excerpt', 'jab_link_externo', 999);
add_filter('comment_text', 'jab_link_externo', 999);
?>
