<?php
    class home_plugin extends control{
        
        public function main(){
            global $model, $db, $l;
            //die;
            if($model->userID<1)
                return $model->redirect('/wellcome');

            $model->view = 'home';
            //$this->shareform();
            
            if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['uploadfile'])){
                $this->__sharephoto();
            }
            
            
            $followerID = intval( $model->profileID );
            
            $SELECT = "SELECT DISTINCT s.*, sharer.image AS sharerimage, sharer.name AS sharername";
            $FROM   = "\n FROM #__share AS s";
            $JOIN   = "\n LEFT JOIN #__follow AS f ON f.followingID = s.profileID";
            $JOIN  .= "\n LEFT JOIN #__profile AS sharer ON sharer.ID = s.profileID";
            //$JOIN   = "\n ";
            $WHERE  = "\n WHERE (s.profileID = " . $db->quote($followerID) . " OR f.followerID=".$db->quote($followerID).")";
            $WHERE .= "\n AND f.status>0";
            $ORDER  = "\n ORDER BY s.datetime DESC";
            $LIMIT  = "\n LIMIT 5";
            $LIMIT  = "\n ";
            
            $db->setQuery($SELECT . $FROM . $JOIN . $WHERE . $ORDER . $LIMIT);
            $rows = $db->loadObjectList();
            
            //echo '<h3>' . $db->_sql . '</h3>';
            //die;
            
            $model->addScript(PLUGINURL . 'lib/jquery/jquery.js', 'jquery.js', 1);
            $model->addScript(PLUGINURL . 'lib/common.js', 'common.js', 1);
            $model->addScript($model->pluginurl . 'home.js', 'home.js', 1);
            
            $model->addScript('profileID = ' . $model->profileID );
            
            if(count($rows)){
?>
      <div class="middlebox">
        <div class="middlebox-head">
          <h3>Güncellemeler</h3>
          &nbsp;</div>
        <div class="middlebox-body">
          <div id="shareitbox">
              <div id="sharephoto">
                <form action="/" method="POST" enctype="multipart/form-data">
                <p>
                <label>Başlık:</label>
                <input type="text" value="" id="sharephototitle" name="sharephototitle" />
                </p>
                <p>
                <label>Fotografın urlsi:</label>
                <input type="file" name="uploadfile" accept="image/jpg, image/gif, image/png" />
                </p>
                <input type="hidden" name="profileID" value="<?=$model->profileID?>" />
                <input type="submit" id="sharephotobtn" value="share photo" />
                </form>
              </div>
              
              <div id="shareurl">
                <input type="text" value="" id="shareurltitle" />
                <input type="text" value="" id="shareurlurl" />
                <input type="button" id="shareurlbtn" value="share url" />
              </div>
              
              <div id="sharestatus">
                <textarea id="shareittext" rows="5" cols="5"></textarea>
                <input type="button" id="sharestatusbtn" value="share status" />
              </div>
              
              
            <ul id="shareitmenu">
              <li class="photomenu">Photo</li>
              <li class="notemenu">Note</li>
              <li class="urlmenu">URL</li>
            </ul>
            
          </div>
          <br class="clearfix" />
<?php
            foreach($rows as $row){
                $sharelike = $this->getsharelikeinfo($row->ID);
                
                //status
                if($row->type == 1){
                
                
                
?>          
<!--sharebox-->          
    <div class="sharebox">
            <div class="shreimg"> <img src="/u/share-status-image.png" alt="" width="48" height="48" /> </div>
            <div class="sharetitle"><?=$row->title?></div>
            <div class="sharedesc"><?=$row->description?></div>
            <div class="sharelikebox"><span class="shareappreciatebutton" rel="<?=$row->ID?>">Takdir Et (<?=$sharelike->appreciate?>)</span> - <span class="shareregardbutton" rel="<?=$row->ID?>">Saygı Duy (<?=$sharelike->regard?>)</span> </div>
            <div class="shareinfo"> <img src="<?=$model->getProfileImage( $row->sharerimage, 32, 32, 'cutout' )?>" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"><strong><?=$row->sharername?></strong> tarafından <strong><?=$row->datetime?></strong> tarihinde ekledi </div>
            </div>
            <div class="sharecommentsbox" id="sharecommentsbox<?=$row->ID?>" rel="<?=$row->ID?>">
            &nbsp;
            </div>        

            
              <div class="commentbox">
                <div class="commentimg"><img src="<?=$model->getProfileImage($model->profileimage, 60, 60, 'cutout')?>" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"><textarea name="sharecomment" id="sharecomment<?=$row->ID?>"></textarea></div>
                  <div class="commentlikebox">
                    <input type="button" value="Yorumla" class="sharecommentbutton" rel="<?=$row->ID?>">
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                
              </div>
             <br class="clearfix" />
          </div>          
<!--sharebox END-->                   
<?php
                } elseif($row->type == 2){
                    
                    
                    
?>          
<!--sharebox-->          
    <div class="sharebox">
            <div class="shreimg"> <img src="/u/share-status-image.png" alt="" width="48" height="48" /> </div>
            <div class="sharetitle"><?=$row->title?></div>
            <div class="sharedesc"><a href="<?=$row->url?>"><?=$row->url?></a></div>
            <div class="sharelikebox"><span class="shareappreciatebutton" rel="<?=$row->ID?>">Takdir Et (<?=$sharelike->appreciate?>)</span> - <span class="shareregardbutton" rel="<?=$row->ID?>">Saygı Duy (<?=$sharelike->regard?>)</span> </div>
            <div class="shareinfo"> <img src="<?=$model->getProfileImage( $row->sharerimage, 32, 32, 'cutout' )?>" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"><strong><?=$row->sharername?></strong> tarafından <strong><?=$row->datetime?></strong> tarihinde ekledi </div>
            </div>
            <div class="sharecommentsbox" id="sharecommentsbox<?=$row->ID?>" rel="<?=$row->ID?>">
            &nbsp;
            </div>

            
              <div class="commentbox">
                <div class="commentimg"><img src="<?=$model->getProfileImage($model->profileimage, 60, 60, 'cutout')?>" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"><textarea name="sharecomment" id="sharecomment<?=$row->ID?>"></textarea></div>
                  <div class="commentlikebox" id="commentlikebox<?=$row->ID?>">
                    <input type="button" value="Yorumla" class="sharecommentbutton" rel="<?=$row->ID?>">
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                
              </div>
             <br class="clearfix" />
          </div>          
<!--sharebox END-->                   
<?php                    
                    
                    
                    
                }elseif($row->type == 3){
                    
                    
                    
?>          
<!--sharebox-->          
    <div class="sharebox">
            <?php 
                $shareimg = $model->getImage($row->image, 120,120,'cutout');
                if(strlen($shareimg)>0){
            ?>
            <div class="shreimg">
                <img src="<?=$model->getImage($row->image, 120,120,'cutout')?>" alt="" width="160" height="120" /> 
            </div>
            <?php } ?>
            <div class="sharetitle"><a href="<?=$row->url?>"><?=$row->title?></a></div>
            <div class="sharedesc"><?=$row->description?></div>
            <div class="sharelikebox"><span class="shareappreciatebutton" rel="<?=$row->ID?>">Takdir Et (<?=$sharelike->appreciate?>)</span> - <span class="shareregardbutton" rel="<?=$row->ID?>">Saygı Duy (<?=$sharelike->regard?>)</span> </div>
            <div class="shareinfo"> <img src="<?=$model->getProfileImage( $row->sharerimage, 32, 32, 'cutout' )?>" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"><strong><?=$row->sharername?></strong> tarafından <strong><?=$row->datetime?></strong> tarihinde ekledi </div>
            </div>
            <div class="sharecommentsbox" id="sharecommentsbox<?=$row->ID?>" rel="<?=$row->ID?>">
            &nbsp;
            </div>

            
              <div class="commentbox">
                <div class="commentimg"><img src="<?=$model->getProfileImage($model->profileimage, 60, 60, 'cutout')?>" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"><textarea name="sharecomment" id="sharecomment<?=$row->ID?>"></textarea></div>
                  <div class="commentlikebox" id="commentlikebox<?=$comment->ID?>">
                    <input type="button" value="Yorumla" class="sharecommentbutton" rel="<?=$row->ID?>">
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                
              </div>
             <br class="clearfix" />
          </div>          
<!--sharebox END-->                   
<?php                    
                    
                    
                    
                }





            }
?>
          </div>
        <div class="middlebox-footer">&nbsp;</div>
      </div>
          
<?php                

            } else {
                echo '<p>not found</p>';
            }
            
            
            
        }
        
        public function shareform(){
?>

    <form action="/share/" method="post">
      <input type="text" name="title" />
      <input type="submit" value="share" />
    </form>

<?php
        }
        
        
        
        public function getsharelikeinfo($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharelike WHERE shareID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
        
        public function getsharecommentlikeinfo($ID){
            global $model, $db;
            
            $db->setQuery('SELECT SUM(regard) AS regard, SUM(appreciate) AS appreciate FROM sharecommentlike WHERE commentID = ' . $db->quote($ID) );
            if( $db->loadObject($result) )
                return $result;
            else {
                return (object) array('regard'=>0, 'appreciate'=>0 );
            }
        }
        
       public function __sharephoto(){
            global $model, $db;
            //$model->mode = 0;
                  try{
                if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST')) throw new Exception('this is not a post');
                

                
                //if($pageID<=0) throw new Exception('pageID error');
            
                // Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
                $POST_MAX_SIZE = ini_get('post_max_size');
                $unit = strtoupper(substr($POST_MAX_SIZE, -1));
                $multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

                if ((int)@$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
                    //header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
                    throw new Exception("POST exceeded maximum allowed size.");
                }
                
                // Settings
                $save_path = $model->getUploadPath();
                
                //mkdir($save_path);

                 // "c:\\web\\uploads\\";//getcwd() . "/uploads/";                // The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
                $upload_name = "uploadfile";
                $max_file_size_in_bytes = 2147483647;                // 2GB in bytes
                $extension_whitelist = array("jpg", "gif", "png");    // Allowed file extensions
                $valid_chars_regex = '.A-Z0-9_!@#$%^&()+={}\[\]\',~`-';                // Characters allowed in the file name (in a Regular Expression format)
                 
                // Other variables    
                $MAX_FILENAME_LENGTH = 260;
                $file_name = "";
                $file_extension = "";
                $uploadErrors = array(
                    0=>"There is no error, the file uploaded with success",
                    1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
                    2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                    3=>"The uploaded file was only partially uploaded",
                    4=>"No file was uploaded",
                    6=>"Missing a temporary folder"
                );
                
             //if post 



            //HandleError("test");
            // Validate the upload
                if (!isset($_FILES[$upload_name])) {
                    throw new Exception("No upload found in \$_FILES for " . $upload_name);
                } 
                elseif (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
                    throw new Exception($uploadErrors[$_FILES[$upload_name]["error"]].$_FILES[$upload_name]['name']);
                } 
                elseif (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
                    throw new Exception("Upload failed is_uploaded_file test.");
         
                } 
                elseif (!isset($_FILES[$upload_name]['name'])) {
                    throw new Exception("File has no name.");
                }
                
            // Validate the file size (Warning: the largest files supported by this code is 2GB)
                $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
                if (!$file_size || $file_size > $max_file_size_in_bytes) {
                    throw new Exception("File exceeds the maximum allowed size");
                }
                
                if ($file_size <= 0) {
                    throw new Exception("File size outside allowed lower bound");
                }

                
            // Validate file name (for our purposes we'll just remove invalid characters)
                $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "_", basename($_FILES[$upload_name]['name']));
                
                while(strstr($file_name,'__')) $file_name = str_replace('__', '_', $file_name);
                
                $path_info = pathinfo($_FILES[$upload_name]['name']);
                $file_extension = strtolower($path_info["extension"]);
                
                $file_name = substr_replace($file_name,$file_extension,strlen($file_name)-strlen($file_extension));
                
                //$file_name = $path_info["basename"].'.'.$file_extension;
                
                if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
                    throw new Exception("Invalid file name");
                }


            // Validate that we won't over-write an existing file
                if (file_exists($save_path . $file_name)) {
                    throw new Exception("File with this name already exists");
                }
                
            // Validate file extension
                $is_valid_extension = false;
                foreach ($extension_whitelist as $extension) {
                    if (strcasecmp($file_extension, $extension) == 0) {
                        $is_valid_extension = true;
                        break;
                    }
                }
                if (!$is_valid_extension) {
                    throw new Exception("Invalid file extension");
                }
                
                if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], UPLOADPATH.$save_path.$file_name)) {
                    throw new Exception("File could not be saved.");
                }
                
                
                
                $profileID     =  filter_input(INPUT_POST, 'profileID', FILTER_SANITIZE_NUMBER_INT);
                
                $share->image = $save_path.$file_name;
                $share->title = strip_tags( filter_input(INPUT_POST, 'sharephototitle', FILTER_SANITIZE_STRING));
                $share->type        = 3;//photo image
                $share->datetime    = date('Y-m-d H:i:s');
                $share->sharerID    = intval( $model->profileID );
                $share->profileID   = intval( $profileID );
                $share->userID      = intval( $model->userID );
                $share->ip          = ip2long($_SERVER['REMOTE_ADDR']);
                
                if( $db->insertObject('share', $share) ){
                    //$response['status'] = 'success';
                    
                    $shareID = $db->insertid();
                    
                } else {
                    throw new Exception('record error');
                }                
                

                $row = array();
                $row['shareID']     = $shareID;
                $row['profileID']   = $profileID;
                $row['imagepath']   = $save_path.$file_name;
                $row['status']      = 1;
                
                $row['datetime']    = date('Y-m-d H:i:s');
                $row['sharerID']    = intval( $model->profileID );
                $row['userID']      = intval( $model->userID );
                $row['ip']          = ip2long($_SERVER['REMOTE_ADDR']);                
                
                $row = (object) $row;
                
                if( $db->insertObject('shareimage', $row) ){
                    //echo 'ok';
                    //echo $db->insertid();
                } else {
                    //hata
                    throw new Exception('page image db insert error');
                }
            } catch(Exception $e) {
                if(!headers_sent()) header("HTTP/1.1 500 ".$e->getMessage());
            }
            

     
        } 
    }
?>