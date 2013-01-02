<?php
class archive_plugin extends control {
	
	public function main() {
		global $model, $db, $l;
		if ($model->paths [1] == 'more')
			return $this->more ();
			
			// $model->newDesign=false;
		if ($model->newDesign)
			$model->initTemplate ( 'beta', 'agenda' );
		else
			$model->initTemplate ( 'v2', 'agenda' );
		
		$model->title = 'Meclis';
		
		if ($model->newDesign) {
			if ($model->paths [1] == "search") {
				$model->mode = 0;
				$wordPost = filter_input ( INPUT_POST, 'word', FILTER_SANITIZE_STRING );
				echo json_encode ( $this->friendList ( $wordPost ) );
				die ();
			}
			if ($model->paths [1] == "page") {
				$model->mode = 0;
				$page = filter_input ( INPUT_POST, 'page', FILTER_SANITIZE_STRING );
				echo json_encode ( $this->friendList ("", $page ) );
				die ();
			}
			
			$model->addScript ( TEMPLATEURL . "beta/docs/assets/js/jquery.js", "jquery.js", 1 );
			$model->addScript ( PLUGINURL . 'lib/common.js', 'common.js', 1 );
			$model->addScript ( '/t/v2/static/javascript/countdown.js', 'countdown.js', 1 );
			$model->addScript ( "var activeStatistic=0;" );
			$model->addScript ( $model->pluginurl . 'archive.js', 'archive.js', 1 );
			$model->addScript ( 'var DATEINPHP="' . date ( 'F d, Y H:i', NEXTELECTION ) . '";' );
			$model->addScript("paths=".json_encode($model->paths));
			
			?>
<ul class="nav nav-tabs" id="tab">
	<li class="active">
		<button data-toggle="tab" href="#tab-vekillistesi" rel="vekillistesi" class="tabbtn tooltip-top" data-original-title="Oylarınızla her hafta şeçilen vekillerin listesi.">Vekil Listesi</button>
	</li>
	<li class="">
		<button data-toggle="tab" href="#tab-vekilsecimi" rel="vekilsecimi" class="tabbtn tooltip-top" data-original-title="Gelecek haftanın meclisi için 10 vekil adayınızı belirleyin.">Vekil Seçimi</button>
	</li>
	<li class="">
		<button data-toggle="tab" href="#tab-arsiv" rel="arsiv" class="tabbtn tooltip-top" data-original-title="Oylamaya sunulmuş eski gündemler.">Arşiv</button>
	</li>
	<li class="">
		<button data-toggle="tab" href="#tab-tasari" rel="tasari" class="tabbtn last tooltip-top" data-original-title="Yarının ülke gündemini belirlemek için tasarınızı yazın.">Tasarı Yaz</button>
	</li>
</ul>
<div id="myTabContent" class="tabuser-content">
	<div class="tab-pane fade in active" id="tab-vekillistesi">
		<div class="roundedcontent">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Dönem
				Vekilleri
			</h1>
	          		<?php
			$SELECT = "SELECT DISTINCT pr.* ,f1.followerstatus, f1.followingstatus";
			$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=" . $db->quote ( $model->profileID ) . " AND md.deputyID=pr.ID) AS mydeputy";
			$SELECT .= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=pr.ID AND di.status>0) AS di_count";
			$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=pr.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>pr.ID AND di.status>0) AS dilike1_count";
			$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=pr.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>pr.ID AND di.status>0) AS dilike2_count";
			$FROM = "\n FROM profile AS pr";
			$JOIN = "\n ";
			$JOIN .= "\n LEFT JOIN follow AS f1 ON f1.followingID=pr.ID AND f1.followerID=" . intval ( $model->profileID );
			$WHERE = "\n WHERE pr.deputy>0";
			$WHERE .= "\n AND pr.status>0";
			$ORDER = "\n ";
			// $LIMIT = "\n LIMIT " . config::$mydeputylimit;
			$LIMIT = "\n ";
			
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT );
			$rows = $db->loadObjectList ();
			
			if (count ( $rows )) {
				$html = '';
				$htmls = '';
				$i = 0;
				foreach ( $rows as $row ) {
					
					if ($model->profileID > 0) {
						if ($row->followingstatus > 0) {
							$follow = 'hide';
							$unfollow = '';
						} else {
							$follow = '';
							$unfollow = 'hide';
						}
						
						if ($row->ID == $model->profileID) {
							$follow_button = '<button class="btn btn-vekil">Sensin</button>';
						} else {
							$follow_button = '<button id="follow' . $row->ID . '"  class="btn btn-vekil follow ' . $follow . '" rel="' . $row->ID . '">Takip Et</button>
						                    				  <button id="unfollow' . $row->ID . '"  class="btn btn-takipetme unfollow ' . $unfollow . '" rel="' . $row->ID . '">Takip Etme</button>
						                                      ';
						}
					} else {
						$follow_button = '';
					}
					
					$SELECT = "SELECT DISTINCT f.followingID, p.*";
					$FROM = "\n FROM #__follow AS f";
					$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followerID";
					$WHERE = "\n WHERE f.followingID=" . $db->quote ( $row->ID );
					$WHERE .= "\n AND f.status>0";
					$ORDER = "\n ORDER BY f.datetime DESC";
					$LIMIT = "\n LIMIT 5";
					
					$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipci = intval ( $db->loadResult () );
					
					$SELECT = "SELECT f.followerID, p.*";
					$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
					
					$FROM = "\n FROM #__follow AS f";
					$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followingID";
					$WHERE = "\n WHERE f.followerID=" . $db->quote ( $row->ID );
					$WHERE .= "\n AND f.status>0";
					$ORDER = "\n ORDER BY f.datetime DESC";
					$LIMIT = "\n LIMIT 5";
					
					$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipedilen = intval ( $db->loadResult () );
					?>
                            	<div class="usrlist-pic">
				<a href="/profile/<?=$row->ID?>"> <img
					src="<?=$model->getProfileImage($row->image, 67, 67, 'cutout')?>">
				</a>
			</div>
			<div class="usrlist-info">
				<table class="table-striped" style="width: 100%">
					<tbody>
						<tr>
							<th style="width: 150px;"><span> <a href="/profile/<?=$row->ID?>">
														<?=$row->name." ".$row->surname?>
													</a>
							</span></th>
							<th><a href="#"><?=$row->di_count?> Ses</a></th>
							<th><a href="#"><?=$row->dilike1_count?> Takdir</a></th>
							<th><a href="#"><?=$row->dilike2_count?> Saygı</a></th>

						</tr>
						<tr>
							<td colspan="5"><p><?=$row->motto?></p></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="usrlist-set">
				<ul>
					<li>
											<?=$follow_button?>
										</li>
					<li><strong><?=$countTakipedilen?></strong> Takip Ettiği</li>
					<li><strong><?=$countTakipci?></strong> Takipçi</li>
				</ul>
			</div>
			<hr class="rounded_hr">
                            <?php
				}
			
			}
			?>
	          		</div>
	</div>
	<div class="tab-pane fade in "id="tab-vekilsecimi">
		<style>
		#time span {
			float: none;
		}
		</style>
		<div class="roundedcontent">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Vekil
				Seçimi <span id="time" class="time"></span>
			</h1>
			<p>Ses’lerini en çok beğendiğiniz arkadaşlarınızı vekil olarak
				önerin, halk onların ağzından dinlesin. Oylama saati gelene kadar
				vekillerinizi değiştirebilirsiniz.</p>
			<p></p>
			<!-- <form action="" class="" onsubmit="return false;"> -->
				<input type="text" id="vekilSecSerch" placeholder="Arama"
					class="search-query width460" /> <i class="icon-search"></i>
			<!-- </form> -->
			<p></p>
			<div id="takipcilerContent">
				          	<?php
			$sonuc = $this->friendList ();
			echo $sonuc ["html"];
			?>
						</div>

		</div>
		<p></p>
		<div class="pagination">
			<input type="hidden" id="currentPage" val="1" />
			<ul id="paging" style="float:right;">
			<?=$sonuc["navNumHtml"];?>
			</ul>
		</div>
		<div class="roundedcontent">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Vekil
				Adaylarınız
			</h1>
					<?php
					
			$SELECT = "SELECT DISTINCT md.*,pr.ID profileID, pr.image, pr.name, pr.surname, pr.motto";
			$SELECT .= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=pr.ID AND di.status>0) AS di_count";
			$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=pr.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>pr.ID AND di.status>0) AS dilike1_count";
			$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=pr.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>pr.ID AND di.status>0) AS dilike2_count";
			$FROM = "\n FROM mydeputy AS md";
			$JOIN = "\n LEFT JOIN #__profile AS pr ON pr.ID = md.deputyID";
			$WHERE = "\n WHERE md.profileID = " . $db->quote ( intval ( $model->profileID ) );
			$WHERE .= "\n AND md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) );
			$WHERE .= "\n AND md.status>0";
			$ORDER = "\n ORDER BY md.datetime DESC";
			$LIMIT = "\n LIMIT " . config::$mydeputylimit;
			
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT );
			$rows = $db->loadObjectList ();
			
			if (count ( $rows )) {
				$html = '';
				$htmls = '';
				$i = 0;
				foreach ( $rows as $row ) {
					$SELECT = "SELECT DISTINCT f.followingID, p.*";
					$FROM = "\n FROM #__follow AS f";
					$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followerID";
					$WHERE = "\n WHERE f.followingID=" . $db->quote ( $row->profileID );
					$WHERE .= "\n AND f.status>0";
					$ORDER = "\n ORDER BY f.datetime DESC";
					$LIMIT = "\n LIMIT 5";
					
					$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipci = intval ( $db->loadResult () );
					
					$SELECT = "SELECT f.followerID, p.*";
					$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
					
					$FROM = "\n FROM #__follow AS f";
					$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followingID";
					$WHERE = "\n WHERE f.followerID=" . $db->quote ( $row->profileID );
					$WHERE .= "\n AND f.status>0";
					$ORDER = "\n ORDER BY f.datetime DESC";
					$LIMIT = "\n LIMIT 5";
					
					$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
					$countTakipedilen = intval ( $db->loadResult () );
					?>
		                	<div id="vekilContent-<?=$row->deputyID?>">
				<div class="usrlist-pic">
					<a href="/profile/<?=$row->profileID?>" >
						<img src="<?=$model->getProfileImage($row->image, 67, 67, 'cutout')?>">
					</a>
				</div>
				<div class="usrlist-info">
					<table class="table-striped" style="width: 100%">
						<tbody>
							<tr>
								<th><span><a href="/profile/<?=$row->profileID?>" ><?=$row->name." ".$row->surname?></a></span></th>
								<th><a href="#"><?=$row->di_count?> Ses</a></th>
								<th><a href="#"><?=$row->dilike1_count?> Takdir</a></th>
								<th><a href="#"><?=$row->dilike2_count?> Saygı</a></th>

							</tr>
							<tr>
								<td colspan="5"><p><?=$row->motto?></p></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="usrlist-set">
					<ul>
						<li><button class="btn btn-vekiladay"
								onclick="deputyremove(<?=$row->deputyID?>);">Vekil Adayım</button></li>
						<li><strong><?=$countTakipedilen?></strong> Takip Ettiği</li>
						<li><strong><?=$countTakipci?></strong> Takipçi</li>
					</ul>
				</div>
				<hr class="rounded_hr">
			</div>
		                <?php
				}
			
			}
			?>
	          		</div>
	</div>
	<div class="tab-pane fade in "
		id="tab-arsiv">
		<div class="roundedcontent" id="confirmed_posts">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Meclis
				Arşivi
			</h1>
			<p>Meclis arşivinde kelime, tarih vb. bazlı arama yapabilmeniz için
				artık daha çok çalışıyoruz.</p>
			          		<?php
			$archive = $this->getarchive();
			echo $archive ['html'];
			$model->addScript ( 'archivestart = ' . $archive ['nextstart'] . ';' );
			?>
			          		
		              </div>
		<p></p>
		<button id="archivemoreNew" class="btn100 tabbtn">DAHA FAZLA TEKLİF
			YÜKLE</button>
		<p></p>
	</div>
	<div class="tab-pane fade in <?=$currentClass["tasari"]?>"
		id="tab-tasari">
	          		<?php
			if ($model->profile->deputy <= 0) {
				echo '<div class="roundedcontent">';
				
				echo '<h3>Gündem teklifleri vekillere özeldir.</h3>';
				echo '<p>
						<ul>
						<li>
							Mecliste günlük yayınlanan ülke meclisi gündemlerini vekiller oluşturup ilk 7 tanesini oylayarak seçmektedir.
						</li>
						<li>
							Vekil Seçimi tabını kullanarak önümüzdeki hafta mecliste vekil olarak görmek istediğin 10 arkadaşını "Vekil Olsun"u işaretleyip aday gösterebilirsin.
						</li>
						<li>
							Her Cuma saat 24\'e kadar vekil adaylarını güncelleyebilirsin.
						</li>
						<li>
							En fazla oy alan 50 kişi haftanın vekilleri olarak meclise seçilirler. Vekiller tabından haftanın 50 vekilini görebilirsin.
						</li>
						<li>
							Vekillerin paylaşımlarının Vekil Sesleri akışında bütün kullanıcılara göründüğünü unutma. 
						</li>
						</ul>
						';
				echo '</div>';
			
			} else {
				?>
	          		          	<script> 
					function yorumGenis(id)
					{
						$('#'+id).animate({
							    height: '70',
							    width: '500'
							  }, 500, function() {
							    // Animation complete.
								 $("#karakterGrubu").fadeIn(700);
						});
					}
					function yorumDar (id)
					{
						
						if($("#"+id).val()=="")
						{ 
							$("#karakterGrubu").fadeOut(250);
							$('#'+id).animate({
							    height: '15',
							    width: '400'
							  }, 500, function() {
							    // Animation complete.
								 
							});
						}
					}
				</script>
					<?php
				if (date ( "H" ) > 22 && date ( "i" ) > 00) {
					
					?>
                       <div class="roundedcontent shareidea">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Tasarı
				girişi bugün için kapanmıştır, lütfen oylarınızı veriniz.
			</h1>

			<p>Gündem tasarılarınızı her gün 23:00'a kadar sunabilirsiniz, oylama
				ise gece yarısına kadar devam edecektir.</p>
			<br />
		</div>
					<?php
				
} else {
					$SELECT = "SELECT count(*)";
					$FROM = "\n FROM proposal AS pp";
					$WHERE = "\n WHERE pp.datetime>" . $db->quote ( date ( 'Y-m-d H:i:s', LASTPROPOSAL ) );
					$WHERE .= "\n AND pp.status>0";
					$WHERE .= "\n AND pp.st=1";
					$WHERE .= "\n AND pp.deputyID='" . $model->profileID . "'";
					$db->setQuery ( $SELECT . $FROM . $WHERE );
					
					$kac = $db->loadResult ();
					if ($kac > 2) {
						?>
	                         <div class="roundedcontent shareidea">
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Tasarı
				girişi sizin için kapanmıştır.
			</h1>
			<div class="clear"></div>
			<p>Bir gün içerisinde sadece 3 tane tasarı yazabilirsiniz yeni tasarı
				yazabilmek için lütfen mevcut tasarılarınızdan birini kaldırınız.</p>
			<br />
		</div>
						<?php
					
} else {
						?>
	          			<div class="roundedcontent shareidea">
			<div class="textarea">
				<textarea rows="3" id="pptext" placeholder="Tasarı Yaz"
					class="input-xlarge numberSay" onblur="yorumDar('pptext')"
					onfocus="yorumGenis('pptext')"
					style="resize: none; height: 15px; width: 400px; overflow-x: hidden; overflow-y: hidden;"
					maxlength="200"></textarea>
				<ul style="float: right; list-style: none; margin: 0;">
					<li style="float: left; display: none;" id="karakterGrubu"><span
						style="color: #9B9B9B; font-size: 10pt; margin-right: 10px; margin-top: 5px;"><span
							id="pptextNumber" style="float: none; font-size: 10pt;">200</span>
							Karakter</span></li>
					<li style="float: left;"><button class="btn btn-gonder" id="ppsend">Paylaş</button></li>
				</ul>
			</div>
			<div id="mentionDisplay"
				style="position: absolute; z-index: 999; left: 224px; top: 127px; width: 501px;"></div>

		</div>
						
					<?php
					} // 3 tane sınırlama else sonu
				} // saat Sınırlaması else sonu				?>
						<div style="clear: both;"></div>
		<p></p>
		<style>
.ppvoteBtn span {
	cursor: pointer;
	color: #962B2B;
}

.ppvoteBtn span:hover {
	color: #D83A3A;
}
</style>
		<div class="roundedcontent" id="confirmed_posts">
			<!-- Tasarılar Başlangıcı  -->
			<h1>
				<img src="<?=TEMPLATEURL?>beta/img/democratus_icon.png"> Oylanan Tüm
				Tasarılar
			</h1>
			          		<?php
				$SELECT = "SELECT pp.*, pr.name, pr.surname, pr.image";
				$SELECT .= "\n , sum(ppv.approve) AS approvecount";
				$SELECT .= "\n , sum(ppv.reject) AS rejectcount";
				$SELECT .= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *  sum(ppv.approve) ) AS points";
				// $SELECT.= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *
				// sum(ppv.approve) ) / (sum(ppv.complaint)+1) AS points" ;
				// (( (onay sayısı-red) x onay )) / (şikayet sayısı +1)
				$FROM = "\n FROM proposal AS pp";
				$JOIN = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
				$JOIN .= "\n LEFT JOIN proposalvote AS ppv ON ppv.proposalID = pp.ID";
				$WHERE = "\n WHERE pp.datetime>" . $db->quote ( date ( 'Y-m-d H:i:s', LASTPROPOSAL ) );
				$WHERE .= "\n AND pp.status>0";
				$WHERE .= "\n AND pp.used<1";
				$WHERE .= "\n AND pp.st=1";
				// $GROUP = "\n GROUP BY ppv.proposalID";
				$GROUP = "\n GROUP BY pp.ID";
				$ORDER = "\n ORDER BY points DESC, approvecount DESC, pp.ID ASC";
				// $ORDER = "\n ORDER BY pp.ID ASC";
				$LIMIT = "\n LIMIT " . config::$mydeputylimit;
				$LIMIT = "\n ";
				$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT );
				// if($model->profileID==1001) die($db->_sql);
				
				$rows = $db->loadObjectList ();
				
				if (count ( $rows )) {
					$i = 0;
					foreach ( $rows as $row ) {
						$i ++;
						?>
				                    <div class="roundedcontent">
				<div class="usrlist-other-pic">
					<img
						src="<?=$model->getProfileImage($row->image, 67, 67, 'cutout')?>">
				</div>
				<div class="usrlist-infowide">
					<table class="table-striped" style="width: 100%">
												<?php
						// if($row->deputyID != $model->profileID){
						$result = proposal::getbuttons ( $row->ID );
						if($row->mecliseAlan>0)
						{
							$profileClass=new profile;
							
							$viaTxt='<a href="/profile/'.$row->mecliseAlan.'">'.$profileClass->get_name($row->mecliseAlan).'</a> üzerinden ';
						}
						else
						{
							$viaTxt="";		
						}
						?>  
						<tbody>
							<tr>
								<th><span><?=$i?> - <?=$viaTxt?><a href="/profile/<?=$row->deputyID?>"><?=$row->name." ".$row->surname?></a></span></th>
								<th style="text-align: right;" class="ppvoteBtn"><span
									id="ppbuttons<?=$row->ID?>">
														<?=$result['html'];?> 
													</span>
                                                	<?php 
                                                		if($row->deputyID==$model->profileID || $row->mecliseAlan==$model->profileID)
                                                			echo "<span onclick='tasariKaldir(".$row->ID.");' > X </span>";
                                                	?>
													</th>
								<!-- 
													<th><a href="javascript:;">1 ay, 1 hafta önce</a></th>
													<th><a href="/di/16533"><i class="icon-comment"></i> Söyleş</a></th>
													<th><a href="#"><i class="icon-share"></i> Paylaş</a></th>
													 -->
							</tr>
							<tr>
								<td colspan="4">
									<p><?=$row->spot?></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<p></p>
				                    <?php
					}
				}
				?>
		              </div>
		<!-- Tasarılar sonu  -->
		         		<?php }?>
	          		</div>
	<!-- tab-tasari Sonu -->
