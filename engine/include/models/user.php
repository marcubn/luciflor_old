<?php
include_once(LIB_DIR."db/tables/user.php");

/**
 * Front User Model
 *
 * @version 2.0
 */
class UserModel{

    
    /**
     * Fetches an object filled with User and User Profile Info for Logged in user
     * 
     * @return object
     */
    function fetchUserProfile(){
        
        if($_SESSION[SESS_IDX]['UL']['member_id']){

            $user_profile = STable::tableInit("members_profile","profile_id");
            $user_profile->_key = "profile_member";
            $user_profile->load( $_SESSION[SESS_IDX]['UL']['member_id'] );
            
            $delivery_id = (int)$_SESSION[SESS_IDX]['UL']['member_delivery_id'];
            $user_address = STable::tableInit("member_delivery_address","id");
            $user_address->load($delivery_id);
            $user_profile->address = $user_address;
            //$user_profile->member = _sqlGetRowContent("members", "member_id", $_SESSION[SESS_IDX]['UL']['member_id']);
            return $user_profile;
        }else
            return null;
        
    }
    
    /**
     * User Login
     * 
     * @param array $data with username and pass key
     * @return boolean
     */
    function login($data){
        
        $db = SDatabase::getInstance();
        include_once(LIB_DIR."utile/validator.php");

        $v = new Validator($data);

        $v->setErrorMessage('username', 'Introduceti un nume de utilizator sau un email valid!');
        $v->setErrorMessage('pass',     'Introduceti parola!');

        $v->filledIn('username');
        $v->filledIn('pass');

        if (!$v->isValid()){
            
            systemMessage::addMessage($v->getErrors());
            return false;
            
        }else{

            $user = strtolower($db->getEscaped($data['username']));
            $pass = $db->getEscaped(md5($data['pass']));
            
            $db->setQuery("SELECT * FROM members WHERE ( LOWER(member_email='{$user}')) AND member_pass='{$pass}'  and (member_tmp_pass_generated='0000-00-00 00:00:00' OR  date_add(member_tmp_pass_generated, INTERVAL 1 day) >= NOW() ) ");
            //echo $db->getQuery();exit;
            $result = $db->loadAssocList();

            if( isset($result[0]) ){

                $record = $result[0];
                if( $record['member_detail_status']!="active" && $record['member_detail_status']!="pending" ){

                    systemMessage::addMessage("Contul nu este activat!");
                    return false;
                }else{
                    
                    $_SESSION[SESS_IDX]['UL'] = $record;
                    $_SESSION[SESS_IDX]['UL']['auth'] = 1;

                    $db->query("UPDATE members SET member_last_login = NOW() WHERE member_id={$record['member_id']}");

                    /*
                    if( $record["member_last_login"] )
                        systemMessage::addMessage("Bine ai revenit!");
                    else
                        systemMessage::addMessage("Bine ai venit!");
                    */
                    return true;
                }

            }else{

                systemMessage::addMessage("Email sau parola gresita!", 2);
                return false;

            }
        }
        
        return false;
    }
    
    /**
     * Logoff Current User
     * 
     */
    function logoff(){
        
        $_SESSION[SESS_IDX]['UL']['auth'] = 0;
        unset($_SESSION[SESS_IDX]['UL']);
        
    }
    
