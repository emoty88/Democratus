<?php
    class mainwall_block extends control{
        
        public function block(){
            global $model, $db, $l;
?>
        <!-- mainwall -->
        <div class="middlebox">
        
        <div class="middlebox-head">
          <h3>başlık</h3>
          &nbsp;</div>
        <div class="middlebox-body">
          <div id="shareitbox">
            <textarea id="shareittext" rows="5" cols="5"></textarea>
            <ul id="shareitmenu">
              <li class="videomenu"><a href="#">Video</a></li>
              <li class="photomenu"><a href="#">Photo</a></li>
              <li class="notemenu"><a href="#">Note</a></li>
              <li class="urlmenu"><a href="#">URL</a></li>
            </ul>
          </div>
          <br class="clearfix" />
          <div class="sharebox">
            <div class="shreimg"> <img src="/t/default/images/profileimg-bg.png" alt="" width="160" height="120" /> </div>
            <div class="sharetitle">başlık başlık başlık</div>
            <div class="sharedesc">başlık başlık başlıkbaşlık başlık başlıkbaşlık başlık başlıkbaşlık başlık</div>
            <div class="sharelikebox">Takdir Et(12) - Saygı Duy(33) </div>
            <div class="shareinfo"> <img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"> <strong>Mehmet Efe Yılmaz</strong> Dün Ekledi </div>
            </div>
            <div class="commentsbox">
              <div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>                            
              
            </div>
          </div>
          
          
          
          
          
          
          
<div class="sharebox">
            <div class="shreimg"> <img src="/t/default/images/profileimg-bg.png" alt="" width="160" height="120" /> </div>
            <div class="sharetitle">başlık başlık başlık</div>
            <div class="sharedesc">başlık başlık başlıkbaşlık başlık başlıkbaşlık başlık başlıkbaşlık başlık</div>
            <div class="sharelikebox">Takdir Et(12) - Saygı Duy(33) </div>
            <div class="shareinfo"> <img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"> <strong>Mehmet Efe Yılmaz</strong> Dün Ekledi </div>
            </div>
            <div class="commentsbox">
              <div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>                            
              
            </div>
          </div>
          
          
          
          
          
          
          <div class="sharebox">
            <div class="shreimg"> <img src="/t/default/images/profileimg-bg.png" alt="" width="160" height="120" /> </div>
            <div class="sharetitle">başlık başlık başlık</div>
            <div class="sharedesc">başlık başlık başlıkbaşlık başlık başlıkbaşlık başlık başlıkbaşlık başlık</div>
            <div class="sharelikebox">Takdir Et(12) - Saygı Duy(33) </div>
            <div class="shareinfo"> <img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"> <strong>Mehmet Efe Yılmaz</strong> Dün Ekledi </div>
            </div>
            <div class="commentsbox">
              <div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>                            
              
            </div>
          </div>
          
          
          
          
          
          
          
          <div class="sharebox">
            <div class="shreimg"> <img src="/t/default/images/profileimg-bg.png" alt="" width="160" height="120" /> </div>
            <div class="sharetitle">başlık başlık başlık</div>
            <div class="sharedesc">başlık başlık başlıkbaşlık başlık başlıkbaşlık başlık başlıkbaşlık başlık</div>
            <div class="sharelikebox">Takdir Et(12) - Saygı Duy(33) </div>
            <div class="shareinfo"> <img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="32" height="32" align="middle" class="shareinfoimg" />
              <div class="shareinfobody"> <strong>Mehmet Efe Yılmaz</strong> Dün Ekledi </div>
            </div>
            <div class="commentsbox">
              <div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> ailskdfjialskfjilaskdjfi aisdlf asf</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong><strong>Mehmet Efe Yılmaz</strong></strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum porttitor ultrices. Praesent pharetra neque sit amet felis...Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum porttitor ultrices. Praesent pharetra neque sit amet felis...</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>
              
              
              
<div class="commentbox">
                <div class="commentimg"><img src="http://a1.twimg.com/profile_/t/default/images/1090665055/Galatasaray__niversitesi_seminer_004_minik_normal.jpg" alt="" width="60" height="60" align="middle" /></div>
                <div class="comment">
                  <div class="commenthead"><img src="/t/default/images/sharecomment-head.png" width="407" height="6" alt="" /></div>
                  <div class="commentbody"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum porttitor ultrices. Praesent pharetra neque sit amet felis...</div>
                  <div class="commentlikebox">
                    <div class="commentlikeboxbutton">Takdir Et(12)</div>
                    <div class="commentlikeboxbutton">Saygı Duy(33)</div>
                  </div>
                  <div class="commentfoot" style="clear:both"><img src="/t/default/images/sharecomment-foot.png" width="407" height="6" alt="" /></div>
                </div>
                <div class="commentinfo"><strong>Mehmet Efe Yılmaz</strong> 12 Şubat Cuma 12:22’de yazdı.</div>
              </div>                            
              
            </div>
          </div>          
          
          
          
          
          
          <p>asdfasfd</p>
          <p>asdfasdfas</p>
          <p>asdfasdfasd</p>
          <p>fasdfa</p>
        </div>
        <div class="middlebox-footer">&nbsp;</div>
      </div> 
      
      <!-- mainwall END -->
<?php
        }
    }
?>