</div>
<?php
		} else { // old design start
			
			$model->addScript ( PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1 );
			$model->addScript ( PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
			$model->addStyle ( PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
			$model->addScript ( PLUGINURL . 'lib/common.js', 'common.js', 1 );
			
			?>
		{{agenda}}

<!-- Confirmed Posts [Begin] -->
<div class="box" id="confirmed_posts" style="position: relative">
	<div class="parliament_sub_title">Oylanmış Gündemler</div>

	<div class="line_center"></div>
	<div id="confirmed_posts_slider"></div>

	<div class="clear"></div>


<?php
			$archive = $this->getarchive ();
			// if($archive['count']>0)
			echo $archive ['html'];
			$model->addScript ( 'archivestart = ' . $archive ['nextstart'] . ';' );
			?>                     
                        </div>
<span id="archivemore" rel="0" class="more">&nbsp;</span>

<!-- Confirmed Posts [End] -->

<?php
		} // old design end
	} // main end
	
	public function friendList($word = "", $page = 1) { // friend List Start
		global $model, $db;
		$Start = ($page - 1) * 10;
		$SELECT = "SELECT f.followerID, p.*";
		$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID AND md.datetime >= " . $db->quote( date('Y-m-d H:i:s', LASTELECTION) )." ) AS mydeputy";
		$SELECT .= ", ( SELECT COUNT(*) FROM di WHERE di.profileID=p.ID AND di.status>0) AS di_count";
		$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike1>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike1_count";
		$SELECT .= ", ( SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID=p.ID AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>p.ID AND di.status>0) AS dilike2_count";
		$FROM = "\n FROM #__follow AS f";
		$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followingID";
		$WHERE = "\n WHERE f.followerID=" . $db->quote ( $model->profileID );
		$WHERE .= "\n AND f.status>0";
		if ($word != "")
			$WHERE .= "\n AND p.name like '%" . $db->escape ( $word ) . "%' ";
		
		$ORDER = "\n "; // "ORDER BY s.datetime DESC";
		$LIMIT = "\n LIMIT $Start , 10";
		
		$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT );
		$rows = $db->loadObjectList ();
		
		$SELECT = "SELECT count(p.ID)";
		$FROM = "\n FROM #__follow AS f";
		$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followingID";
		$WHERE = "\n WHERE f.followerID=" . $db->quote ( $model->profileID );
		$WHERE .= "\n AND f.status>0";
		
		$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE);
		
		$response ["count"] = $db->loadResult();
		$response ["page"] = $page;
		
		if (count ( $rows )) {
			$html = '';
			$htmls = '';
			$nawNum="";
			$i = 0;
			
			foreach ( $rows as $row ) {
				$SELECT = "SELECT DISTINCT f.followingID, p.*";
				$FROM = "\n FROM #__follow AS f";
				$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followerID";
				$WHERE = "\n WHERE f.followingID=" . $db->quote ( $row->ID );
				$WHERE .= "\n AND f.status>0";
				$ORDER = "\n ORDER BY f.datetime DESC";
				$LIMIT = "\n LIMIT 5";
				
				$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
				$countTakipci = intval ( $db->loadResult () );
				
				$SELECT = "SELECT f.followerID, p.*";
				$SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
				
				$FROM = "\n FROM #__follow AS f";
				$JOIN = "\n JOIN #__profile AS p ON p.ID = f.followingID";
				$WHERE = "\n WHERE f.followerID=" . $db->quote ( $row->ID );
				$WHERE .= "\n AND f.status>0";
				$ORDER = "\n ORDER BY f.datetime DESC";
				$LIMIT = "\n LIMIT 5";
				
				$db->setQuery ( 'SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
				$countTakipedilen = intval ( $db->loadResult () );
				if ($row->mydeputy > 0) {
					$vekilolsun = "hide";
					$vekilAdayim = "";
				} else {
					$vekilolsun = "";
					$vekilAdayim = "hide";
				}
				$buttons = '
        			<button id="NotDeputy-' . $row->ID . '" class="btn btn-vekil ' . $vekilolsun . '" onclick="deputyadd(' . $row->ID . ');">Vekil Olsun</button>
        			<button id="deputy-' . $row->ID . '" class="btn btn-vekiladay ' . $vekilAdayim . '" onclick="deputyremove(' . $row->ID . ');">Vekil Adayım</button>';
				$htmls .= '
        	        	<div id="arkadasContent-' . $row->ID . '">
        			    	<div class="usrlist-pic">
        			    		<a href="/profile/'.$row->ID.'" >
        			    			<img src="' . $model->getProfileImage ( $row->image, 67, 67, 'cutout' ) . '">
        			    		</a>
        			    		</div>
        					<div class="usrlist-info">
        						<table class="table-striped" style="width: 100%;">
        							<tbody><tr>
        								<th style="width: 150px;"><span><a href="/profile/'.$row->ID.'" >' . $row->name . " " . $row->surname . '</a></span></th>
        								<th><a href="javascript:;">' . $row->di_count . ' Ses</a></th>
        								<th><a href="javascript:;">' . $row->dilike1_count . ' Takdir</a></th>
        								<th><a href="javascript:;">' . $row->dilike2_count . ' Saygı</a></th>
        							</tr>
        							<tr>
        								<td colspan="5"><p>' . $row->motto . '</p></td>
        							</tr>
        							</tbody>
        						</table>
        					</div>
        					<div class="usrlist-set">
        						<ul>
        							<li>' . $buttons . '</li>
        							<li><strong>' . $countTakipedilen . '</strong> Takip Ettiği</li>
        							<li><strong>' . $countTakipci . '</strong> Takipçi</li>
        						</ul>
        					</div>
        					<hr class="rounded_hr">
        				</div>';
			
			} // foreach($rows as $row)
			$topPage = ceil( $response["count"] / 10 );
			$page = $response["page"];
			if ($page > 1) {
				$nawNum.= "<li><a href='javascript:;' class='pageChange' rel='pre' >&laquo;</a></li>";
			} else {
				$nawNum.= " <li><a href='javascript:;' class='pageChange' rel='0' >&laquo;</a></li> ";
			}
			if ($topPage <= 8) {
				$i = 1;
				while ( $i <= $topPage ) {
					$actv="";
					if($i==$page)
						$actv='class="active"';
					$nawNum.= "<li $actv > <a class='pageChange' href='javascript:;' rel='".$i."' >" . $i . "</a> </li>";
					$i = $i + 1;
				}
				} elseif ($page >= ($topPage - 4)) {
				$i = 1;
				while ( $i <= 2 ) {
				$actv="";
				if($i==$page)
					$actv='class="active"';
					$nawNum.= "<li $actv> <a class='pageChange' href='javascript:;' rel='".$i."'>" . $i . "</a> </li>";
					$i = $i + 1;
				}
				$nawNum.= " <li><a href='javascript:;'>...</a></li>";
				$i = $page - 2;
				while ( $i <= $topPage ) {
				$actv="";
				if($i==$page)
					$actv='class="active"';
					$nawNum.= "<li $actv><a class='pageChange' href='javascript:;' rel='".$i."'>" . $i . "</a> </li>";
					$i = $i + 1;
				}
				} elseif ($page > 5 && $page < ($topPage - 4)) {
				$i = 1;
				while ( $i <= 2 ) {
				$actv="";
				if($i==$page)
					$actv='class="active"';
					$nawNum.= "<li $actv ><a class='pageChange' href='javascript:;' rel='".$i."'>" . $i . "</a> </li>";
					$i = $i + 1;
				}
				$nawNum.= " <li><a href='' >...</a></li> ";
			
				$i = $page - 2;
				while ( $i <= ($page + 2) ) {
				$actv="";
				if($i==$page)
				$actv='class="active"';
				$nawNum.= "<li $actv ><a class='pageChange' href='javascript:;' rel='".$i."'>" . $i . "</a> </li>";
				$i = $i + 1;
				}
				$nawNum.= " <li ><a href='' >...</a></li> ";
			
				$i = $topPage - 1;
				while ( $i <= $topPage ) {
						$actv="";
				if($i==$page)
					$actv='class="active"';
					$nawNum.= "<li $actv ><a href='javascript:;' class='pageChange' rel='".$i."'>" . $i . "</a> </li>";
				$i = $i + 1;
				}
				} else {
				$i = 1;
				while ( $i <= ($page + 2) ) {
				$actv="";
				if($i==$page)
				$actv='class="active"';
				$nawNum.= "<li $actv ><a class='pageChange' href='javascript:;' rel='".$i."'>" . $i . "</a> </li>";
				$i = $i + 1;
				}
					$nawNum.= " <li><a href='' >...</a></li> ";
			
					$i = $topPage - 1;
					while ( $i <= $topPage ) {
					$actv="";
					if($i==$page)
					$actv='class="active"';
					$nawNum.= "<li $actv ><a href='javascript:;' class='pageChange' rel='".$i."'>" . $i . "</a> </li>";
					$i = $i + 1;
				}
				}
					
				if ($page < $topPage) {
				$nawNum.= "<li><a href='javascript:;' class='pageChange' rel='next' >&raquo;</a></li>";
				} else {
				$nawNum.= " <li><a href='javascript:;' class='pageChange' rel='0' >&raquo;<li>";
				}
				$response ["html"] = $htmls;
				$response ["navNumHtml"]=$nawNum;
		} // count($rows) END
		else {
			$response ["html"] = "";
			$response ["navNumHtml"]=0;
		}
		return $response;
	} // friend List END
	public function more() {
		global $model, $db;
		$model->mode = 0;
		$start = filter_input ( INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT );
		
		echo json_encode ( $this->getarchive ( $start ) );
	
	}
	
	public function getarchive($start = 0) {
		global $model, $db;
		
		$response = array ();
		try {
			$ocolors = array (
					1 => '#88b131',
					2 => 'progress-success',
					3 => 'progress-warning',
					4 => 'progress-danger',
					5 => '#ff6f32' 
			);
			$ocolorsBar = array (
					1 => '#88b131',
					2 => 'bar-success',
					3 => 'bar-warning',
					4 => 'bar-danger',
					5 => '#ff6f32' 
			);
			
			$SELECT = "\n SELECT a.*, av.vote AS myvote, p.image AS deputyimage, p.name AS deputyname, p.surname AS deputysurname";
			$FROM = "\n FROM agenda AS a";
			$JOIN = "\n LEFT JOIN agendavote AS av ON av.agendaID=a.ID AND av.profileID= " . $db->quote ( $model->profileID );
			$JOIN .= "\n LEFT JOIN profile AS p ON p.ID=a.deputyID";
			$WHERE = "\n WHERE " . $db->quote ( date ( 'Y-m-d H:i:s' ) ) . " > a.endtime";
			$WHERE .= "\n AND a.status>0";
			if ($start > 0)
				$WHERE .= "\n AND a.ID<" . intval ( $start );
			$GROUP = "\n ";
			$ORDER = "\n ORDER BY a.ID desc";
			$LIMIT = "\n LIMIT 7";
			
			$db->setQuery ( $SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT );
			$agendas = $db->loadObjectList ();
			
			$response ['count'] = count ( $agendas );
			
			if (count ( $agendas )) {
				$i = 0;
				ob_start ();
				foreach ( $agendas as $agenda ) {
					
					$db->setQuery ( 'SELECT av.vote, COUNT(*) AS votecount FROM agendavote AS av WHERE av.agendaID=' . $db->quote ( $agenda->ID ) . ' GROUP BY av.vote ORDER BY av.vote' );
					$voted = $db->loadObjectList ( 'vote' );
					$totalvote = 0;
					$win = 0;
					$winoption = '';
					if (count ( $voted ))
						foreach ( $voted as $v ) {
							$totalvote += $v->votecount;
							if ($v->votecount > $win) {
								$win = $v->votecount;
								$winoption = $v->vote;
							}
						}
					if($totalvote>0)
						$winpercent = round ( ($win * 100) / $totalvote );
					else
						$winpercent = "0";
						
						
					$optioans = array (
							1 => 'Kesinlikle Katılıyorum',
							2 => 'Katılıyorum',
							3 => 'Kararsızım',
							4 => 'Katılmıyorum',
							5 => 'Kesinlikle Katılmıyorum' 
					);
					
					$statistic_left = '';
					$statistic_right = '';
					$statistic_Line = '';
					$tekLine='<div class="progress">';
					foreach ( config::$votetypes as $key => $option ) {
						
						// oy oranini hesapla
						if (array_key_exists ( $key, $voted ))
							$percent = round ( ($voted [$key]->votecount * 100) / $totalvote );
						else
							$percent = 0;
						
						$checked = $agenda->myvote == $key ? '<i title="senin seçimin">*</i>' : '';
						
						$tekLine.='<div class="bar '.$ocolorsBar [$key].'" style="width: '.$percent.'%;">'.$percent.'</div>';
						  
						
						$statistic_left .= '<div class="choose">' . $option . ' ' . $checked . '<span>%' . $percent . '</span></div>';
						
						$statistic_right .= '<div class="choose" style="width: ' . $percent . '%; background-color: ' . $ocolors [$key] . '">' . $percent . '%</div>';
						$statistic_Line .= '
							<tr>
								<td class="quest" style="width:70px;">
									<label class="checkbox" for="parliament_choose_' . $agenda->ID . '_' . $key . '">' . $option . '
									</label>
								</td>
								<td style="width:30px;">%' . $percent . '</td>
								<td class="bars"><div style="margin-bottom: 5px;" class="progress  ' . $ocolors [$key] . '"><div style="width: ' . $percent . '%" class="bar"></div></div></td>
							</tr>
						';
					}
					$tekLine.="</div>";
					if ($model->newDesign) {
						$dicomment_count = di::getdicomment_count ( $agenda->diID );
						if ($dicomment_count > 0)
							$dicomment_count = ' (' . $dicomment_count . ') ';
						else
							$dicomment_count = '';
							// new design getarchive start
						?>
<div class="roundedcontent"
	onclick="statisticLineChange(<?=$agenda->ID?>);"
	style="background-color: #FDFBFB">
	<div class="usrlist-other-pic">
		<img
			src="<?=$model->getProfileImage( $agenda->deputyimage, 67, 67, 'cutout' )?>">
	</div>
	<div class="usrlist-infowide">
		<table class="table-striped" style="width: 100%">
			<tbody>
				<tr>
					<th style="width: 150px;"><span><?=$agenda->deputyname." ".$agenda->deputysurname?></span></th>
					<th><a href="javascript:;" style="width: 90px;"><?=time_since(strtotime( $agenda->endtime))?> önce</a></th>
					<th><a href="/di/<?=$agenda->diID?>"><i class="icon-comment"></i> Söyleş <?=$dicomment_count?></a></th>
					<th><a href="javascript:redi(<?=$agenda->diID?>)"><i
							class="icon-share"></i> Paylaş</a></th>
				</tr>
				<tr>
					<td colspan="4">
						<p><?=$agenda->title?></p>
						<br />
						<p>
							<span>Sonuç: %<?php echo $winpercent?>  <strong><?php echo @config::$votetypesss[$winoption]; ?></strong></span>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="statisticLine-<?=$agenda->ID?>" class="statisticLine-all"
		style="display: none;">
		<hr class="rounded_hr">
		<div style="clear: both;"></div>
		<table class="polltable" style="margin-left: 20px; width: 95%;">
			
								<?=$tekLine?>
							
							</table>
	</div>
</div>
<p></p>
<?php
					} 					// new design getarchive end

				
				}
				
				$response ['html'] = '<div id="archive' . $start . '">' . ob_get_contents () . '</div>';
				ob_end_clean ();
				$response ['nextstart'] = $agenda->ID;
			
			} else {
				// not found
				$response ['html'] = '';
				$response ['count'] = 0;
			
			}
		
		} catch ( Exception $e ) {
			// hata oldu
			$response ['html'] = '';
			$response ['count'] = 0;
		}
		
		return $response;
	
	}

}
?>
