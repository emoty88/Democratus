<?php
    class proposal_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            
            if($model->userID<1)
                return $model->redirect('/welcome');

            $model->view = 'proposal';
            $model->initTemplate('v2', 'proposal');
            
            $model->title = 'Gündem Teklifleri';
            $model->description = 'Vekillerin hazırladıkları gündem teklifleri';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1);
            $model->addStyle(PLUGINURL . 'lib/jquery-ui/jquery-ui.css', 'jquery-ui.css', 1 );
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            //$model->addScript($model->pluginurl . 'election.js', 'election.js', 1);
            
            
            
            
            
            
            
            if($model->profile->deputy <=0){
                echo '<div class="box">';
                
                echo '<h3>Gündem teklifleri vekillere özeldir.</h3>';
                echo '<p>Katkıda bulunmak için gündemleri oylayabilir, arkadaşlarınıza milletvekili oyu verebilirsiniz. <a href="/election/">Şöyle buyurun.</a></p>';
                
                echo '</div>';
                return;
            }
            //echo $model->profile->deputy;
            
            
            
            
            $model->addScript(' 
            function tasariKaldir(ID)
                {
        			 $.post("/ajax/deleteProposal", { ID: ID }, function(data){ 
			            if(data && data.result=="success"){
			                window.document.location.reload();
			            } else {
			                alert("Tasarı Silinemedi Tekrar Deneyiniz");
			            }
			        },"json");
        		}
            $(document).ready(function(){
               
                $("#time").countdown({
                    date: "'.date('F d, Y H:i', NEXTPROPOSTAL).'",
                    onChange: function( event, timer ){
                        
                    },
                    onComplete: function( event ){
                        $(this).html("Oylama Sona Erdi!");
                    },
                    leadingZero: true
                });                
                
            });');
?>
				<?php 

					if(date("H")>22 && date("i")>00)
					{
					?>
                        <div id="share_idea" class="box">
                            <span class="title_icon" style="width: 290px; float: left">Tasarı girişi bugün için kapanmıştır, lütfen oylarınızı veriniz.</span>
                 			<div class="clear"></div>
                            <p>Gündem tasarılarınızı her gün 23:00'a kadar sunabilirsiniz, oylama ise gece yarısına kadar devam edecektir.</p>
                            <br />
                        </div>
					<?php }
					else
					{
						$SELECT = "SELECT count(*)";
		                $FROM   = "\n FROM proposal AS pp";
		                $WHERE  = "\n WHERE pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTPROPOSAL)) ;
		                $WHERE .= "\n AND pp.status>0";
		                $WHERE .= "\n AND pp.st=1";  
		                $WHERE .= "\n AND pp.deputyID='".$model->profileID."'"; 
		                $db->setQuery($SELECT . $FROM  . $WHERE);            
		                 
            			$kac = $db->loadResult();
            			if($kac>2)
            			{
						?>
	                        <div id="share_idea" class="box">
	                            <span class="title_icon" style="width: 290px; float: left">Tasarı girişi sizin için kapanmıştır.</span>
	                 			<div class="clear"></div>
	                            <p>Bir gün içerisinde sadece 3 tane tasarı yazabilirsiniz yeni tasarı yazabilmek için lütfen mevcut tasarılarınızdan birini kaldırınız.</p>
	                            <br />
	                        </div>
						<?php }
            			else   
            			{
				?>
                        <div id="share_idea" class="box">
                            <span class="title_icon" style="width: 290px; float: left">Tasarı Yaz Gündemi Belirle</span>
                            <span class="character"><span class="number">200</span> Karakter</span>
                            
                            <div class="clear"></div>
                            <p>Vekil olarak tasarılarınızı yazın, diğer tasarıları oylayın ve bir sonraki günün gündemini siz belirleyin. Sistem her gece yarısı eski tasarıları silmektedir.</p>
                            <form method="post" onsubmit="return false;">
                                <div class="textarea"><textarea maxlength="200" id="pptext"></textarea></div>
                                <button id="ppsend">Gönder</button>
                                <div class="clear"></div>
                            </form>
                        </div>
<?php  }
	} ?>
<?php            
            
            if(1||$model->profileID==1001){
                /**/
                $SELECT = "SELECT pp.*, pr.name, pr.image";
                $SELECT.= "\n , sum(ppv.approve) AS approvecount";
                $SELECT.= "\n , sum(ppv.reject) AS rejectcount";
                $SELECT.= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *  sum(ppv.approve) ) AS points" ;
                //$SELECT.= "\n , (( sum(ppv.approve) - sum(ppv.reject)) *  sum(ppv.approve) ) / (sum(ppv.complaint)+1) AS points" ;
                //((  (onay sayısı-red) x onay  ))  /  (şikayet sayısı +1) 
                $FROM   = "\n FROM proposal AS pp";
                $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
                $JOIN  .= "\n LEFT JOIN proposalvote AS ppv ON ppv.proposalID = pp.ID";
                $WHERE  = "\n WHERE pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTPROPOSAL)) ;
                $WHERE .= "\n AND pp.status>0";
                $WHERE .= "\n AND pp.used<1";
                $WHERE .= "\n AND pp.st=1";
                //$GROUP  = "\n GROUP BY ppv.proposalID";
                $GROUP  = "\n GROUP BY pp.ID";
                $ORDER  = "\n ORDER BY points DESC, approvecount DESC, pp.ID ASC";
                //$ORDER  = "\n ORDER BY pp.ID ASC";
                $LIMIT  = "\n LIMIT " . config::$mydeputylimit;
                $LIMIT  = "\n ";            
                /**/
                
                /*
                $SELECT = "SELECT pp.*, pr.name, pr.image";
                $SELECT.= "\n , sum(ppv.approve) AS approvecount";
                $SELECT.= "\n , sum(ppv.reject) AS rejectcount";
                $SELECT.= "\n , ( sum(ppv.approve) - sum(ppv.reject)) * ( (sum(ppv.approve) + sum(ppv.reject)) / " . config::$deputylimit ." ) AS points" ;

                $FROM   = "\n FROM proposal AS pp";
                //$FROM   = "\n FROM proposal AS pp, proposalvote AS ppv, profile AS pr, user AS u";
                $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
                $JOIN  .= "\n LEFT JOIN proposalvote AS ppv ON ppv.proposalID = pp.ID";
                //$JOIN   = "\n ";
                $WHERE  = "\n WHERE pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTPROPOSAL)) ;
                $WHERE .= "\n AND pp.status>0";
                $WHERE .= "\n AND pp.used<1";
                //$WHERE .= "\n AND pr.ID = pp.deputyID";
                //$WHERE .= "\n AND ppv.proposalID = pp.ID";
                $GROUP  = "\n GROUP BY ppv.proposalID";
                $GROUP  = "\n GROUP BY pp.ID";
                $ORDER  = "\n ORDER BY points DESC, approvecount DESC, pp.ID ASC";
                //$ORDER  = "\n ORDER BY pp.ID DESC";
                $LIMIT  = "\n LIMIT " . config::$mydeputylimit;
                $LIMIT  = "\n ";
                */
                
                
                
                
                
                
            } else {
                
                $SELECT = "SELECT pp.*, pr.name, pr.image";
                $SELECT.= "\n , sum(ppv.approve) AS approvecount";
                $SELECT.= "\n , sum(ppv.reject) AS rejectcount";
                $SELECT.= "\n , ( sum(ppv.approve) - sum(ppv.reject)) * ( (sum(ppv.approve) + sum(ppv.reject)) / " . config::$deputylimit ." ) AS points" ;
                $FROM   = "\n FROM proposal AS pp";
                $JOIN   = "\n LEFT JOIN profile AS pr ON pr.ID = pp.deputyID";
                $JOIN  .= "\n LEFT JOIN proposalvote AS ppv ON ppv.proposalID = pp.ID";
                $WHERE  = "\n WHERE pp.datetime>" . $db->quote(date('Y-m-d H:i:s', LASTELECTION)) ;
                $WHERE .= "\n AND pp.status>0";
                $WHERE .= "\n AND pp.used<1";
                $GROUP  = "\n GROUP BY ppv.proposalID";
                $GROUP  = "\n GROUP BY pp.ID";
                $ORDER  = "\n ORDER BY points DESC, approvecount DESC, pp.ID ASC";
                $LIMIT  = "\n LIMIT " . config::$mydeputylimit;
                $LIMIT  = "\n ";
            }
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $GROUP . $ORDER . $LIMIT);
            //if($model->profileID==1001) die($db->_sql);
            
            $rows = $db->loadObjectList();
             
            if(count($rows)){
?>
                        <div class="box" id="vote_agenda">
                            <span class="title_icon">Oylanan Gündemler</span>
                            <div class="line_center"></div>
                            <span id="time" class="time"></span>
<?php
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    
                    
?>

                            
                            <div class="box" style="background-color: #fdfbfb">
                                <div class="idea">
                                    <a href="/profile/<?=$row->deputyID?>"><img src="<?=$model->getProfileImage($row->image, 60, 60, 'cutout')?>" class="image" /></a>
                                    <div class="content" style="width: 320px">
                                        <div class="top">
                                            <span class="name"><?=$i?> - <a href="/profile/<?=$row->deputyID?>"> <?=$row->name?></a><a name="pp<?=$row->ID?>">&nbsp;</a></span>
											<?php
											    //if($row->deputyID != $model->profileID){
											    $result = proposal::getbuttons($row->ID);
											?>                                            
                                            <span class="statistic_tip ppbuttons" id="ppbuttons<?=$row->ID?>">
                                                <?=$result['html'];?> 
                                                <?php if($row->deputyID==$model->profileID)echo "<span onclick='tasariKaldir(".$row->ID.");' > X </span>";?>
                                            </span>
<?php
    //}
?>  

                                            <div class="clear"></div>
                                        </div>
                                    
                                        <div class="line_center"></div>
                                        <div class="bottom">
                                            <p>
                                                <?=$row->spot?>
                                                <span style="display: block; margin: 10px 0 10px 0; font-style: italic"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
<?php                    
                }
?>
                            
                            <div class="clear"></div>
                        </div>
<?php                
            } else echo 'Hiç yok!';
            //<div class="page_more"></div>
 
        }
    }
?>
