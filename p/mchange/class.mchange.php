<?php 
    class mchange_plugin extends control{
        public function main(){
        	global $model, $db;
            $model->mode=0;
        	?>
	<style>
		.ulkeUL 
		{
			float:left;
			list-style:none;
			margin:0;
			padding:0;
			width:150px;
			
		}

	</style>
	<div style="width:460px;">
		<h2>İlgilendiğiniz Ülke Meclisini Seçin:</h2>
		<h5>Yakında Democratus üzerinden diğer ülkelerin meclislerini de görüntüleyip 
			bu ülkelerdeki görüş ve fikirleri de takip edebileceksiniz.
		</h5>
	    <hr />
	    <ul class="ulkeUL">
	        <li>
	        	ABD
	        </li>
			<li>
	  			Almanya
	        </li>
	        <li>
	  			Arjantin
	        </li>
	        <li>
	  			Avustralya
	        </li>
	        <li>
	  			Birleşik Arap Emirlikleri
	  		</li>
	  		<li>
	  			Birleşik Krallık
	        </li>
	        <li>
	        	Brezilya
	        </li>
	        <li>
	        	Dominik Cumhuriyeti    	
	       	</li>        
	       	<li>
				Ekvador
	        </li>
	        <li>
				Endonezya
	       	</li>
	       	<li>
	  			Filipinler
	        </li>
	        <li>
	        	Fransa
	        </li>
	   	</ul>
	    <ul class="ulkeUL">
	        <li>
	        	Guatemala
	        </li>
	        <li>
				Güney Afrika
	       	</li>
	       	<li>
	  			Hindistan
	        </li>
	        <li>
	        	Hollanda
	        </li>
	        <li>
	  			Japonya
	       	</li>
	       	<li>
				Kanada
	        </li>
	        <li>
	        	Kolombiya
	        </li>
	        <li>
				Malezya
	        </li>
	        <li>
	        	Meksika
	        </li>
	        <li>
	        	Nijerya
	        </li>
	        <li>
	        	Pakistan
	       	</li>
	       	<li>
	       		Peru
	       	</li>
		</ul>
	    <ul class="ulkeUL">
	        <li>
	        	Rusya
	        </li>
	        <li>
	        	Singapur
	        </li>
	        <li>
	        	<a href="/">Türkiye</a>
	        </li>
	        <li>
	        	Venezuela
	        </li>
	        <li>
	        	Yeni Zelanda
	        </li>
	        <li>
	        	İrlanda
	        </li>
	        <li>
	        	İspanya
	        </li>
	        <li>
	        	İsveç
	        </li>
	        <li>
	        	İtalya
	        </li>
	        <li>
	        	Şili
	        </li>    
		</ul>
		
	</div>
        	<?php
			
		}
	}