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
    

/**
 * Create a user.

 */
function user_create_bulk( $p_username, $p_password, $p_email = '',
	$p_access_level = null, $p_protected = false, $p_enabled = true,
	$p_realname = '', $p_admin_name = '', $p_send_email= 1 ) {
	if( null === $p_access_level ) {
		$p_access_level = config_get( 'default_new_account_access_level' );
	}

	$t_password = auth_process_plain_password( $p_password );

	$c_enabled = (bool)$p_enabled;

	user_ensure_name_valid( $p_username );
	user_ensure_name_unique( $p_username );
	user_ensure_email_unique( $p_email );
	user_ensure_realname_unique( $p_username, $p_realname );
	email_ensure_valid( $p_email );

	$t_cookie_string = auth_generate_unique_cookie_string();

	db_param_push();
	$t_query = 'INSERT INTO {user}
				    ( username, email, password, date_created, last_visit,
				     enabled, access_level, login_count, cookie_string, realname )
				  VALUES
				    ( ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param() . ', ' . db_param()  . ',
				     ' . db_param() . ',' . db_param() . ',' . db_param() . ',' . db_param() . ', ' . db_param() . ')';
	db_query( $t_query, array( $p_username, $p_email, $t_password, db_now(), db_now(), $c_enabled, (int)$p_access_level, 0, $t_cookie_string, $p_realname ) );

	# Create preferences for the user
	$t_user_id = db_insert_id( db_get_table( 'user' ) );

	# Users are added with protected set to FALSE in order to be able to update
	# preferences.  Now set the real value of protected.
	if( $p_protected ) {
		user_set_field( $t_user_id, 'protected', (bool)$p_protected );
	}

	# Send notification email
	if( $p_send_email==1 ) {
		if( !is_blank( $p_email ) ) { 
			$t_confirm_hash = auth_generate_confirm_hash( $t_user_id );
			token_set( TOKEN_ACCOUNT_ACTIVATION, $t_confirm_hash, TOKEN_EXPIRY_ACCOUNT_ACTIVATION, $t_user_id );
			email_signup( $t_user_id, $t_confirm_hash, $p_admin_name );
		}
	}

	event_signal( 'EVENT_MANAGE_USER_CREATE', array( $t_user_id ) );

	return $t_cookie_string;
} 	
	
	# Check given parameters - File
	$f_import_file = gpc_get_file( 'import_file', -1 ); 

	$t_file_content = array();
	$t_file_content = file( $f_import_file['tmp_name'] );

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
		$f_sendmail		    = $t_file_row[5];

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
		$t_cookie = user_create_bulk( $f_username, $f_password, $f_email, $f_access_level, $f_protected, $f_enabled, $f_realname, $t_admin_name, $f_sendmail );
		echo "User created : ".$f_username;
		echo "<br>";
	}
echo "Please hit the back button of your browse to return to mantis";
