<?php
    class election_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            
            if($model->userID<1)
                return $model->redirect('/welcome');

            //$model->view = 'election';
            $model->initTemplate('v2', 'election');
            
            $model->title = 'Vekil seçimleri';
            $model->description = 'Takip ettiklerin arasından vekil oyu ver, gündemi belirlesinler!';
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            
            //$model->addScript($model->pluginurl . 'election.js', 'election.js', 1);
            $model->addScript('$(window).load(function() {
                mCustomScrollbars();
            });
            
            function mCustomScrollbars(){
                $("#choose_users").mCustomScrollbar("horizontal",500,"easeOutCirc",1,"fixed","yes","yes",20);
                $("#choose_friends").mCustomScrollbar("horizontal",500,"easeOutCirc",1,"fixed","yes","yes",20);
                $("#old_deputies").mCustomScrollbar("horizontal",500,"easeOutCirc",1,"fixed","yes","yes",20);
            }

            $.fx.prototype.cur = function() {
                if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
                      return this.elem[ this.prop ];
                }
        
                var r = parseFloat( jQuery.css( this.elem, this.prop ) );
                return typeof r == "undefined" ? 0 : r;
            }

            function LoadNewContent(id,file){
                $("#"+id+" .customScrollBox .content").load(file,function(){
                    mCustomScrollbars();
                });
            }
            

            $(document).ready(function(){
                
                $("#time").countdown({
                    date: "'.date('F d, Y H:i', NEXTELECTION).'",
                    onChange: function( event, timer ){
                        
                    },
                    onComplete: function( event ){
                        $(this).html("Oylama Sona Erdi!");
                    },
                    leadingZero: true
                });                
                
            });');
            
            
            //Yetki var mı?  December 26, 2011 21:02
            
            
            
            //ana taglar
?>
                        <div class="box" id="choose_deputy">
                            <span class="title_icon">Milletvekili Seçimi</span>
                            <div class="line_center"></div>
                            <span id="time" class="time"></span>
                            
                             <div>
    <p>
        Fikirlerini en çok beğendiğin arkadaşlarını vekil olarak öner, haftada bir gün yapılacak sayımda vekil seçilerek gündemi onlar belirlesin.
    </p>
    <p>
        Oylama saati gelene kadar vekil adaylarını değiştirebilirsin.
    </p>
</div>
                            
                            <span class="box_sub_title">Senin vekillerin</span>
                            <div class="line_center"></div>
                            
                            <div class="choose_box">
                                <div class="choose_top"></div>
                                <div class="choose_center">
                                    
                                    <!-- Choose Users [Begin] -->
                                    <div id="choose_users">
                                        <div class="customScrollBox">
                                            <div class="horWrapper"> 
                                                <div class="container">
                                                    
                                                    <!-- Content [Begin] -->
                                                    <div class="content">
                                                    <!-- User Group [Begin] -->    
                                                        <div id="your_deputies">                            
                                                        
<?php            
            
            //senin vekillerin
            $SELECT = "SELECT DISTINCT md.*, pr.image, pr.name";
            $FROM   = "\n FROM mydeputy AS md";
            $JOIN   = "\n LEFT JOIN #__profile AS pr ON pr.ID = md.deputyID";            
            $WHERE  = "\n WHERE md.profileID = " . $db->quote(intval( $model->profileID ));
            $WHERE .= "\n AND md.status>0";
            $ORDER  = "\n ORDER BY md.datetime DESC";
            $LIMIT  = "\n LIMIT " . config::$mydeputylimit;
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            
            if(count($rows)){
                $html = '';
                $htmls = '';
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    $html .='
                            <div class="user selected_user" id="deputy_'.$row->deputyID.'">
                                <img src="'.$model->getProfileImage($row->image, 60, 60, 'cutout').'" class="medium_image" />
                                <div class="clear"></div>
                                <span class="name">'.$model->shortname( $row->name ).'</span>
                                <span class="delete"></span>                                                            
                            </div>';
            
                    if($i % 10 == 0) {
                        $htmls.= '<div class="user_group">' .$html. '</div>';
                        $html = '';
                    }
                    
                }
                
                if($i % 10 != 0) $htmls.= '<div class="user_group">' .$html. '</div>';
                
                echo $htmls;
                
                
            } else echo 'Hiç yok';
            
