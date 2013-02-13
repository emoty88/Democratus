<?php
    class smsuggestion_block extends control{
        
        public function block(){
        	global $model;
			if($model->checkLogin())
			{
				$model->addScript("
				jQuery(document).ready(function ($) {
					get_who2follow();
				});");
				 
			?>
				<!-- Bileşen -->
				<section class="bilesen beyaz padding_yok" id="whotofollow">
					<header>
						<h1>Arkadaşlarını Davet Et</h1>
					</header>
					<div class="bilesen_icerigi">
                                            <address style="padding: 10px;">Arkadaşlarını da fikirleriyle gündemi şekillendirmeye <a href="/my#arkadasB" >davet et.</a></address>
					</div>
					
				</section>
			<?
			}
		}
        public function block_old(){
            global $model, $db, $l; //return;
            
            $model->cacheble = true;
            $model->cachettl = 5 * 60;
            
            
            //if($model->profileID!=1001) return;
            
            if($model->userID<1) return;
            
            $db->setQuery("SELECT MAX(ID) FROM profile");
            $minid = 1000;
            $maxid = intval($db->loadResult());
            
            
            //seni takip ettiği halde senin etmediklerin
            $SELECT = "SELECT p.*";
            $FROM   = "\n FROM profile AS p, follow AS f";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE f.followingID=".$db->quote($model->profileID);
            $WHERE .= "\n AND p.ID=f.followerID";
            $WHERE .= "\n AND f.status>0";
            $WHERE .= "\n AND f.followerID NOT IN (SELECT f2.followingID FROM follow AS f2 WHERE f2.followerID=".$db->quote($model->profileID)." AND f2.status>0)";
            //$WHERE .= "\n AND p.ID<>".$db->quote($model->profileID);
            $ORDER  = "\n ORDER BY f.datetime DESC";
            $LIMIT  = "\n LIMIT 2";
            
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList('ID');
            //$rows = array();
            //print_r($rows);
            
            $count = intval(count($rows));
            
            for($i=0;$i<5-$count;$i++){
                $SELECT = "SELECT p.*";
                $FROM   = "\n FROM profile AS p";
                $JOIN   = "\n ";
                $WHERE  = "\n WHERE p.ID>" . rand($minid, $maxid);
                $WHERE .= "\n AND p.ID NOT IN (SELECT f2.followingID FROM follow AS f2 WHERE f2.followerID=".$db->quote($model->profileID)." AND f2.status>0)";
                $WHERE .= "\n AND p.status>0";
                //$WHERE .= "\n AND p.ID<>".$db->quote($model->profileID);
                $ORDER  = "\n ORDER BY p.ID";
                $LIMIT  = "\n LIMIT 1";    
                
                $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
                
                $row = null;
                
                if($db->loadObject($row))
                    $rows[$row->ID] = $row;                
                
            }
            
            
            
            //$rows = shuffle_assoc($rows);
            //$rows = 
            shuffle($rows);
            
            //print_r($rows);
            
            /*
            $SELECT = "SELECT p.*";
            $FROM   = "\n FROM profile AS p";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE p.ID NOT IN (SELECT f2.followingID FROM follow AS f2 WHERE f2.followerID=".$db->quote($model->profileID)." AND f2.status>0)";
            $WHERE .= "\n AND p.status>0";
            
            $ORDER  = "\n ORDER BY RAND()";
            $LIMIT  = "\n LIMIT 5";
            
            $RANDOM = $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;
            */
            
            
            /**/
            
            
            //$YOURFOLLOWERS = $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;            
            
            /*
            
            //seni takip ettiği halde senin etmediklerin
            $SELECT = "SELECT p.*";
            $FROM   = "\n FROM profile AS p, follow AS f";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE f.followerID=p.ID";
            //$WHERE .= "\n AND p.ID=f.followerID";
            $WHERE .= "\n AND f.status>0";
            $WHERE .= "\n AND f.followingID IN (SELECT f2.followingID FROM follow AS f2 WHERE f2.followerID=".$db->quote($model->profileID)." AND f2.status>0)";
            $WHERE .= "\n AND f.followerID NOT IN (SELECT f3.followingID FROM follow AS f3 WHERE f3.followerID=".$db->quote($model->profileID)." AND f3.status>0)";
            //$ORDER  = "\n ORDER BY f.datetime DESC";
            $ORDER  = "\n ORDER BY RAND()";
            $LIMIT  = "\n LIMIT 5";
            
            $YOURFOLLOWERS = $SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT;
            
         
            
            
            /*
            $SELECT = "SELECT DISTINCT f.followingID, p.*";
            $FROM   = "\n FROM follow AS f";
            $JOIN   = "\n JOIN profile AS p ON p.ID = f.followerID";
            $WHERE  = "\n WHERE f.followingID=".$db->quote($myID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ORDER BY f.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            */
            //$db->setQuery('SELECT COUNT(*)' . $FROM . $JOIN . $WHERE );
            //$count = intval( $db->loadResult() );
            
            //$db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            //$rows = $db->loadObjectList();
            //die($db->_sql);
            if(count($rows)){
?>
                        <!-- Followers [Begin] -->
                        <div class="box" id="followers">
                            <span class="title" style="float: left">Kimleri takip etsem?</span>
                            <span class="all_users">&nbsp;</span>
                            <div class="clear"></div>
                            <div class="line"></div>

                            <ul class="users_small">
<?php
        foreach($rows as $row){
?>
                                <li>
                                    <a href="/profile/<?=$row->ID?>"><img src="<?=$model->getProfileImage($row->image, 40, 40, 'cutout')?>" alt="" />
                                    <span><?=$model->shortname( $row->name )?></span></a>
                                </li>                  
<?php
                }
?>                    
                            </ul>
                        </div>
                        <!-- Followers [End] -->
<?php

            } else {
                //echo '<p>not found</p>';
            }
            
            
            
        }
    }
?>