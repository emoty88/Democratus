<?php
    class mygroups_block extends control{
        
        public function block(){ return;
            global $model, $db, $l;
?>
 <!-- leftbox start -->
      <div class="leftbox">
        <div class="leftbox-head">
        <h3>Gruplar</h3></div>
        <div class="leftbox-body">
          <div class="leftbox-subhead">Yönettiğim Gruplar</div>
          <!--profilebox END-->
          <div class="leftbox-profilebox"><img src="/t/default/images/profile-img.jpg" width="60" height="60" alt="" />
            <div class="leftbox-profile-body">
               blla bla bal
            </div>
            <br class="clearfix" />
          </div>
          <!--profilebox END-->        
        
        
                  <div class="leftbox-subhead">Üyesi olduğum gruplar</div>
          <!--profilebox END-->
          <div class="leftbox-profilebox"><img src="/t/default/images/profile-img.jpg" width="60" height="60" alt="" />
            <div class="leftbox-profile-body">
               asdfa asdf asdfa sdfa sdfa asfas sas asfasfas as fasdfa sfas asf as fas f
            </div>
            <br class="clearfix" />
          </div>
          <!--profilebox END-->
        </div>
        <div class="leftbox-footer">&nbsp;</div>
      </div>
      <!-- leftbox end -->


<?php
        }
    }
?>