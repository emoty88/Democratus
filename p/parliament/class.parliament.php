<?php
class parliament_plugin extends control {
    public function main() {
        global $model, $db, $l;
		$model->template	= "ala";
		$model->view		= "default";
		$model->title 		= 'Democratus - Parliament';
		
		$model->addHeaderElement();
		
		$model->addScript("paths=".json_encode($model->paths));
		$model->addScript("plugin='parliament'");
                
        $model->addScript("var count=".parliament::count_poroposal().';');
        
        $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
        $diff = $tomorrow-time();
        if($diff<3600)
            $tasari = FALSE;
        else
            $tasari = TRUE;
            
        $kaldi =NEXTELECTION-time();
        
        $gun = floor($kaldi/86400);
        $saat = floor(($kaldi-$gun*24*60*60)/3600);
        $dakika = floor((($kaldi-$gun*24*60*60)-$saat*60*60)/60);
        
        if($gun < 10)
            $gun = '0'.$gun;
        if($saat < 10)
            $saat = '0'.$saat;
        if($dakika <10)
            $dakika = '0'.$dakika;
                
		?>
			<section class="banner">
				<header>
					<h1>TÜRKİYE MECLİSİ</h1>
				</header>
				<!-- <img alt="" src="img/banner-adaylarim.png"> -->
				<nav>
					<ul class="alt_menu visible-desktop" id="tab-container" >
						<li class="active"><a href="#tab-referandum" rel="referandum" data-toggle="tab" >REFERANDUM</a></li>
						<li><a href="#tab-vekilsecimleri" rel="vekilsecimleri" data-toggle="tab" >VEKİL SEÇİMLERİ</a></li>
						<li><a href="#tab-donemvekilleri" rel="donemvekilleri" data-toggle="tab" >DÖNEM VEKİLLERİ</a></li>
						<li><a href="#tab-tasariyaz" rel="tasariyaz" data-toggle="tab" >TASARI YAZ</a></li>
						<li><a href="#tab-eskireferandumlar" rel="eskireferandumlar" data-toggle="tab" >ESKİ REFERANDUMLAR</a></li>
					</ul>
					<select class="mobil_menu hidden-desktop" id="alt_menu_mobil" >
						<option selected="" value="#tab-referandum" >REFERANDUM</option>
						<option value="#tab-vekilsecimleri" >VEKİL SEÇİMLERİ</option>
						<option value="#tab-donemvekilleri" rel="donemvekilleri" data-toggle="tab">DÖNEM VEKİLLERİ</option>
						<option value="#tab-tasariyaz">TASARI YAZ</option>
						<option value="#tab-eskireferandumlar">ESKİ REFERANDUMLAR</option>
					</select>
				</nav>
				<div class="clearfix"></div>
			</section>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab-referandum">
					<!-- Referandumlar Tab -->
					<section id="referandum" class="satir ilk_satir uste_cikar">
						<div class="satir_ic">
							<header>
								<h1 class="sayfa_basligi_2">Referandum</h1>
							</header>
						</div>
					</section>
					<section id="referandum-container" class="duvar_yazilari">
						
					</section>
					<!-- Referandumlar Tab Son -->
				</div>
				<div class="tab-pane fade in " id="tab-vekilsecimleri">
					<!-- Vekil Seçimi Tab -->
					<section class="kalan_zaman satir ilk_satir">
						<div class="kalan_zaman_ic">
							<p>Ses’lerini en çok beğendiğiniz arkadaşlarınızı vekil olarak önerin, halk onların ağzından dinlesin. Her hafta 10 vekil önerebilirsiniz. </p>
							<ul style="" class="dijital_saat">
								<li>
									<span class="baslik">Gün</span>
									<span class="sayi"><b><?=$gun?></b><span class="cizgi"></span></span>
								</li>
								<li>
									<span class="baslik">Saat</span>
									<span class="sayi"><b><?=$saat?></b><span class="cizgi"></span></span>
								</li>
								<li>
								<span class="baslik">Dakika</span>
									<span class="sayi"><b><?=$dakika?></b><span class="cizgi"></span></span>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
					</section>
					<!-- Vekil Seçim Sandıklı alan -->
					<section class="satir padding20lr yeni_bilesen" id="vekil_adaylarim">
						<header>
							<h3>Vekil Adaylarım <small><span id="kalanOySayisi"></span> Aday seçme hakkınız daha var.</small></h3>
						</header>
						<div class="yeni_bilesen_icerigi" style="height: 317px;">
							<ul id="vekil_listesi_ul" class="vekil_listesi">
								
							</ul>
							<div class="clearfix"></div>
						</div>
					</section>
					<!-- Vekil Seçim Sandıklı alan son-->
					<!-- Vekil Oyu verilmek üzere  Arkadaşlarının listesi -->
					<section class="satir padding20lr yeni_bilesen beyaz" id="arkadaslarim">
						<header>
							<h3>Arkadaşlarım</h3>
							<div class="komut_tutucu">
								<input type="text" id="findMyFriend" placeholder="Arkadaşları Listele"/>
							</div>

						</header>
						<div class="yeni_bilesen_icerigi" > 
							<ul id="arkadas_listesi_ul" class="arkadas_listesi">
								 			
							</ul>
							<div class="clearfix"></div>
						</div>
					</section>
					<!-- Vekil Oyu verilmek üzere  Arkadaşlarının listesi -->
				<!-- Vekil Seçimi Tab Son -->
				</div>
				<div class="tab-pane fade in " id="tab-donemvekilleri">
					<!-- Donem Vekilleri Tab-->
					<section class="satir ilk_satir uste_cikar boluklari_azalt" id="vekil_secimleri">
						<div class="satir_ic">
							<header class="komut_tutucu_tutucu_2">
								<h1 class="sayfa_basligi_2">Dönem Vekilleri</h1>
							</header>
						</div>
					</section>
					<section id="vekiller">
						<div class="padding20lr">
							<ul class="vekil_secim_listesi"  id="deputy-container">
								
							</ul>
							<div class="clearfix"></div>
						</div>
					</section>
					<!-- Donem Vekilleri Tab Son -->
				</div>
				
				<div class="tab-pane fade in " id="tab-tasariyaz">
				<!-- tasarı yaz tab -->
					<!-- tasarı yaz title  -->
					<section class="satir ilk_satir uste_cikar" id="referandum">
						<div class="satir_ic">
							<header>
								<h1 class="sayfa_basligi_2">Tasarı Yaz</h1>
							</header>
						</div>
                                                <div style="display: none" id="message">message</div>
					</section>
					<!-- tasarı yaz title End -->
					<!-- tasarı yaz textarea -->
					<div class="satir">
						<div class="karakter_sayaci_tutucu" id="yeni_yazi_yaz">
                                                        <?php if($model->profile->deputy == 1 and $tasari ) : ?>
                                                            <textarea rows="2" placeholder="Tasarı Yaz..." class="karakteri_sayilacak_alan" name="yeni_yazi" id="tasari_textarea"></textarea>
                                                            <div class="kalan_karakter_mesaji"><span data-limit="200" class="karakter_sayaci">200</span> karakter</div>

                                                            <div class="kontroller">
                                                                    <button class="btn btn-danger" onclick="set_proposal();" >Tasarı Ekle</button>
                                                                    <!-- 
                                                                    <a href="javascript:void(0)"><i class="atolye15-ikon-gorsel atolye15-ikon-24"></i></a>
                                                                    <a href="javascript:void(0)"><i class="atolye15-ikon-atac atolye15-ikon-24"></i></a>
                                                                    -->
                                                            </div>
                                                        <?php elseif ($model->profile->deputy == 1): ?>
                                                            Gündem tasarılarınızı her gün 23:00'a kadar sunabilirsiniz, oylama ise gece yarısına kadar devam edecektir.
                                                        <?php else: ?>
                                                            Tasarı yazabilmek için vekil olmalısınız.
                                                        <?php endif; ?>
							<div class="clearfix"></div>
						</div>
					</div>
					<!-- tasarı yaz  textarea End -->
					<!-- tartışılan tasarılar -->
					<section class="duvar_yazilari" id="proposalArea">
						
					</section>
					<!-- tartışılan tasarılar Son-->
				<!-- tasarı yaz tab Son -->
				</div>
				<div class="tab-pane fade in " id="tab-eskireferandumlar">
					<!-- Eski Referandumlar -->
					<section class="satir ilk_satir uste_cikar boluklari_azalt" id="eski_referandumlar">
						<div class="satir_ic">
							<header class="komut_tutucu_tutucu_2">
								<h1 class="sayfa_basligi_2">Eski Referandumlar</h1>

							</header>
						</div>
					</section>
					<section id="eskiReferandum-container" class="duvar_yazilari">
						
					</section>
					<!-- Eski Referandumlar Son -->
				</div>
			</div>
			

		<?
		//$model->addScript('');
	}
    public function main_old() {
        return;
        global $model, $db, $l;

        $model->initTemplate('v2', 'parliament');

        $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
        //$model->addScript($model->pluginurl . 'parliament.js', 'parliament.js', 1 );


        $SELECT = "\n SELECT a.*, av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname";
        $FROM = "\n FROM agenda AS a";
        $JOIN = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote($model->profileID);
        $JOIN .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
        $WHERE = "\n WHERE " . $db->quote(date('Y-m-d H:i:s')) . " BETWEEN a.starttime AND a.endtime";
        $GROUP = "\n ";
        $ORDER = "\n ORDER BY a.ID desc";
        $LIMIT = "\n ";

        $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
        $agendas = $db->loadObjectList();
    }

