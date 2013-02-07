<?php
    class navBarNew_block extends control{
        
        public function block(){
        	global $model, $db, $l; 
			?>
			<div class="navbar-inner">
			      <div class="container">
			        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			          <span class="icon-bar"></span>
			          <span class="icon-bar"></span>
			          <span class="icon-bar"></span>
			        </a>
			        <a class="brand" href="/"><img src="<?=TEMPLATEURL?>beta/img/logo.png" alt="Democratus"></a>
			        <div class="nav-collapse">
			          <!-- 
			          <ul class="nav">
			            <li><span class="motto">Dünyayı fikirlerinle şekillendir!</span></li>
			          </ul>
			           -->
			        <?php
			        if($model->profileID>0)
			        {
			        ?>
			    	<?php
			    		/*
					    $notice_count = intval( $this->get_notice_count($model->profileID) );
					    if($notice_count>0){
					        $notice_count = $notice_count;
					    } else {
					        $notice_count = '';
					    }
						 * 
						 */
						 $notice_count="";
					?> 
						<script>
							function gotoSearch()
							{
								var word=$('#search').val();
								
								if(word.search("#")>=0)
								{
									word=word.replace("#","");
									location.href='/search/'+word+"#sesler"; 
								}
								else
								location.href='/search/'+word+"#kisiler"; 
								
							}
						</script>
			          <ul class="nav pull-right">
			           <li> 
			           <?php 
			           $arama="";
			           if($model->paths[0]=="search")
			           	$arama=$model->paths[1];
			           ?>
			           	<form class="navbar-search pull-left" action="" onsubmit="gotoSearch(); return false;">
			            	<input type="text" x-webkit-speech="x-webkit-speech" id="search"class="search-query span2" placeholder="Arama" value="<?=$arama?>">
							<i class="icon-search" onclick="gotoSearch();" style="cursor:pointer;"></i> 
			          	</form>
			          </li>
			            <li class="divider-vertical"></li>
			            <li>
			            	<a href="javascript:;" id="noticeIcon">
			             		<i class="icon-check" >
			             		<p id="noticeCountC" style="color:#962B2B; margin: 10px 0 0px 13px;font-weight:bold;">
			             			<?=$notice_count?>
			             		</p>	
			             		</i>
			             	</a>
			             	
			           	</li>
			            <li class="divider-vertical"></li>
			            <li>
			            	<a id="messageIcon" href="javascript:;" data-original-title="Mesajlar">
			            		<i class="icon-envelope"></i>
			            		<span class="bildirim_sayisi">4</span>
			            	</a>
			            </li>
			            <li class="divider-vertical"></li>
			            <li class="dropdown">
			              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			              	<img class="profilsmall" src="<?=$model->getProfileImage($model->profile->image, 40,40, 'cutout')?>" /> 
			              	<span class="profilname"><?=$model->profile->name." ".$model->profile->surname?></span>
			              	<b class="caretdd"></b>
			              </a>
			              <ul class="dropdown-menu">
			                <li><a href="/archive"><i class="icon-democratus"></i>Meclis</a></li>
			                <li class="divider"></li>
			                <li><a href="/profile/<?=$model->profileID?>"><i class="icon-user"></i> Profil</a></li>
			                <li class="divider"></li>
			                <li><a href="/my/profile"><i class="icon-cog"></i> Ayarlar</a></li>
			                <li class="divider"></li>
			                <li><a href="/user/logout"><i class="icon-off"></i>Çıkış</a></li>
			              </ul>
			            </li>
			          </ul>
			          <?php }
			          else 
			          { 
			          ?>
			         <ul class="nav pull-right">
					 	<li>
					 		<button onclick="location.href='/welcome';" class="tabbtn btn-darkgreen margin7">GİRİŞ YAP</button>
							<button onclick="location.href='/welcome/signin';" class="tabbtn btn-success margin7">KAYIT OL</button>
						</li>
					 </ul>
			          <?php 
			          }
			          ?>
			        </div><!-- /.nav-collapse -->
			      </div>
			    </div>
				<div class="langbar">
				<ul>
					<li><a href="#" class="active">TR</a></li>
					<li><span>&nbsp;</span></li>
					<li><a href="#">EN</a></li>
				</ul>
				</div>
				<?php 
        }
		public function get_notice_count($profileID){
            global $model, $db;
            
            $SELECT = "SELECT count(*) FROM (SELECT distinct n.ID3";
            $FROM   = "\n FROM notice AS n";
            $JOIN   = "\n JOIN profile AS p ON p.ID=n.fromID";
            $WHERE  = "\n WHERE n.profileID=".$db->quote(intval( $profileID ));
            $WHERE .= "\n AND n.datetime>".$db->quote( asdatetime( $model->profile->noticetime, 'Y-m-d H:i:s' ));
            $WHERE .= "\n AND n.ID3 UNION ";
            $WHERE .= " SELECT distinct n.ID2 FROM notice AS n JOIN profile AS p ON p.ID=n.fromID "; 
            $WHERE .= " WHERE n.profileID=".$db->quote(intval( $profileID ));
            $WHERE .= " AND n.datetime>".$db->quote( asdatetime( $model->profile->noticetime, 'Y-m-d H:i:s' ));
            $WHERE .= " AND n.ID3 IS NULL ) Say";
            //$WHERE .= "\n AND n.datetime>".$db->quote(  date('Y-m-d H:i:s', time()-60*60*60) );
            //$ORDER  = "\n ORDER BY n.ID DESC";
            $LIMIT  = "\n "; 
            //echo $WHERE;
			//Burayar Açıklamayı yaz

            //echo $SELECT . $FROM . $JOIN . $WHERE  . $LIMIT;
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE  . $LIMIT);
            //die;
            //$db->setQuery('SELECT COUNT(dilike.ID) FROM dilike, di WHERE di.profileID='.$db->quote($profileID).' AND di.ID=dilike.diID AND dilike.dilike2>0 AND dilike.profileID<>'.$db->quote($profileID).' AND di.status>0');
            $result = $db->loadResult();
            if( $result ) 
                return intval( $result );
            else 
                return null;
        }  
    }
?> 
