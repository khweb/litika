<?php
/**
 * Template Name: Объявления администрации
 *
 * @package lptheme
*/


get_header();
$title_wrapper = ( animo_get_opt('title-wrapper')) ? animo_get_opt('title-wrapper-template'):'default';
get_template_part('templates/title-wrapper/'.$title_wrapper);

?>



<div class="page-content-wrapper">
  <div class="container">
   <?php get_template_part('templates/global/page-before-content');?>

		<h1><?php the_title();?></h1>

		<div class="ads-wr-loop">
		<?php 
			$post_args = array(
				'post_type' => 'ads'
			);
			$query =  new WP_Query( $post_args );
		?>
		<?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>

			<div <?php post_class('adsItem'); ?>>
				<div class="adsItem-title"><?php the_title(); ?></div>
				<div class="adsItem-content"><?php the_content(); ?></div>
				
			</div>

		<?php endwhile; wp_reset_postdata(); endif;?>
		</div>

  <?php if (have_posts()): the_post();  the_content(); endif;  ?> 
  <?php get_template_part('templates/global/page-after-content');?>
</div>
</div>


<?php get_footer();