    public function mainoldold() {
        return;
        global $model, $db;
        if ($model->paths[1] == 'ajax')
            return $this->ajax();

        $model->template = 'v2';
        $model->view = 'parliament';

        $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
        $model->addScript($model->pluginurl . 'parliament.js', 'parliament.js', 1);
        ?>
        <div id="parliament">
            <div class="main_title"></div>

            <div id="parliament_slider">
                <div class="slides_container">

                    <!-- Slide BOX 1 -->
                    <div class="slide_box">
                        <div class="head">
                            <div class="image"><img src="/t/v2/static/image/users/medium/1.jpg" /></div>
                            <div class="qoute">
                                <blockquote>
                                    <p>Türkiye, sorunları sıfırlama politikasında bazı puanlar kazandı. Fakat sorunun duvarı yükseldikçe, bu siyaseti sürdürmekteki acizlik açıkça görülüyor. Libya sorununda Ankara, NATO’nun askeri müdahalesinin yanında durmak zorunda kaldı. Afganistan’da yine öyle... Suriye ve müttefiklerine yönelik tutum</p>
                                </blockquote>
                                <span class="cite">Tahsin Durur</span>
                            </div>
                        </div>

                        <div class="clear"></div>
                        <div class="line"></div>

                        <div class="form">
                            <label for="parliament_choose_1_1">Kesinliklike Katılıyorum</label>
                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_1" class="parliamentoption" />

                            <div class="clear"></div>

                            <label for="parliament_choose_1_2">Katılıyorum</label>
                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_2" class="parliamentoption" />

                            <div class="clear"></div>

                            <label for="parliament_choose_1_3">Kararsızım</label>
                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_3" class="parliamentoption" />

                            <div class="clear"></div>

                            <label for="parliament_choose_1_4">Katılmıyorum</label>
                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_4" class="parliamentoption" />

                            <div class="clear"></div>

                            <label for="parliament_choose_1_5">Kesinlikle Katılmıyorum</label>
                            <input type="radio" name="poll_choose_1" id="parliament_choose_1_5" class="parliamentoption" />                        
                        </div>
                        <div class="vertical_line"></div>
                        <div class="statistic">
                            <img src="/t/v2/static/image/background/box/parliament/statistic.png" />
                            <a href="#" class="show_result">Sonuçları Gör</a>
                        </div>
                    </div>

                    <!-- Slide BOX 2 -->
                    <div class="slide_box">

                        <div class="head">
                            <div class="image"><img src="/t/v2/static/image/users/medium/2.jpg" /></div>
                            <div class="qoute">
                                <blockquote>
                                    <p>Türkiye, sorunları sıfırlama politikasında bazı puanlar kazandı. Fakat sorunun duvarı yükseldikçe, bu siyaseti sürdürmekteki acizlik açıkça görülüyor. </p>
                                </blockquote>
                                <span class="cite">Tahsin Durur</span>
                            </div>
                        </div>

                        <div class="clear"></div>
                        <div class="line"></div>

                        <div class="form">
                            <label for="parliament_choose_2_1">Kesinliklike Katılıyorum</label>
                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_1" />

                            <div class="clear"></div>

                            <label for="parliament_choose_2_2">Katılıyorum</label>
                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_2" />

                            <div class="clear"></div>

                            <label for="parliament_choose_2_3">Kararsızım</label>
                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_3" />

                            <div class="clear"></div>

                            <label for="parliament_choose_2_4">Katılmıyorum</label>
                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_4" />

                            <div class="clear"></div>

                            <label for="parliament_choose_2_5">Kesinlikle Katılmıyorum</label>
                            <input type="radio" name="poll_choose_2" id="parliament_choose_2_5" />                        
                        </div>

                        <div class="vertical_line"></div>
                        <div class="statistic">
                            <img src="/t/v2/static/image/background/box/parliament/statistic.png" />
                            <a href="#" class="show_result">Sonuçları Gör</a>
                        </div>                                    
                    </div>
                </div>

            </div>


            <div class="buttons">
                <button class="button_1"></button>
                <button class="button_2"></button>
            </div>

        </div>

        <?php
    }

