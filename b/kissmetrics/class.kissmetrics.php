<?php
    class kissmetrics_block extends control{
        
        public function block(){
        	global $model;
        ?>
			<script type="text/javascript">
			  var _kmq = _kmq || [];
			  var _kmk = _kmk || '0628f27a8295634329f648360a42174f8b1dcac0';
			  function _kms(u){
			    setTimeout(function(){
			      var d = document, f = d.getElementsByTagName('script')[0],
			      s = d.createElement('script');
			      s.type = 'text/javascript'; s.async = true; s.src = u;
			      f.parentNode.insertBefore(s, f);
			    }, 1);
			  }
			  _kms('//i.kissmetrics.com/i.js');
			  _kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
			</script>
			
			<?
			if($model->profileID>0)
			{
				
				KM::identify($model->user->email);
				//KM::set(array('gender'=>'male'));
				//KM::record('test', array('in Php' => 'True'));
				/*
				?>
					<script type="text/javascript">
						_kmq.push(['identify', '<?=$model->user->email?>']);
					</script>
				<?
				 * 
				 */
			}
        }
   }
?>