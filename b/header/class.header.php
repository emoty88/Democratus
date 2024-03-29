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
									<h2>Sosyal paylaşım platformu...</h2>
								</hgroup>
								<a id="logo" href="/" title="Democratus :: Ana Sayfa">
									<img src="<?=TEMPLATEURL?>/ala/img/logo.png" alt="Democratus Logo">
								</a>
								
								<!-- Mobil Menü Tetikleyici -->
								<button id="mobil_menu_tetikleyici" type="button" class="btn visible-phone" data-tetikleyici="responsive" data-hedef="#ana_menu">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button><!-- // Mobil Menü Tetikleyici -->
							</header>
						</div><!-- // Logo -->

						<!-- Arama Formu -->
						<div class="span4  landscape-tablet-span3 hidden-phone">
							<div id="arama_formu">
								<?php
									$keyword = "";
									if($model->paths[0]=="search" && $model->paths[1]!="")
									{
										$keyword = $model->paths[1];
									} 
								?>
								<form id="arama_formuForm"  onsubmit="gotoSearch(); return false;" method="post">
									<input type="submit" value="Ara" id="arama_dugmesi">
									<input type="text" name="q" placeholder="Arama yap ..." id="arama_kutusu" class="bradius5" value="<?=$keyword?>">
								</form>
							</div>
						</div><!-- // Arama Formu -->

						<!-- Üye Menüsü -->
						<div class="span5 landscape-tablet-span6 tab prelative">
							<nav>
								<ul id="ana_menu">
                                                                    <?php if($model->profileID>0): ?>
									<li class="separator"></li>
									<? $this->get_noticeIcon(); ?>
									<li class="separator"></li>
									<? $this->get_messageIcon(); ?>
									<li class="separator"></li>
									<? $this->get_userArea()?>
                                                                    <?php else:?>
                                                                        <li>
                                                                            <button title="Giriş Yap" onclick="javascript:window.location='/welcome'" class="btn btn-danger" >Giriş Yap</button>
                                                                        </li>
                                                                        <li>
                                                                            <button style="margin-left: 20px" title="Giriş Yap" onclick="javascript:window.location='/welcome?register'" class="btn btn-success" >Kayıt Ol</button>
                                                                        </li>
                                                                        
                                                                    <?php endif; ?>
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
					<li><a href="/<?=$model->profile->permalink?>"><i class="icon-user icon-white"></i> Profilim</a></li>
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
				<a id="noticeIcon" onclick="_gaq.push(['_trackEvent', 'noticeBtn', 'clicked'])"  class="bildirim" href="javascript:;">
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
				<a id="messageIcon" onclick="_gaq.push(['_trackEvent', 'messageBtn', 'clicked'])" class="bildirim" href="/message" >
					<span class="ikon"><i class="atolye15-ikon-mesaj atolye15-ikon-32"></i></span>
					<span id="messageCount" class="bildirim_sayisi" style="display:none;"></span>
				</a>
			</li>
		<?
		}
	}
?>