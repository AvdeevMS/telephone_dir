<?php
  ini_set( "display_errors", true );
  date_default_timezone_set( "Asia/Vladivostok" );
  define( "DB_DSN", "mysql:host=localhost; dbname=telephone_dir" );
  define( "DB_USERNAME", "TD_USER" );
  define( "DB_PASSWORD", "beef" );
  define( "CLASS_PATH", "classes" );
  define( "TEMPLATE_PATH", "templates" );  
  require(CLASS_PATH . "/Record.php" );
  require(CLASS_PATH . "/City.php" );
  require(CLASS_PATH . "/Street.php" );
    
  function handleException( $exception ){
    echo "Sorry, a problem occured. Please try later.";
    error_log( $exception->getMessage() );
  }
  
  set_exception_handler( 'handleException' );
?>