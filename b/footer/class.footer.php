<?php
    class footer_block extends control{
        public function block()
		{
			global $model;
			?>
				<!-- Footer -->
				<footer>
					<div class="container">
						<div class="row">
							<div class="span6">
								<ul>
									<li><a href="#">Hakkımızda</a></li>
									<li><a href="#">Yardım</a></li>
									<li><a href="#">Koşullar</a></li>
									<li><a href="gizlilik.html">Gizlilik</a></li>
									<li><a href="#">İletişim</a></li>	
								</ul>
							</div>
							<div class="span6">
								<address class="copyright">2012 © <a href="#" title="Democratus">Democratus</a></address>
							</div>
						</div>
					</div>
				</footer><!-- // Footer -->
			<?
		}
        public function block_old(){
            global $model, $db, $l;
?>
            <!-- FOOTER [BEGIN] -->
            <style>
				#footer { 
					height: 40px; 
					width: 875px; 
					background-color: #F5F5F5; 
				    border: 1px solid #DFDFDF;
				    
				    border-radius: 5px 5px 5px 5px;
				    box-shadow: 0 2px 0 #FFFFFF inset, 0 1px 2px rgba(0, 0, 0, 0.05);
				    color: #444;
					margin: 10px auto; 
					-webkit-border-radius: 5px; 
					-moz-border-radius: 5px; 
					border-radius: 5px; 
        		}
				#footer .democratus { float: left; font-size: 12px; margin-left: 20px; margin-top: 13px; color: #797069; }
				#footer ul { list-style: none; float: right; margin-top: 13px; margin-right: 20px; }
				#footer ul li { float: left; font-size: 12px; margin-left: 20px; }
				#footer ul li a { text-decoration: none;color: #797069; }
			</style>
            <div id="footer">
                <div class="democratus">2011 &copy; Democratus</div>
                <ul>
                    <li><a id="guideBtn" class="fnc" onclick=""  href="/home/userguide">Kullanım Klavuzu</a></li>
                    <li><a href="/about" title="">Democratus</a></li>
                    <li><a href="/contact" title="">İletişim</a></li>
                </ul>
            </div>
            <!-- FOOTER [END] -->    
<?php
        }
    }
?>
