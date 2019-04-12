<?php
/**
 * Seo Theme Functions.
 *
 * @package lptheme
 * @subpackage Template
 */

 //print css in head============================================
if(animo_get_opt('enable-css-compression')) { 
	add_action( 'wp_enqueue_scripts', 'main_style' );

	function main_style() {
	wp_dequeue_style('css-main');
	//get content
	if (!function_exists('get_style')) {
		function get_style($url)  {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
		}
	}

	$main_css = get_style(get_template_directory_uri(). '/style.css');
	//compress css
	ob_start();
	if (!function_exists('minimizeCSS')) {
  function minimizeCSS($main_css){
		$main_css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $main_css); 
		$main_css = preg_replace('/\s{2,}/', ' ', $main_css);
		$main_css = preg_replace('/\s*([:;{}])\s*/', '$1', $main_css);
		$main_css = preg_replace('/;}/', '}', $main_css);
		return $main_css;
		$main_css = ob_get_contents();
	}
}
	echo '<style>'.minimizeCSS($main_css).'</style>'; 

}
}

// Remove comment-reply.min.js from footer
function clean_header_hook(){
wp_deregister_script( 'comment-reply' );
}
add_action('init','clean_header_hook');

// Remove WP embed script==============================
function infophilic_stop_loading_wp_embed() {
	if (!is_admin()) { wp_deregister_script('wp-embed');}
}
add_action('init', 'infophilic_stop_loading_wp_embed');


function _remove_script_version( $src ){
$parts = explode( '?ver', $src );
return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

//replace jquery version ------------------------------------------------------------------------------- 
if(animo_get_opt('enable-jquery')): { 
	add_action('wp_enqueue_scripts', 'lptheme_load_scripts');
	function lptheme_load_scripts(){
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js');
	}
	
//Remove JQuery migrate
	add_action( 'wp_default_scripts', 'remove_jquery_migrate');
	function remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
	$script = $scripts->registered['jquery'];
	if ( $script->deps ) { 
	$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
	}}}
} endif;

//отключаем стили ------------------------------------------------------------------------------- 
add_action( 'wp_enqueue_scripts', 'remove_styles', 20 );
function remove_styles() {
wp_dequeue_style('wp-block-library-theme' );
wp_dequeue_style('wordfenceAJAXcss' );
wp_dequeue_style('bodhi-svgs-attachment');
wp_dequeue_style('contact-form-7');
wp_dequeue_style('wp-block-library');
wp_dequeue_style('wp-block-library-theme' );
wp_dequeue_style('wordfenceAJAXcss' );
wp_dequeue_style('cf7cf-style');
}

