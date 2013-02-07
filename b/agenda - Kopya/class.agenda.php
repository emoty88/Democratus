<?php
    class agenda_block extends control{
        
        public function block(){
            global $model, $db, $l;
            
            $classes = array('world'=>1, 'region'=>2, 'country'=>3, 'city'=>4, 'foryou'=>10);
            $model->addScript(PLUGINURL . 'lib/combined.js', 'combined.js', 1 );
            $model->addScript(BLOCKURL . 'agenda/agenda.js', null, 1 );
            
?>

            
      <!--status-agenda -->
      <div class="status-agenda">
        <h4><a href="#">“Akaryakıt zamlarının gerekçelerini makul buluyor musunuz?” anketinin sonuçları açıklandı. </a></h4>
      </div>
      <!--status-agenda END-->
<?php
        $agenda=null;
        $db->setQuery('SELECT a.* FROM agenda AS a WHERE '.$db->quote(date('Y-m-d H:i:s')).' BETWEEN a.starttime AND a.endtime AND class='.$db->quote($classes['country']).' ORDER BY ID desc LIMIT 1');

    
        if($db->loadObject($agenda)){
            
        } else {
            
        }
        
        
        $img = $model->getImage($agenda->imagepath, 500, 200, 'cutout');
        $response['ID'] = $agenda->ID;
        $response['image'] = $img;
        $response['title'] = $agenda->title;
        $response['dateinfo'] = asdatetime( $agenda->starttime,'d F Y').' günü oylamaya açıldı';
        $response['isvotable'] = 1;
        $response['agendagolink'] = '/agenda/'.$agenda->ID;
        $response['agendagotitle'] = 'Meclise Gir';
        $response['agendatimeleft'] = 'Gündemde oy kullanmak için <strong>'.time_left(strtotime($agenda->endtime)).'</strong> kaldı.';
        $response['agendalastcomment'] = '';
        $response['agendalastcomments'] = '';
        
        $db->setQuery('SELECT COUNT(*) FROM agendacomment AS ac WHERE ac.agendaID='.$db->quote($agenda->ID));
                $totalcomments = intval( $db->loadResult() );                
                
                if($totalcomments > 0){
                    $SELECT = "SELECT ac.*, p.image, p.name";
                    $FROM   = "\n FROM agendacomment AS ac";
                    $JOIN   = "\n JOIN profile AS p ON p.ID = ac.profileID";
                    $WHERE  = "\n WHERE ac.agendaID = " . $db->quote($agenda->ID);
                    $ORDER  = "\n ORDER BY ac.ID DESC";
                    $LIMIT  = "\n LIMIT 4";

                    $db->setQuery($SELECT.$FROM.$JOIN.$WHERE.$ORDER.$LIMIT);
                    
                    $comments = $db->loadObjectList();
                    if(count($comments)){
                        $i=0;
                        foreach($comments as $comment){
                            $i++;
                            if($i==1){
                                $response['agendalastcomment'] = '<img height="32" align="middle" width="32" class="profileimage" alt="" src="'.$model->getProfileImage($comment->image, 32,32,'cutout').'"> <span><strong>'.time_since(strtotime($comment->datetime)).' önce '.$comment->name.':</strong> '.$comment->comment. '» <a href="/agenda/'.$agenda->ID.'"> Devamı</a></span>';
                            } else {
                                $response['agendalastcomments'] .= '<strong>'.$comment->name.'</strong> ';
                            }
                        }
                        $response['agendalastcomments'] .= ' ve '.$totalcomments.' yorumu okumak için tıklayın';
                        
                    }
                    
                    
                    
                }
        
?>      
      
       <!--agendablock START -->
      <div id="agendablock">
        <ul id="agendatabs">
          <li rel="city" <?php if($agenda->class==$classes['city']) echo 'class="active"';?>>İstanbul</li>
          <li rel="country" <?php if($agenda->class==$classes['country']) echo 'class="active"';?>>Türkiye</li>
          <li rel="region" <?php if($agenda->class==$classes['region']) echo 'class="active"';?>>Bölge</li>
          <li rel="world" <?php if($agenda->class==$classes['world']) echo 'class="active"';?>>Dünya</li>
          <li rel="foryou" <?php if($agenda->class==$classes['foryou']) echo 'class="active"';?>>Sizce</li>
        </ul>
        
        <div id="agenda">
          <div id="agendaimage"> <img src="<?=$model->getImage($agenda->imagepath, 500, 120, 'cutout')?>" width="500" height="120" alt="" /> </div>
          <div id="agendainfo"><?=$response['dateinfo'];?></div>
          <div id="agendatitle"><?=$response['title']?></div>
          <div id="agendago"><a href="/agenda/<?=$agenda->ID?>"> Meclise Gir </a></div>
          <div id="agendalastcomment"><?=$response['agendalastcomment']?></div>
          <div id="agendalastcomments"><?=$response['agendalastcomments']?></div>
          <div id="agendatimeleft"><?=$response['agendatimeleft']?></div>
        </div>
      </div>
      <!--agendablock END-->
<?php
        }
    }
?>