    public function main1() {
        return;
        global $model, $db;
        if ($model->paths[1] == 'ajax')
            return $this->ajax();

        $model->view = 'agenda';

        $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
        $model->addScript($model->pluginurl . 'agenda.js', 'agenda.js', 1);
        $classes = array('world' => 1, 'region' => 2, 'country' => 3, 'city' => 4, 'foryou' => 10);

        $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1);
        ?>            
        <div id="meclislogo"><img src="<?php echo TEMPLATEURL . 'default/images/meclislogo.gif'; ?>" alt=""></div>
        <div id="agendacontainer">


            <?php
            if (intval($model->paths[1]) > 0) {
                $agendaID = intval($model->paths[1]);
                $agenda = null;
                $db->setQuery('SELECT a.* FROM agenda AS a WHERE a.ID=' . $db->quote($agendaID) . ' LIMIT 1');
                if ($db->loadObject($agenda)) {
                    
                } else {
                    
                }
            } else {
                $agenda = null;
                $db->setQuery('SELECT a.* FROM agenda AS a WHERE ' . $db->quote(date('Y-m-d H:i:s')) . ' BETWEEN a.starttime AND a.endtime AND class=' . $db->quote($classes['country']) . ' ORDER BY ID desc LIMIT 1');


                if ($db->loadObject($agenda)) {
                    
                } else {
                    
                }
            }

