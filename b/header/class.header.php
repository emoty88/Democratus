<?php
    class header_block extends control{
    	public function block ()
		{
			global $model;
			?>
			<!-- Üst Kısım -->
			<div id="ust_kisim" class="gradyan_dolgu_1">
				<div class="container">
					<div class="row landscape-tablet-row">
						<!-- Logo -->
						<div class="span3 landscape-tablet-span3">
							<header>
								<hgroup>
									<h1><a href="#" title="Democratus :: Ana Sayfa">Democratus</a></h1>
									<h2>S"osyal paylaşım platformu...</h2>
								</hgroup>
								<a id="logo" href="/" title="Democratus :: Ana Sayfa">
									<img src="<?=TEMPLATEURL?>/ala/img/logo.png" alt="Democratus Logo">
								</a>
								
								<!-- Mobil Menü Tetikleyici -->
								<button id="mobil_menu_tetikleyici" type="button" class="btn visible-phone" data-tetikleyici="ac-kapa" data-hedef="#ana_menu">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button><!-- // Mobil Menü Tetikleyici -->
							</header>
						</div><!-- // Logo -->

						<!-- Arama Formu -->
						<div class="span4  landscape-tablet-span3 hidden-phone">
							<div id="arama_formu">
								<form action="#" method="post">
									<input type="submit" value="Ara" id="arama_dugmesi">
									<input type="text" name="q" placeholder="Arama yapın ..." id="arama_kutusu" class="bradius5">
								</form>
							</div>
						</div><!-- // Arama Formu -->

						<!-- Üye Menüsü -->
						<div class="span5 landscape-tablet-span6 tab prelative">
							<nav>
								<ul id="ana_menu">
									<li class="separator"></li>
									<? $this->get_noticeIcon(); ?>
									<li class="separator"></li>
									<? $this->get_messageIcon(); ?>
									<li class="separator"></li>
									<? $this->get_userArea()?>
								</ul>
								<div class="clearfix"></div>
							</nav>
						</div><!-- // Üye Menüsü -->

					</div>
				</div>
			</div><!-- // Üst Kısım -->
			<?
		}
		function get_userArea()
		{
			global $model;
			?>
			<li class="acilir_menu_tutucu">
				<a class="uye_profili" href="javascript:;">
					<img src="<?=$model->getProfileImage($model->profile->image, 22,22, 'cutout')?>" alt="<?=$model->profile->name?> Profil Resmi">
					<span><?=$model->profile->name?></span>
				</a>
				<ul class="acilir_menu">
					<li><a href="/<?=$model->profileID?>"><i class="icon-user icon-white"></i> Profilim</a></li>
					<li><a href="/my"><i class="icon-wrench icon-white"></i> Ayarlar</a></li>
					<li class="son"><a href="/user/logout"><i class="icon-off icon-white"></i> Çıkış</a></li>
				</ul>
			</li>
			<?
		}
		function get_noticeIcon()
		{
		?>
			<li>
				<a id="noticeIcon" class="bildirim" href="javascript:;">
					<span class="ikon"><i class="atolye15-ikon-uyari atolye15-ikon-32"></i></span>
					<span id="noticeCount" class="bildirim_sayisi" style="display:none;"></span>
				</a>
			</li>
		<?
		}
		function get_messageIcon()
		{
		?>
			<li>
				<a id="messageIcon" class="bildirim" href="/message" >
					<span class="ikon"><i class="atolye15-ikon-mesaj atolye15-ikon-32"></i></span>
					<span id="messageCount" class="bildirim_sayisi" style="display:none;"></span>
				</a>
			</li>
		<?
		}
	}
?>