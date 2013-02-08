<?php
    class headerlogin_block extends control{
        
        public function block(){
            global $model, $db, $l;
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1 );
?>
                <!-- Login [Begin] -->
                <div id="header_login">
                    <form method="post" action="">
                        <input type="text" name="email" class="email focus" value="Email" tabindex="1" />
                        <span class="password_wrapper"  style="width:300px; display: block;">
                            
                            <input type="text" name="pw" rel="password" value="Password" class="focus" tabindex="2"/>
                            <input type="password" name="password" rel="password_focus" class="password" style="display: none;" tabindex="3" />
                            <button id="header_login_button" onclick="return false;"  style="float: right;" tabindex="4"></button>
                        </span>
                        
                        <div class="clear"></div>
                        <span class="forget_password" id="forget_password"><a href="#">Forgot it?</a></span>
                        <span class="forget_password" ><input type="checkbox" name="remember" value="1" /><i>remember me</i></span>
                    </form>
                    
                </div>
                <!-- Login [End] -->
<?php
        }
    }
?>
