<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="user-scalable=0, width=device-width, initial-scale=1, maximum-scale=2.0"/>
	<?php wp_head(); ?>

	<script type='application/ld+json'> 
	{
	  "@context": "http://www.schema.org",
	  "@type": "ProfessionalService",
	  "name": "A-List Signs & Banners",
	  "url": "http://www.alistbanners.com/",
	  "logo": "http://alistbanners.com/wp-content/uploads/2016/10/alsitbanner_logo.png",
	  "image": "http://alistbanners.com/wp-content/uploads/2016/12/step-and-repeat-banners-2.jpg",
	  "address": {
	    "@type": "PostalAddress",
	    "streetAddress": "4342 N Selland Ave #101",
	    "addressLocality": "Fresno",
	    "addressRegion": "CA",
	    "postalCode": "93722",
	    "addressCountry": "United States"
	  },
	  "openingHours": "Mo, Tu, We, Th, Fr 08:00-05:00"
	}
	 </script>

</head>

<body <?php body_class(); ?>>

<?php do_action( 'et_after_body' ); ?>

<?php
$header_type = etheme_get_header_type();
?>

<div class="template-container">
	<?php if ( is_active_sidebar('top-panel') && etheme_get_option('top_panel') ): ?>
		<div class="top-panel-container">
			<div class="top-panel-inner">
				<div class="container">
					<?php dynamic_sidebar( 'top-panel' ); ?>
					<div class="close-panel"></div>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div class="mobile-menu-wrapper">
		<div class="container">
			<div class="navbar-collapse">
				<?php if(etheme_get_option('search_form')): ?>
					<?php etheme_search_form( array(
						'action' => 'default'
					)); ?>
				<?php endif; ?>
				<?php etheme_get_mobile_menu(); ?>
				<?php etheme_top_links( array( 'short' => true ) ); ?>
				<?php dynamic_sidebar('mobile-sidebar'); ?>
			</div><!-- /.navbar-collapse -->
		</div>
	</div>
	<div class="template-content">
		<div class="page-wrapper">

<?php get_template_part( 'headers/' . $header_type ); ?>