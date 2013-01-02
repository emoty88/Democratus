<?php
    class geribildirim_block extends control{
        
        public function block(){
            global $model, $db, $l;
?>
			<style>
				.geribildirim{
					font: normal normal bold 14px/1em Arial, sans-serif;
					position: fixed;
					left: -365px;
					top: 50%;
					z-index: 9999;
					width:395px;
					margin-top: -100px;
					display: block;
					margin-right: 0px;
				}
				.geribildirim .gbGovde
				{
					float:left;
					width:360px;
				}
				.geribildirim .gbIcon
				{
					float:right;
				}
			</style>
            <!-- GERİBİLDİRİM [BEGIN] -->
          	<div class="geribildirim roundedcontent shareidea">
          		<div class="gbGovde">
						<div class="textarea">
							<textarea maxlength="200" style="width: 340px; height: 120px; resize: none; overflow: hidden;" class="input-xlarge numberSay"  id="geribildirim" rows="3">@GeriBildirim </textarea>
				            <div id="degerler" style="display: none;">
								<input type="hidden" id="linkliGB" value="profile">
								<input type="hidden" id="profileNameGB" value="GeriBildirim">
								<input type="hidden" id="profileIDGB" value="4575">
							</div>
				            <ul style="float: right; list-style: none; margin:0 10px 0 0;">
				            	<li id="karakterGrubu" style="float: left;">
				            	<span style="color: #9B9B9B; font-size:10pt; margin-right: 10px; margin-top: 5px;"><span style="float: none;font-size:10pt;" id="geribildirimNumber">200</span> Karakter</span></li>
				            	<li style="float: left;"><button id="sharediGB" class="btn btn-gonder">Paylaş</button></li>
				            </ul>
			            </div>
					
          		</div>
          		<div class="gbIcon" style="cursor:pointer;">
          			<img src="/t/beta/img/gb.png" />
          		</div>
          		
          	</div>
          	<script>
          		$(".gbIcon").live("click",function(){
          			var offset=$(".geribildirim").offset();
          			var newLeft
          			if(offset.left==0)
              			newLeft=-365;
          			else
              			newLeft=0;
					$(".geribildirim").animate({left: newLeft},500);
             	});
				if (screen.width <= 699) {
					$(".geribildirim").remove();
				}
          	</script>
            <!-- GERİBİLDİRİM [END] -->    
<?php
        }
    }
?>