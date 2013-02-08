<?php
    class share_plugin extends control{
        
        public function main(){
            global $model, $db;
            
            if($model->userID<1)
                return $this->notloggedin();
            
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $profileID = filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
            
            $share = array();
            $share ['title'] = $title;
            
            $share ['datetime'] = date('Y-m-d H:i:s');
            $share ['profileID'] = intval( $model->user->profileID );
            $share ['sharerID'] = intval( $model->user->profileID );
            $share ['userID'] = intval( $model->user->ID );
            $share ['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
            
            $share = (object) $share;
            
            if( $db->insertObject('#__share', $share) ){
                echo '<strong>success</strong>';
            } else {
                echo '<strong>error</strong>';
            }
        }
        
        public function notloggedin(){
            echo 'not logged in';
        }
    }
?>