<?php
    class parliament_block extends control{
    	public function block(){
        	global $model;
			?>
			<section class="banner">
				<header>
					<h1>TÜRKİYE MECLİSİ</h1>
				</header>
				<img alt="" src="img/banner-adaylarim.png">
				<nav>
					<ul class="alt_menu visible-desktop" id="tab-container" >
						<li class="active"><a href="#tab-referandum" rel="referandum" data-toggle="tab" >REFERANDUM</a></li>
						<li><a href="#tab-vekilsecimleri" rel="vekilsecimleri" data-toggle="tab" >VEKİL SEÇİMLERİ</a></li>
						<li><a href="#tab-donemvekilleri" rel="donemvekilleri" data-toggle="tab" >DÖNEM VEKİLLERİ</a></li>
						<li><a href="#tab-tasariyaz" rel="tasariyaz" data-toggle="tab" >TASARI YAZ</a></li>
						<li><a href="#tab-eskireferandumlar" rel="eskireferandumlar" data-toggle="tab" >ESKİ REFERANDUMLAR</a></li>
					</ul>
					<select class="mobil_menu hidden-desktop" id="alt_menu_mobil">
						<option value="http://atolye15.com">REFERANDUM</option>
						<option value="http://atolye15.com">DÖNEM VEKİLLERİ</option>
						<option value="http://atolye15.com">VEKİL SEÇİMLERİ</option>
						<option selected="" value="http://atolye15.com">ADAYLARIM</option>
						<option value="http://atolye15.com">ESKİ REFERANDUMLAR</option>
					</select>
				</nav>
				<div class="clearfix"></div>
			</section>
			<?
		}
	}
?>