    /**
     * Signs up an user
     * 
     * @param array $data
     */
    function signup($data){
        $app = SApp::getInstance();
        $smarty = $app->getTemplate();
        $db = SDatabase::getInstance();
    	include_once(LIB_DIR."utile/validator.php");

        $v = new Validator($data);
        $v->setErrorMessage('member_first_name', 'Introduceti prenumele!');	
        $v->setErrorMessage('member_last_name', 'Introduceti numele!');
        $v->setErrorMessage('member_pass', 'Introduceti parola!');
        $v->setErrorMessage('member_email', 'Introduceti o adresa valida de email!');

        $v->filledIn('member_first_name');
        $v->filledIn('member_last_name');
        if(!$data["member_guest"] && $data["member_user_source"]=="")
        	$v->filledIn('member_pass');
        
        $v->filledIn('member_email');
        $v->email('member_email');

        if(!$data["member_guest"] && $data["member_user_source"]=="")
        {
        	if ( ($data['member_pass'] != $data['member_pass_confirm']) ) {
        		$v->pushError("member_pass", "Va rugam sa confirmati parola in campul de repetare parola!");
        	}
        }

        if ($db->search("members", "count", "`member_email`=".$data['member_email'])){
                $v->pushError("member_email", "Acest email a fost deja inregistrat! Daca aveti un cont va rugam sa va recuperati parola.");
        }

        if ($db->search("members_profile", "count", "`profile_cnp`='".$data['profile_cnp']."'")){
                $v->pushError("member_email", "Exista un cont cu acest CNP deja! Daca aveti un cont va rugam sa va recuperati parola.");
        }
        
        if ($db->search("members_profile", "count", "`profile_tel`='".$data['profile_tel']."'")){
                $v->pushError("member_email", "Exista un cont cu acest numar de telefon deja! Daca aveti un cont va rugam sa va recuperati parola.");
        }
        
        if (!$v->isValid()){
        	$this->_errors = $v->getErrors();
            return false;
        }else{
            
        	$status = "pending";
        	if($data["member_guest"])
        		$status = "temporary";
        	
        	if(!$data['member_user_source'])
        		$data['member_user_source'] = "";
        	
        	if(!$data['member_user'])
        		$data['member_user'] = "";
        	 
            $q="INSERT INTO 
                            members
                    SET 
                            member_user_source	=	'".$db->getEscaped($data['member_user_source'])."',
                            member_user			=	'".$db->getEscaped($data['member_user'])."',
                            		
                            member_first_name	=	'".$db->getEscaped($data['member_first_name'])."',
                            member_last_name	=	'".$db->getEscaped($data['member_last_name'])."',
                            member_email		=	'".$db->getEscaped($data['member_email'])."',
                            member_pass			=	'".$db->getEscaped(md5($data['member_pass']))."',
                            member_date_created	=	'".time()."',
                            member_status	=	1,
							member_ip		= '".getIP()."',
                            member_detail_status = '{$status}'  
            ";
            $db->query($q);
            $this->_id = $db->insertid();
            
            if(isset($data["profile_cnp"]) && isset($data["profile_tel"])){
            	$profile_table = STable::tableInit("members_profile", "id");
            	$profile_table->bind($data);
            	$profile_table->profile_member = $this->_id;
            	$profile_table->store();
            }


            /*
            $q = "SELECT * FROM members WHERE (member_id='{$member_id}')";
            $result = _sqlFetchQuery($q);
            
            ob_start();
            $smarty->assign("title","Contul dvs pe ".SITE_NAME);
            $smarty->assign("message","Contul dvs pe ".SITE_NAME." a fost creat!"."<br />User:". _sqlEscValue($data['member_email'])."<br> Parola: "._sqlEscValue($data['member_pass']));
            $smarty->display("wmail/email.tpl");
            $message = ob_get_contents();
            ob_end_clean();

            @send_mail($_POST['member_email'], EMAIL_FROM_ADDR, EMAIL_FROM_NAME, $message, "Contul dumneavoastra pe ".SITE_NAME);
            */
            
            //systemMessage::addMessage("Informatiile furnizate de dumneavoastra au fost salvate cu succes.");
            return true;

        }
    }
    
    /**
     * Returns list of delivery addreses for parameter User id
     * 
     * @param int $userid
     * @return array of objects
     */
    function getDeliveryAddresses($userid){
    	$userid = (int)$userid;
    	if($userid==0)
    		return false;
    	$db = SDatabase::getInstance();
    	$db->setQuery("select * from member_delivery_address where status=1 and member={$userid}");
    	return $db->loadObjectList(); 
    }
}