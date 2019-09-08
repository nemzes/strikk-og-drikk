<?php $template_dir = esc_url( get_template_directory_uri() ); ?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<title><?php wp_title( '' ); ?><?php if ( wp_title( '', false ) ) { echo ' : '; } ?><?php bloginfo( 'name' ); ?></title>

		<link href="<?php echo $template_dir; ?>/img/icons/favicon.ico" rel="shortcut icon" />
		<link href="<?php echo $template_dir; ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed" />
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<!-- wrapper -->
		<div class="ssod-site">

			<!-- header -->
			<header class="global-header" role="banner">

				<div class="ssod-layout-clamp">
					<div class="global-header--wrap">
						<div class="site-logo">
							<a href="<?php echo esc_url( home_url() ); ?>">
								<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo-small.svg" alt="Logo">
							</a>
						</div>

						<nav class="ssod-global-nav" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'global-nav' ) ); ?>
						</nav>

						<?php get_template_part( 'searchform' ); ?>
					</div>
				</div>


			</header>
			<!-- /header -->
