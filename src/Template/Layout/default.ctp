<!doctype html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

	<?php
	echo $this->Html->css([
		'main.css',
		'/js/vendor/node_modules/pikaday/css/pikaday.css'
	]);
	echo $this->Html->script([
		'main.min.js',
		'vendor/node_modules/moment/moment.js',
		'vendor/node_modules/pikaday/pikaday.js'
	]);
	?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
	<header class="main-header">
		<h1>Expenses</h1>
	</header>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
	<?= $this->fetch('bottomscripts'); ?>
</body>
</html>