?>
    
                                                        </div>
                                                        <!-- User Group [End] -->
                                                    </div>
                                                    <!-- Content [End] -->
                                                </div>
                                                    
                                                <div class="dragger_container">
                                                    <div class="dragger"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="choose_bottom"></div>
                                
                            </div>
                            
                            <div class="clear" style="margin-bottom:20px;"></div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            <span class="box_sub_title">Arkadaşların</span>
                            <div class="line_center"></div>
                            
                            <div class="choose_box">
                                <div class="choose_top"></div>
                                <div class="choose_center">
                                    
                                    <!-- Choose Users [Begin] -->
                                    <div id="choose_friends">
                                        <div class="customScrollBox">
                                            <div class="horWrapper"> 
                                                <div class="container">
                                                    
                                                    <!-- Content [Begin] -->
                                                    <div class="content">
                                                    <!-- User Group [Begin] -->    
                                                        <div id="your_friends">
<?php            
            
            //arkadaşların
            $SELECT = "SELECT f.followerID, p.*";
            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=f.followerID AND md.deputyID=f.followingID) AS mydeputy";
            $FROM   = "\n FROM #__follow AS f";
            $JOIN   = "\n JOIN #__profile AS p ON p.ID = f.followingID";
            $WHERE  = "\n WHERE f.followerID=".$db->quote($model->profileID);
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ";//"ORDER BY s.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            
             if(count($rows)){
                $html = '';
                $htmls = '';
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    $selected = $row->mydeputy>0?' selected_user':'';
                    $delete   = $row->mydeputy>0?'':' hide';
                    $html .='
                            <div class="user '.$selected.'" id="friend_'.$row->ID.'">
                                <img src="'.$model->getProfileImage($row->image, 60, 60, 'cutout').'" class="medium_image" />
                                <div class="clear"></div>
                                <span class="name">'.$model->shortname( $row->name ).'</span>
                                <span class="delete '.$delete.'"></span>                                                            
                            </div>';
                            
                                 
                            
            
                    if($i % 10 == 0) {
                        $htmls.= '<div class="user_group">' .$html. '</div>';
                        $html = '';
                    }
                    
                }
                
                if($i % 10 != 0) $htmls.= '<div class="user_group">' .$html. '</div>';
                
                echo $htmls;
                
                
            } else echo 'Hiç yok';
            
?>
    
                                                        </div>
                                                        <!-- User Group [End] -->
                                                    </div>
                                                    <!-- Content [End] -->
                                                </div>
                                                    
                                                <div class="dragger_container">
                                                    <div class="dragger"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="choose_bottom"></div>
                                
                            </div>
                            
                            <div class="clear" style="margin-bottom:20px;"></div>
                            
                            
                            
                            
                                                        
                            
                          
                          
                          
                          
                          
                          
  
                            <span class="box_sub_title">Şuanki vekiller</span>
                            <div class="line_center"></div>
                            
                            <div class="choose_box">
                                <div class="choose_top"></div>
                                <div class="choose_center">
                                    
                                    <!-- Choose Users [Begin] -->
                                    <div id="old_deputies">
                                        <div class="customScrollBox">
                                            <div class="horWrapper"> 
                                                <div class="container">
                                                    
                                                    <!-- Content [Begin] -->
                                                    <div class="content">
                                                    <!-- User Group [Begin] -->    
                                                        <div id="deputies">