            $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
            $response['ID'] = $agenda->ID;
            $response['image'] = $img;
            $response['title'] = $agenda->title;
            $response['dateinfo'] = asdatetime($agenda->starttime, 'd F Y') . ' günü oylamaya açıldı';
            $response['isvotable'] = 1;
            $response['agendagolink'] = '/agenda/' . $agenda->ID;
            $response['agendagotitle'] = 'Meclise Gir';
            $response['agendatimeleft'] = 'Gündemde oy kullanmak için <strong>' . time_left(strtotime($agenda->endtime)) . '</strong> kaldı.';
            $response['moreinfo'] = 'daha fazla bilgi';
            $response['agendalastcomment'] = '';
            $response['agendalastcomments'] = '';

            $db->setQuery('SELECT COUNT(*) FROM agendacomment AS ac WHERE ac.agendaID=' . $db->quote($agenda->ID));
            $totalcomments = intval($db->loadResult());

            if ($totalcomments > 0) {
                $SELECT = "SELECT ac.*, p.image, p.name";
                $FROM = "\n FROM agendacomment AS ac";
                $JOIN = "\n JOIN profile AS p ON p.ID = ac.profileID";
                $WHERE = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                $ORDER = "\n ORDER BY ac.ID DESC";
                $LIMIT = "\n LIMIT 4";

                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);

