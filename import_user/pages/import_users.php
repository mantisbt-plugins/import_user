<?php
	# Mantis - a php based bugtracking system

	require_once( 'core.php' );
    
	$t_core_path = config_get( 'core_path' );
	require_once( $t_core_path . 'database_api.php' );    
    require_once( $t_core_path . 'user_api.php' );
  
  
    	# Get submitted data
	$f_import_file = gpc_get_string( 'import_file' );
	$f_skip_first = gpc_get_bool( 'cb_skip_first_line' );     
    $f_separator = gpc_get_string('edt_cell_separator');     
    
	
	
	# Check given parameters - File
	$f_import_file = gpc_get_file( 'import_file', -1 ); 

	$t_file_content = array();
	if( file_exists( $f_import_file['tmp_name'] ) )
	{
		$t_file_content = file( $f_import_file['tmp_name'] );

	}
	else
	{
		error_parameters( lang_get( 'import_error_file_not_found' ) );
		trigger_error( ERROR_IMPORT_FILE_FORMAT, ERROR );
	};

	# Import file content
 
    $t_first_run = true;

	foreach( $t_file_content as $t_file_row )
	{
		# Check if first row skipped
		if( $t_first_run && $f_skip_first )
		{
			$t_first_run = false;
			continue;
		};

		# Explode into elements
		$t_file_row = explode( $f_separator, $t_file_row );

		# Variables
		$f_username        = $t_file_row[0];
		$f_realname        = $t_file_row[1];
		$f_password        = $t_file_row[3];
		$f_password_verify = $t_file_row[3];
		$f_email           = $t_file_row[2];
		$f_access_level    = $t_file_row[4];
		$f_protected       = FALSE;
		$f_enabled         = TRUE; 

		# check access level
		$f_access_level = trim($f_access_level);
		if ( is_blank( $f_access_level ) ) {
			$f_access_level = 10;
		}
		# check for empty username
		$f_username = trim( $f_username );
		if ( is_blank( $f_username ) ) {
			continue;
		} 
		# check if user already exists
		if( !user_is_name_valid( $f_username ) ) {
			echo "Not a valid username : ".$f_username;
			echo "<br>";
			continue;
		} 
		if ( !user_is_name_unique($f_username) ) {
			echo "User already exist :  ".$f_username;
			echo "<br>";
			continue;
		}
		#check if it is a valid email address
		if ( is_blank( $f_email ) || !strchr( $f_email, '@' ) ) {
			echo "No valid email email address for ".$f_username;
			echo "<br>";
			continue;
		} 
		# check unique email address
		if (!user_is_email_unique( $f_email ) ) {
			echo "Email address already in use for ".$f_username;
			echo "<br>";
			continue;
		}
		# adduser
		$t_admin_name = user_get_name( auth_get_current_user_id() );
		$t_cookie = user_create( $f_username, $f_password, $f_email, $f_access_level, $f_protected, $f_enabled, $f_realname, $t_admin_name );
		echo "User created : ".$f_username;
		echo "<br>";
	}
echo "Please hit the back button of your browse to return to mantis";