// перенос js в подвал ------------------------------------------------------------------------------- 
function remove_head_scripts() {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

//Remove type tag from script and style ------------------------------------------------------------------------------- 
add_action( 'template_redirect', function(){
	ob_start( function( $buffer ){
			$buffer = str_replace( array( 'type="text/javascript"', "type='text/javascript'" ), '', $buffer );
			$buffer = str_replace( array( 'type="text/css"', "type='text/css'" ), '', $buffer );
			return $buffer;
	});
});



//compress html ------------------------------------------------------------------------------- 
if(animo_get_opt('enable-html-compression')) { 
function teckel_init_minify_html() {
	$minify_html_active = get_option( 'minify_html_active' );
	if ( $minify_html_active != 'no' )
		ob_start('teckel_minify_html_output');
}
if ( !is_admin() )
	if ( !( defined( 'WP_CLI' ) && WP_CLI ) )
		add_action( 'init', 'teckel_init_minify_html', 1 );

function teckel_minify_html_output($buffer) {
	if ( substr( ltrim( $buffer ), 0, 5) == '<?xml' )
		return ( $buffer );
	$minify_javascript = get_option( 'minify_javascript' );
	$minify_html_comments = get_option( 'minify_html_comments' );
	$minify_html_utf8 = get_option( 'minify_html_utf8' );
	if ( $minify_html_utf8 == 'yes' && mb_detect_encoding($buffer, 'UTF-8', true) )
		$mod = '/u';
	else
		$mod = '/s';
	$buffer = str_replace(array (chr(13) . chr(10), chr(9)), array (chr(10), ''), $buffer);
	$buffer = str_ireplace(array ('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array ('M1N1FY-ST4RT<script', '/script>M1N1FY-3ND', 'M1N1FY-ST4RT<pre', '/pre>M1N1FY-3ND', 'M1N1FY-ST4RT<textarea', '/textarea>M1N1FY-3ND', 'M1N1FY-ST4RT<style', '/style>M1N1FY-3ND'), $buffer);
	$split = explode('M1N1FY-3ND', $buffer);
	$buffer = ''; 
	for ($i=0; $i<count($split); $i++) {
		$ii = strpos($split[$i], 'M1N1FY-ST4RT');
		if ($ii !== false) {
			$process = substr($split[$i], 0, $ii);
			$asis = substr($split[$i], $ii + 12);
			if (substr($asis, 0, 7) == '<script') {
				$split2 = explode(chr(10), $asis);
				$asis = '';
				for ($iii = 0; $iii < count($split2); $iii ++) {
					if ($split2[$iii])
						$asis .= trim($split2[$iii]) . chr(10);
					if ( $minify_javascript != 'no' )
						if (strpos($split2[$iii], '//') !== false && substr(trim($split2[$iii]), -1) == ';' )
							$asis .= chr(10);
				}
				if ($asis)
					$asis = substr($asis, 0, -1);
				if ( $minify_html_comments != 'no' )
					$asis = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $asis);
				if ( $minify_javascript != 'no' )
					$asis = str_replace(array (';' . chr(10), '>' . chr(10), '{' . chr(10), '}' . chr(10), ',' . chr(10)), array(';', '>', '{', '}', ','), $asis);
			} else if (substr($asis, 0, 6) == '<style') {
				$asis = preg_replace(array ('/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod), array('>', '<', '\\1'), $asis);
				if ( $minify_html_comments != 'no' )
					$asis = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $asis);
				$asis = str_replace(array (chr(10), ' {', '{ ', ' }', '} ', '( ', ' )', ' :', ': ', ' ;', '; ', ' ,', ', ', ';}'), array('', '{', '{', '}', '}', '(', ')', ':', ':', ';', ';', ',', ',', '}'), $asis);
			}
		} else {
			$process = $split[$i];
			$asis = '';
		}
		$process = preg_replace(array ('/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod), array('>', '<', '\\1'), $process);
		if ( $minify_html_comments != 'no' )
			$process = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->' . $mod, '', $process);
		$buffer .= $process.$asis;
	}
	$buffer = str_replace(array (chr(10) . '<script', chr(10) . '<style', '*/' . chr(10), 'M1N1FY-ST4RT'), array('<script', '<style', '*/', ''), $buffer);
	$minify_html_xhtml = get_option( 'minify_html_xhtml' );
	$minify_html_relative = get_option( 'minify_html_relative' );
	$minify_html_scheme = get_option( 'minify_html_scheme' );
	if ( $minify_html_xhtml == 'yes' && strtolower( substr( ltrim( $buffer ), 0, 15 ) ) == '<!doctype html>' )
		$buffer = str_replace( ' />', '>', $buffer );
	if ( $minify_html_relative == 'yes' )
		$buffer = str_replace( array ( 'https://' . $_SERVER['HTTP_HOST'] . '/', 'http://' . $_SERVER['HTTP_HOST'] . '/', '//' . $_SERVER['HTTP_HOST'] . '/' ), array( '/', '/', '/' ), $buffer );
	if ( $minify_html_scheme == 'yes' )
		$buffer = str_replace( array( 'http://', 'https://' ), '//', $buffer );
	return ($buffer);
} }