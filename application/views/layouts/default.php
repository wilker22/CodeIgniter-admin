<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
		<base href="<?php echo base_url(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $ggt_title; ?></title>
        <meta name="keywords" content="<?php echo $ggt_keywords; ?>">
        <meta name="description" content="<?php echo $ggt_description; ?>">
        <meta name="viewport" content="width=1024">
        <link rel="stylesheet" href="<?php echo base_url().CSS; ?>normalize.min.css">
        <?php echo $ggt_css; ?>
        <script src="<?php echo base_url().VENDOR; ?>modernizr-2.6.1.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
<?php if (user_logado()): ?>
<div class="container">
	<header>
		<div id="saudacao">Bem vindo(a), <i><?php echo user_logado(); ?></i>. <a href="<?php echo site_url('admin/logout');?>">fazer logout</a></div>
		<h1 class="logo"><?php echo NM_SISTEMA_HTML; ?></h1>
	</header>
	<nav>
		<?php echo menu_monta();?>
	</nav>
	<section>
		<?php echo $ggt_content; ?>
	</section>
	
	<footer>
		<div class="logo"><img height="45" title="<?php echo NM_SISTEMA; ?>" alt="<?php echo NM_SISTEMA; ?>" src="<?php echo LOGOMARCA;?>" /></div>
		<div><?php echo NM_EMPRESA . ' - ' . NM_SISTEMA; ?> v.: <?php echo N_VERSAO; ?></div>
	</footer>
</div>
<?php else: ?>
	<?php echo $ggt_content; ?>
<?php endif; ?>
        <?php echo $ggt_js;?>
        <!-- Página renderizada em {elapsed_time} segundos -->
    </body>
</html>
