<!DOCTYPE html>
<html> 
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
       	<?php
    	global $model; 
    	$model->addMeta('<meta name="viewport" content="width=device-width, initial-scale=1"> ');
    	//$model->addStyle('/t/v2/static/style/sharksfrenzy.css', '/t/v2/static/style/sharksfrenzy.css', 1 );
    	$model->addStyle('/t/v2/static/style/democratus_mobile.css', 'democratus_mobile.css', 1 );
    	$model->addStyle('http://code.jquery.com/mobile/1.0.1/jquery.mobile.structure-1.0.1.min.css', 'jquery.mobile.structure-1.0.1.min.css', 1 );    
		$model->addScript('http://code.jquery.com/jquery-1.7.1.min.js', 'jquery-1.7.1.min.js', 1);
		$model->addScript('http://code.jquery.com/mobile/1.1.0-rc.2/jquery.mobile-1.1.0-rc.2.min.js', 'jquery.mobile-1.1.0-rc.2.min.js', 1);
		$model->addScript('/p/mobile/mobile.js', 'mobile.js',1);
		?>	
	</head>
	<body>
			
	<?php
	    $notice_count = intval(get_notice_count($model->profileID) );
	    if($notice_count>0){
	        $notice_count = ' ('.$notice_count.') ';
	    } else {
	        $notice_count = '';
	    }
	?> 
		
		<div data-role="page" data-theme="a" class="pageLoadEventCls">

			<div data-theme="a" data-role="header" class="ui-header ui-bar-a hcn" role="banner" id="headerBar">
				<h1 class="ui-title" tabindex="0" role="heading" aria-level="1">Democratus</h1>
				<a href="/mobile/home" data-role="button" data-icon="home" data-mini="true" class="ui-btn-left" data-iconpos="notext"></a>
				<a href="javascript:;" onclick="usermenuShow();" data-role="button" data-icon="gear" data-mini="true" class="ui-btn-right" data-iconpos="notext"></a>
					<!-- 
					<a class="ui-btn-right jqm-home ui-btn ui-btn-icon-notext ui-btn-corner-all ui-shadow ui-btn-up-a" data-direction="reverse" data-iconpos="notext" data-icon="home" href="#" title="Home" data-theme="a">
						<span class="ui-btn-inner ui-btn-corner-all" aria-hidden="true">
							<span class="ui-btn-text">Home</span>
							<span class="ui-icon ui-icon-home ui-icon-shadow"></span>
						</span></a> -->
			</div><!-- /header -->
			<div id="userMenuMenu" class="userMenuMenuCls ui-overlay-shadow ui-corner-all ui-body-b pop in " data-theme="a" style="position: absolute; right:20px; width: 70%; display: none; z-index: 9999;"  >
				<div data-role="controlgroup" style="margin:10px;"data-theme="a" >
					<a href="/mobile/profile/<?=$model->profileID;?>" data-role="button" data-mini="true" data-icon="grid" >Profil</a>
					<a href="/mobile/notice" data-role="button" data-icon="delete" >Olaylar <?=$notice_count?></a>
					<a href="javascript:logout();" data-role="button" data-mini="true" data-icon="delete" >Çıkış Yap</a>
				</div>
			</div>
				{{main}}
				<div class="sescontainer" style="display:none">
					<hr/>
					<div id="shareitbox">
						<div id="sharestatus">
							<textarea id="shareditext" rows="5" cols="25"></textarea>
							<input type="button" id="sharedi" value="Paylaş" onclick="sharedi();" />
						</div>
					</div>
					<br class="clearfix" />
				</div>
		<div class="nav-glyphish-example ui-footer ui-bar-a" data-role="footer" data-position="fixed" role="contentinfo">
			<div data-grid="c" class="nav-glyphish-example ui-navbar" data-role="navbar" role="navigation">
			<ul class="ui-grid-c">
				<li class="ui-block-a" >
					<a data-icon="home" id="chat" href="javascript:;" onclick="$('.sescontainer').toggle();"  data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-a">
						<span class="ui-btn-text">Ses Yaz</span>
					</a>
				</li>
				<li class="ui-block-b" >
					<a data-icon="grid" id="chat" href="/mobile/wall" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-a">
						<span class="ui-btn-text">Takip Ettiklerim</span>
					</a>
				</li>
				<li class="ui-block-c" >
					<a data-icon="star" id="chat" href="/mobile/wall/deputy" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-a">
						<span class="ui-btn-text">Vekiller</span>
					</a>
				</li>
				<li class="ui-block-d" >
					<a data-icon="gear" id="chat" href="javascript:gotoWeb();" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-iconpos="top" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-a">
						<span class="ui-btn-text">Web</span>
					</a>
				</li>
			</ul>
			</div>
		</div>
			</div><!-- /page -->
    </body>
</html>
<?php 
       function get_notice_count($profileID){
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
 ?>