<?php            
                   
            //şu anki vekiller
            $SELECT = "SELECT DISTINCT pr.*";
            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=".$db->quote($model->profileID)." AND md.deputyID=pr.ID) AS mydeputy";
            $FROM   = "\n FROM profile AS pr";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE pr.deputy>0";
            $WHERE .= "\n AND pr.status>0";
            $ORDER  = "\n ";
            //$LIMIT  = "\n LIMIT " . config::$mydeputylimit;
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            
            
             if(count($rows)){
                $html = '';
                $htmls = '';
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    $selected = $row->mydeputy>0?' selected_user':'';
                    $delete   = $row->mydeputy>0?'':' hide';
                    $html .='
                            <div class="user hoverable" id="olddeputy_'.$row->ID.'">
                                <a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 60, 60, 'cutout').'" class="medium_image" /></a>
                                <div class="clear"></div>
                                <span class="name"><a href="/profile/'.$row->ID.'">'. $model->shortname( $row->name ).'</a></span>
                                                                                     
                            </div>';
                            
                                 
                            
            
                    if($i % 10 == 0) {
                        $htmls.= '<div class="user_group">' .$html. '</div>';
                        $html = '';
                    }
                    
                }
                
                if($i % 10 != 0) $htmls.= '<div class="user_group">' .$html. '</div>';
                
                echo $htmls;
                
                
            } else echo 'Hiç yok';
            
?>
    
                                                        </div>
                                                        <!-- User Group [End] -->
                                                    </div>
                                                    <!-- Content [End] -->
                                                </div>
                                                    
                                                <div class="dragger_container">
                                                    <div class="dragger"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="choose_bottom"></div>
                                
                            </div>
                            
                            <div class="clear" style="margin-bottom:20px;"></div>                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
                          
<?php
     if(0){
?>                         
                          
                          
                          
                          
                            
                            
                            
                            
                            
                            
                            <span class="box_sub_title">Şu anki vekiller</span>
                            <div class="line_center"></div>
                            
                            <div class="choose_box">
                                <div class="choose_top"></div>
                                <div class="choose_center">
                                    
                                    <!-- Choose Users [Begin] -->
                                    <div id="choose_friendss">
                                        <div class="customScrollBox">
                                            <div class="horWrapper"> 
                                                <div class="container">
                                                    
                                                    <!-- Content [Begin] -->
                                                    <div class="content">
                                                    <!-- User Group [Begin] -->    
                                                        
<?php            
            
            //şu anki vekiller
            $SELECT = "SELECT DISTINCT pr.*";
            $SELECT .= "\n , (SELECT status FROM mydeputy AS md WHERE md.profileID=".$db->quote($model->profileID)." AND md.deputyID=pr.ID) AS mydeputy";
            $FROM   = "\n FROM profile AS pr";
            $JOIN   = "\n ";
            $WHERE  = "\n WHERE pr.deputy>0";
            $WHERE .= "\n AND pr.status>0";
            $ORDER  = "\n ";
            //$LIMIT  = "\n LIMIT " . config::$mydeputylimit;
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            if(count($rows)){
                $html = '';
                $htmls = '';
                $i = 0;
                foreach($rows as $row){
                    $i++;
                    $selected = $row->mydeputy>0?' selected_user':'';
                    $delete   = $row->mydeputy>0?'':' hide';
                    $html .='
                            <div class="user " id="olddeputy_'.$row->ID.'">
                                <a href="/profile/'.$row->ID.'"><img src="'.$model->getProfileImage($row->image, 60, 60, 'cutout').'" class="medium_image" /></a>
                                <div class="clear"></div>
                                <span class="name"><a href="/profile/'.$row->ID.'">'. $model->shortname( $row->name ).'</a></span>
                                                                                     
                            </div>';
                            
                                 
                            
            
                    if($i % 10 == 0) {
                        $htmls.= '<div class="user_group">' .$html. '</div>';
                        $html = '';
                    }
                    
                }
                
                if($i % 10 != 0) $htmls.= '<div class="user_group">' .$html. '</div>';
                
                echo $htmls;
                
                
            } else echo 'Hiç yok';


            
?>
    
                                                        
                                                    </div>
                                                    <!-- Content [End] -->
                                                </div>
                                                  <div class="dragger_container">
                                                    <div class="dragger"></div>
                                                </div>  
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="choose_bottom"></div>
                                
                            </div>
                            
                            
<?php
        }
?>                            
                            
                            <div class="clear" style="margin-bottom:20px;"></div>
                            
                            
                            
                            <div class="clear" style="margin-bottom:20px;"></div>
                        
                            
                        
                        </div>                            
                             
<?php                                                                      
              
                       

        }
    }
?>