                $comments = $db->loadObjectList();
                if (count($comments)) {
                    $i = 0;
                    foreach ($comments as $comment) {
                        $i++;
                        if ($i == 1) {
                            $response['agendalastcomment'] = '<img height="32" align="middle" width="32" class="profileimage" alt="" src="' . $model->getProfileImage($comment->image, 32, 32, 'cutout') . '"> <span><strong>' . time_since(strtotime($comment->datetime)) . ' önce ' . $comment->name . ':</strong> ' . $comment->comment . '» Devamı</span>';
                        } else {
                            $response['agendalastcomments'] .= '<strong>' . $comment->name . '</strong> ';
                        }
                    }
                    $response['agendalastcomments'] .= ' ve ' . $totalcomments . ' yorumu okumak için tıklayın';
                }
            }







            $db->setQuery('SELECT COUNT(*) FROM agendavote AS av WHERE av.agendaID=' . $db->quote($agenda->ID));
            $totalvote = intval($db->loadResult());

            $db->setQuery('SELECT av.optionID, COUNT(*) AS total FROM agendavote AS av WHERE av.agendaID=' . $db->quote($agenda->ID) . 'GROUP BY av.optionID');
            $votes = $db->loadObjectList('optionID');


            $db->setQuery('SELECT ao.* FROM agendaoption AS ao WHERE ao.agendaID=' . $db->quote($agenda->ID));
            $aos = $db->loadObjectList();
            $options = array();
            if (count($aos)) {
                foreach ($aos as $ao) {

                    if ($totalvote > 0) {
                        if (array_key_exists($ao->ID, $votes)) {
                            $percent = floor(($votes[$ao->ID]->total * 100) / $totalvote);
                        } else {
                            $percent = 0;
                        }
                    } else {
                        $percent = 0;
                    }

                    $options[$ao->ID] = array('title' => $ao->title, 'percent' => $percent);
                }
            }

            $response['options'] = $options;
            ?>
            <ul id="agendatabs">
                <li rel="city" <?php if ($agenda->class == $classes['city'])
            echo 'class="active"'; ?>>İstanbul</li>
                <li rel="country" <?php if ($agenda->class == $classes['country'])
                echo 'class="active"'; ?>>Türkiye</li>
                <li rel="region" <?php if ($agenda->class == $classes['region'])
                echo 'class="active"'; ?>>Bölge</li>
                <li rel="world" <?php if ($agenda->class == $classes['world'])
                echo 'class="active"'; ?>>Dünya</li>
                <li rel="foryou" <?php if ($agenda->class == $classes['foryou'])
                echo 'class="active"'; ?>>Sizce</li>
            </ul>




            <div id="agenda">
                <div id="agendaimage"> <img src="<?= $model->getImage($agenda->imagepath, 500, 120, 'cutout') ?>" width="500" height="120" alt="" /> </div>
                <div id="agendainfo"><?= $response['dateinfo']; ?></div>
                <div id="agendatitle"><?= $response['title'] ?></div>

                <div id="agendatimeinfo"><?= $response['agendatimeleft'] ?></div>
                <div id="agendamoreinfobtn" rel="<?= $agenda->ID ?>">daha çok bilgi</div>


                <div id="agendaoptions">
                    <?php
                    foreach ($response['options'] as $optionID => $option) {
                        ?>          	
                        <div class="agendaoption">
                            <div class="agendaoptionleft"><input type="radio" id="ao<?= $optionID ?>" value="<?= $optionID ?>" name="ao<?= $agenda->ID ?>"></div>
                            <div class="agendaoptionbody"><?= $option['title'] ?></div>
                            <div class="agendaoptionright"><?= $option['percent'] ?> %</div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div id="agendafooter">
                    <div id="agendavoteinfo">Şu ana dek 12.453 kişi oy kullandı, 345 kişi yorumladı</div>
                    <input type="button" id="agendavote" class="votebutton" rel="<?= $agenda->ID ?>" value="Oyunu Kullan" />
                </div>
                <input type="hidden" name="activeagenda" id="activeagenda" value="<?= $agenda->ID ?>" />

                <div id="agendacomments" class="comments"></div>
                <?php
                $model->addScript('activeagenda = ' . $agenda->ID);
                ?>          
            </div>




        </div>
        <?php
    }

    public function block() {
        return;
        global $model, $db;


        $db->setQuery('SELECT a.* FROM agenda AS a ORDER BY ID desc LIMIT 1');
        $agenda = null;
        if ($db->loadObject($agenda)) {

            $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
            if (strlen($img))
                echo '<img src="' . $img . '" alt="" />';

            echo '<h1>' . $agenda->title . '</h1>';
            //echo '<h3>'. $agenda->starttime .' günü oylamaya açıldı</h3>';
            echo '<h3>' . asdatetime($agenda->starttime, 'd F Y') . ' günü oylamaya açıldı</h3>';

            echo '<h4><a href="/agenda/' . $agenda->ID . '"> Meclise gir</a></h4>';
        } else {
            echo 'agenda not found';
        }
    }

    public function ajax() {
        global $model;
        $model->mode = 0;
        $method = (string) 'ajax_' . $model->paths[2];
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            
        }
    }

    public function ajax_agenda() {
        return;
        global $model, $db;
        $what = filter_input(INPUT_POST, 'what', FILTER_SANITIZE_STRING);
        $response = array();

        $classes = array('world' => 1, 'region' => 2, 'country' => 3, 'city' => 4, 'foryou' => 10);

        $class = array_key_exists($what, $classes) ? $classes[$what] : $classes[0];

        $db->setQuery('SELECT a.* FROM agenda AS a WHERE ' . $db->quote(date('Y-m-d H:i:s')) . ' BETWEEN a.starttime AND a.endtime AND class=' . $db->quote($class) . ' ORDER BY ID desc LIMIT 1');
        $agenda = null;
        if ($db->loadObject($agenda)) {

            $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
            $response['ID'] = $agenda->ID;
            $response['image'] = $img;
            $response['title'] = $agenda->title;
            $response['dateinfo'] = asdatetime($agenda->starttime, 'd F Y') . ' günü oylamaya açıldı';
            $response['isvotable'] = 1;
            $response['agendagolink'] = '/agenda/' . $agenda->ID;
            $response['agendagotitle'] = 'Meclise Gir';
            $response['moreinfo'] = 'Daha çok bilgi';
            $response['agendatimeleft'] = 'Gündemde oy kullanmak için <strong>' . time_left(strtotime($agenda->endtime)) . '</strong> kaldı.';
            $response['agendalastcomment'] = '';
            $response['agendalastcomments'] = '';

            $db->setQuery('SELECT COUNT(*) FROM agendacomment AS ac WHERE ac.agendaID=' . $db->quote($agenda->ID));
            $totalcomments = intval($db->loadResult());

            if ($totalcomments > 0) {
                $SELECT = "SELECT ac.*, p.image, p.name";
                $FROM = "\n FROM agendacomment AS ac";
                $JOIN = "\n JOIN profile AS p ON p.ID = ac.profileID";
                $WHERE = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                $ORDER = "\n ORDER BY ac.ID DESC";
                $LIMIT = "\n LIMIT 4";

                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);

                $comments = $db->loadObjectList();
                if (count($comments)) {
                    $i = 0;
                    foreach ($comments as $comment) {
                        $i++;
                        if ($i == 1) {
                            $response['agendalastcomment'] = '<img height="32" align="middle" width="32" class="profileimage" alt="" src="' . $model->getProfileImage($comment->image, 32, 32, 'cutout') . '"> <span><strong>' . time_since(strtotime($comment->datetime)) . ' önce ' . $comment->name . ':</strong> ' . $comment->comment . '» Devamı</span>';
                        } else {
                            $response['agendalastcomments'] .= '<strong>' . $comment->name . '</strong> ';
                        }
                    }
                    $response['agendalastcomments'] .= ' ve ' . $totalcomments . ' yorumu okumak için tıklayın';
                }
            }



            //die('agenda');




            $db->setQuery('SELECT COUNT(*) FROM agendavote AS av WHERE av.agendaID=' . $db->quote($agenda->ID));
            $totalvote = intval($db->loadResult());

            $db->setQuery('SELECT av.optionID, COUNT(*) AS total FROM agendavote AS av WHERE av.agendaID=' . $db->quote($agenda->ID) . 'GROUP BY av.optionID');
            $votes = $db->loadObjectList('optionID');

            $db->setQuery('SELECT ao.* FROM agendaoption AS ao WHERE ao.agendaID=' . $db->quote($agenda->ID));
            $aos = $db->loadObjectList();
            $options = array();
            if (count($aos)) {
                foreach ($aos as $ao) {
                    if ($totalvote > 0) {
                        if (array_key_exists($ao->ID, $votes)) {
                            $percent = floor(($votes[$ao->ID]->total * 100) / $totalvote);
                        } else {
                            $percent = 0;
                        }
                    } else {
                        $percent = 0;
                    }

                    $options[$ao->ID] = array('title' => $ao->title, 'percent' => $percent);
                }
            }

            $response['options'] = $options;


            $response['result'] = 'success';
        } else {
            $response['result'] = 'error';
        }

        echo json_encode($response);
    }

    public function ajax_vote() {
        global $model, $db;
        $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
        $optionID = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
        $profileID = intval($model->user->profileID);

        $response = array();

        $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID=' . $db->quote($agendaID) . ' LIMIT 1');
        $agenda = null;
        if ($db->loadObject($agenda)) {

            $db->setQuery('SELECT av.* FROM agendavote AS av WHERE agendaID=' . $db->quote($agendaID) . ' AND profileID=' . $db->quote($profileID) . ' LIMIT 1');
            $av = null;
            if ($db->loadObject($av)) {
                //voted
                $response['result'] = 'error';
                $response['message'] = 'allready voted';
            } else {
                //not voted
                $av = new stdClass;
                $av->agendaID = $agendaID;
                $av->profileID = $profileID;
                $av->optionID = $optionID;
                $av->datetime = date('Y-m-d H:i:s');
                $av->userID = intval($model->user->ID);
                $av->ip = ip2long($_SERVER['REMOTE_ADDR']);

                if ($db->insertObject('agendavote', $av)) {
                    //new vote saved
                    $response['result'] = 'success';
                } else {
                    //not saved
                    $response['result'] = 'error';
                    $response['message'] = 'save error';
                }
            }
            //$response['result']='success';
        } else {
            $response['result'] = 'error';
            $response['message'] = 'error';
        }

        $response['image'] = $model->getProfileImage($model->profile->image, 60, 60, 'cutout');

        echo json_encode($response);
    }

    public function ajax_commentit() {
        global $model, $db;
        $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
        $optionID = filter_input(INPUT_POST, 'optionID', FILTER_SANITIZE_NUMBER_INT);
        $sip = filter_input(INPUT_POST, 'showinprofile', FILTER_SANITIZE_NUMBER_INT);
        $comment = htmlspecialchars_decode(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING), ENT_QUOTES);
        $profileID = intval($model->profileID);

        $response = array();

        $db->setQuery('SELECT a.* FROM agenda AS a WHERE ID=' . $db->quote($agendaID) . ' LIMIT 1');
        $agenda = null;
        if ($db->loadObject($agenda)) {

            $db->setQuery('SELECT ac.* FROM agendacomment AS ac WHERE agendaID=' . $db->quote($agendaID) . ' AND profileID=' . $db->quote($profileID) . ' LIMIT 1');
            $ac = null;
            if ($db->loadObject($ac)) {
                //voted
                $response['result'] = 'error';
                $response['message'] = 'allready commented';
            } else {
                //not voted
                $ac = new stdClass;
                $ac->agendaID = $agendaID;
                $ac->profileID = $profileID;
                $ac->optionID = $optionID;
                $ac->comment = $comment;
                $ac->datetime = date('Y-m-d H:i:s');
                $ac->userID = intval($model->profileID);
                $ac->ip = ip2long($_SERVER['REMOTE_ADDR']);

                if ($db->insertObject('agendacomment', $ac)) {
                    //new comment saved
                    $response['result'] = 'success';
                    $response['image'] = $model->getProfileImage($model->profile->image, 60, 60, 'cutout');
                    $response['comment'] = $comment;
                    $response['name'] = $model->profile->name;
                    $response['info'] = time_since(time()) . ' önce yazdı';




                    //show in profile
                    if ($sip > 0) {

                        //show in profile 
                        $share = new stdClass;
                        $share->url = '/agenda/' . $agenda->ID;
                        $share->title = $agenda->title;
                        $share->description = $comment;

                        //save the share
                        $share->type = 3; //agenda comment

                        $share->datetime = date('Y-m-d H:i:s');
                        $share->sharerID = intval($model->profileID);
                        $share->profileID = intval($model->profileID);
                        $share->userID = intval($model->user->ID);
                        $share->ip = ip2long($_SERVER['REMOTE_ADDR']);

                        if ($db->insertObject('share', $share)) {
                            $response['status'] = 'success';
                        } else {
                            throw new Exception('record error');
                        }



                        //$response['result']='success';
                    }
                } else {
                    //not saved
                    $response['result'] = 'error';
                    $response['message'] = 'save error';
                }
            }
            //$response['result']='success';
        } else {
            $response['result'] = 'error';
            $response['message'] = 'undefined error';
        }

        echo json_encode($response);
    }

    public function ajax_like() {
        global $model, $db;
        $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
        $like = filter_input(INPUT_POST, 'like', FILTER_SANITIZE_NUMBER_INT);

        $profileID = intval($model->user->profileID);

        $response = array();

        $db->setQuery("SELECT acl.* FROM agendacommentlike AS acl WHERE acl.commentID=" . $db->quote($ID) . " AND acl.profileID=" . $db->quote($profileID) . " LIMIT 1" . "\n #" . __FILE__ . " - " . __LINE__);
        $acl = null;
        if ($db->loadObject($acl)) {
            switch ($like) {
                case 1: $acl->regard = 1;
                    $acl->appreciate = 0;
                    break;
                case 2: $acl->regard = 0;
                    $acl->appreciate = 1;
                    break;
                default: $acl->regard = 0;
                    $acl->appreciate = 0;
            }
            $db->updateObject('agendacommentlike', $acl, 'ID');
        } else {
            $acl = new stdClass;

            switch ($like) {
                case 1: $acl->regard = 1;
                    $acl->appreciate = 0;
                    break;
                case 2: $acl->regard = 0;
                    $acl->appreciate = 1;
                    break;
                default: $acl->regard = 0;
                    $acl->appreciate = 0;
            }

            $acl->commentID = intval($ID);
            $acl->datetime = date('Y-m-d H:i:s');
            $acl->profileID = intval($profileID);
            $acl->userID = intval($model->user->ID);
            $acl->ip = ip2long($_SERVER['REMOTE_ADDR']);

            $db->insertObject('agendacommentlike', $acl);
        }



        echo json_encode($response);
    }

    public function ajax_moreinfo() {
        global $model, $db;
        $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);

        $profileID = intval($model->user->profileID);

        $response = array();

        $db->setQuery("SELECT a.* FROM agenda AS a WHERE a.ID=" . $db->quote($ID) . " LIMIT 1" . "\n #" . __FILE__ . " - " . __LINE__);
        $a = null;
        if ($db->loadObject($a)) {
            $response['result'] = 'success';
            $response['moreinfo'] = $a->content;
        } else {
            $response['result'] = 'error';
            $response['moreinfo'] = '';
        }



        echo json_encode($response);
    }

    public function ajax_comments() {
        global $model, $db;
        $agendaID = filter_input(INPUT_POST, 'agendaID', FILTER_SANITIZE_NUMBER_INT);
        $response = array();

        $db->setQuery('SELECT a.* FROM agenda AS a WHERE a.ID=' . $db->quote($agendaID) . ' LIMIT 1');
        $agenda = null;
        if ($db->loadObject($agenda)) {

            $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
            $response['ID'] = $agenda->ID;
            $response['image'] = $img;
            $response['title'] = $agenda->title;
            $response['dateinfo'] = asdatetime($agenda->starttime, 'd F Y') . ' günü oylamaya açıldı';
            $response['isvotable'] = 0;
            $response['voteinfo'] = 'this is vote info';

            $SELECT = "SELECT ac.*, p.image, p.name";
            $FROM = "\n FROM agendacomment AS ac";
            $JOIN = "\n JOIN profile AS p ON p.ID = ac.profileID";
            $WHERE = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
            $ORDER = "\n ORDER BY ac.ID DESC";
            $LIMIT = "\n LIMIT 10";

            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);

            //$db->setQuery('SELECT ac.* FROM agendacomment AS ac WHERE ac.agendaID='.$db->quote($agenda->ID));
            $acs = $db->loadObjectList();
            $comments = array();
            if (count($acs)) {
                foreach ($acs as $ac) {
                    $comments[$ac->ID] = array(
                        'image' => $model->getProfileImage($ac->image, 60, 60, 'cutout'),
                        'name' => $ac->name,
                        'comment' => $ac->comment,
                        'info' => time_since(strtotime($ac->datetime)) . ' önce yazdı.'
                    );
                }
            }
            $response['comments'] = $comments;


            $response['result'] = 'success';
        } else {

            $response['result'] = 'error';
        }

        echo json_encode($response);
    }

}
?>
