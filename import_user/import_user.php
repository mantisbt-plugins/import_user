<?php
class import_userPlugin extends MantisPlugin {

	function register() {
		$this->name			= 'import_user' ;
		$this->description	= 'import users from CSV file';
		$this->version		= '1.0';
		$this->requires   	= array('MantisCore'       => '2.0.0',);
		$this->author		= 'Cas Nuy';
		$this->contact		= '';
		$this->url			= '';
	}

    function hooks() {
        $hooks = array
        (
                'EVENT_MENU_MANAGE' => 'user_import_menu',
        );
        return $hooks;
    }

	function user_import_menu() {
		return array
        (
				'<a href="' . plugin_page( 'import_users_page' ) . '">' . lang_get( 'manage_import_users_link' ) . '</a>',
		);
	}
}