<?php 
class interaction_block extends control{

	public function block(){
		global $model;
		echo '<input type="hidden" value="0" id="interactionFirstID" />';
		echo '<div class="roundedcontent lastactions">
				<ul class="scrollspyactions" id="interactionBar">
																
				</ul>
			</div>';
	}
}
?>
