<?php 
include_once(LIB_DIR."db/db_table.php");

class User_Table extends STable{
    var $member_id;
    var $member_user;
    var $member_title;
    var $member_first_name;
    var $member_last_name;
    var $member_email;
    var $member_pass;
    var $member_status;
    var $member_detail_status;
    var $member_newsletter;
    var $member_delivery_id;
    var $member_password_mode;
    var $member_date_created;
    var $member_last_login;
    var $member_verified_email;
    var $member_verified_mobile;
    
	function __construct( $table = "members", $key = "member_id" )
	{
		$this->_table		= $table;
		$this->_key	        = $key;
	}
}

class UserSettings_Table extends STable{
	var $setting_id;
    var $setting_member;
    var $setting_newsletter;
    
    function __construct( $table = "members_settings", $key = "setting_member" )
	{
		$this->_table		= $table;
		$this->_key	        = $key;
	}
    
}

?>