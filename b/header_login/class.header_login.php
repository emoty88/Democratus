<?php
    class header_login_block extends control{
        
        public function block(){
            global $model, $db, $l;
?>
                <!-- Login [Begin] -->
                <div id="header_login">
                    <form method="post">
                        <input type="text" name="" class="email" value="E-posta:" />
                        <span class="password_wrapper">
                            <input type="text" name="" rel="password" value="Şifre:" />
                            <input type="password" name="password" rel="password_focus" class="password" style="display: none" />
                            <button></button>
                        </span>
                        
                        <div class="clear"></div>
                        
                        <span class="remember_me"><input type="checkbox" name="" class="remember" />Beni Hatırla</span>
                        <span class="forget_password"><a href="#">Şifremi unuttum</a></span>
                    </form>
                    
                </div>
                <!-- Login [End] -->
<?php
        }
    }
?>