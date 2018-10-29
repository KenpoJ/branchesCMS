<?php
ini_set( "display_errors", true );
date_default_timezone_set( "America/Los_Angeles" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=branches" );
define( "DB_USERNAME", "branches" );
define( "DB_PASSWORD", "dVwAYNvpHQXDmJjr" );
define( "CLASS_PATH", "application/classes" );
define( "TEMPLATE_PATH", "application/templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "FlashingSw0rds" );
require( CLASS_PATH . "/post.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.<br>";
  echo $exception->getMessage();
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );
?>