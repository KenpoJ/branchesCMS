<?php
require( "application/config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
	case 'archive':
		archive();
		break;
	case 'viewPost':
		viewPost();
		break;
	default:
		homepage();
}

function archive() {
	$results = array();
	$data = Post::getList();
	$results['posts'] = $data['results'];
	$results['totalRows'] = $data['totalRows'];
	$results['pageTitle'] = "Archives";
	require( TEMPLATE_PATH . "/archive.php" );
}

function viewPost() {
	if ( !isset($_GET["postID"]) || !$_GET["postID"] ) {
		homepage();
		return;
	}
	$results = array();
	$results['post'] = Post::getById( (int)$_GET["postID"] );
	$results['pageTitle'] = $results['post']->title . " | Branch CMS";
	require( TEMPLATE_PATH . "/single.php" );
}

function homepage() {
	$results = array();
	$data = Post::getList( HOMEPAGE_NUM_ARTICLES );
	$results['posts'] = $data['results'];
	$results['totalRows'] = $data['totalRows'];
	$results['pageTitle'] = "BranchesCMS";
	require( TEMPLATE_PATH . "/homepage.php" );
}

function parse_path() {
	$path = array();
	if (isset($_SERVER['REQUEST_URI'])) {
		$request_path = explode('?', $_SERVER['REQUEST_URI']);

		$path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
		$path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
		$path['call'] = utf8_decode($path['call_utf8']);
		if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
			$path['call'] = '';
		}
		$path['call_parts'] = explode('/', $path['call']);

		$path['query_utf8'] = urldecode($request_path[1]);
		$path['query'] = utf8_decode(urldecode($request_path[1]));
		$vars = explode('&', $path['query']);
		foreach ($vars as $var) {
			$t = explode('=', $var);
			$path['query_vars'][$t[0]] = $t[1];
		}
	}
	return $path;
}

$path_info = parse_path();
echo '<pre>'.print_r($path_info, true).'</pre>';

switch($path_info['call_parts'][0]) {
	case 'about-us': 
		include 'about.php';
		break;
	case 'users': 
		include 'users.php';
		break;
	case 'news': 
		include 'news.php';
		break;
	case 'products': 
		include 'products.php';
		break;
	default:
		include 'index.php';
}

?>