<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php 
		$model->addStyle($this->url.'Democratus.min.css', 'Democratus.min.css', 1 ); 
		$model->addStyle('http://code.jquery.com/mobile/1.3.0/jquery.mobile.structure-1.3.0.min.css', 'jquery.mobile.structure-1.3.0.min.css', 1 ); 
		$model->addScript('http://code.jquery.com/jquery-1.9.1.min.js','jquery-1.9.1.min.js',1);
		$model->addScript('http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js','jquery.mobile-1.3.0.min.js',1);
		$model->addScript(PLUGINURL.'mobile/mobile.js', 'mobile.js', 1 ); 
		?>
	</head>
	<body>
		<div data-role="page" data-theme="a">
			<div data-role="header" data-position="inline">
				<h1>Democratus</h1>
			</div>
			<div data-role="content" data-theme="a">
				{{main}}
			</div>
		</div>
	</body>
</html>