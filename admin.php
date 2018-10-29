<?php

require( "application/config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
	login();
	exit;
}

switch ( $action ) {
	case 'login':
		login();
		break;
	case 'logout':
		logout();
		break;
	case 'newPost':
		newPost();
		break;
	case 'editPost':
		editPost();
		break;
	case 'deletePost':
		deletePost();
		break;
	default:
		listPosts();
}


function login() {
	$results = array();
	$results['pageTitle'] = "Admin Login";

	if ( isset( $_POST['login'] ) ) {
		// User has posted the login form: attempt to log the user in
		if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

		// Login successful: Create a session and redirect to the admin homepage
		$_SESSION['username'] = ADMIN_USERNAME;
		header( "Location: admin.php" );
		} else {
			// Login failed: display an error message to the user
			$results['errorMessage'] = "Incorrect username or password. Please try again.";
			require( TEMPLATE_PATH . "/admin/login.php" );
		}
	} else {
		// User has not posted the login form yet: display the form
		require( TEMPLATE_PATH . "/admin/login.php" );
	}
}

function logout() {
	unset( $_SESSION['username'] );
	header( "Location: admin.php" );
}

function newPost() {

	$results = array();
	$results['pageTitle'] = "New Post";
	$results['formAction'] = "newPost";

	if ( isset( $_POST['saveChanges'] ) ) {
		// User has posted the post edit form: save the new post
		$post = new Post;
		$post->storeFormValues( $_POST );
		$post->insert();
		header( "Location: admin.php?status=changesSaved" );
	} elseif ( isset( $_POST['cancel'] ) ) {
		// User has cancelled their edits: return to the post list
		header( "Location: admin.php" );
	} else {
		// User has not posted the post edit form yet: display the form
		$results['post'] = new Post;
		require( TEMPLATE_PATH . "/admin/edit-post.php" );
	}

}


function editPost() {

	$results = array();
	$results['pageTitle'] = "Edit Post";
	$results['formAction'] = "editPost";

	if ( isset( $_POST['saveChanges'] ) ) {
		// User has posted the post edit form: save the post changes
		if ( !$post = Post::getById( (int)$_POST['postID'] ) ) {
			header( "Location: admin.php?error=postNotFound" );
			return;
		}

		$post->storeFormValues( $_POST );
		$post->update();
		header( "Location: admin.php?status=changesSaved" );
	} elseif ( isset( $_POST['cancel'] ) ) {
		// User has cancelled their edits: return to the post list
		header( "Location: admin.php" );
	} else {
	// User has not posted the post edit form yet: display the form
	$results['post'] = Post::getById( (int)$_GET['postID'] );
	require( TEMPLATE_PATH . "/admin/edit-post.php" );
	}

}


function deletePost() {
	if ( !$post = Post::getById( (int)$_GET['postID'] ) ) {
		header( "Location: admin.php?error=postNotFound" );
		return;
	}

	$post->delete();
	header( "Location: admin.php?status=postDeleted" );
}


function listPosts() {
	$results = array();
	$data = Post::getList();
	$results['posts'] = $data['results'];
	$results['totalRows'] = $data['totalRows'];
	$results['pageTitle'] = "All Posts";

	if ( isset( $_GET['error'] ) ) {
		if ( $_GET['error'] == "postNotFound" ) $results['errorMessage'] = "Error: Post not found.";
	}

	if ( isset( $_GET['status'] ) ) {
		if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
		if ( $_GET['status'] == "postDeleted" ) $results['statusMessage'] = "Post deleted.";
	}

	require( TEMPLATE_PATH . "/admin/list-posts.php" );
}

?>

<h1>Admin Home</h1>