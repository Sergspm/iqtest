<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?=View::getInstance()->getVar('meta.title', 'Гостевая книга')?></title>

	<link href="assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/lib/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="assets/css/application.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="assets/lib/html5shiv/dist/html5shiv.min.js"></script>
	<script src="assets/lib/respond/dest/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">Гостевая книга</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="/">Главная</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Гость</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container main-container">
		<?=View::getInstance()->renderView()?>
	</div>




	<script src="assets/lib/jquery/dist/jquery.min.js"></script>
	<script src="assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="assets/js/application.js"></script>
</body>
</html>