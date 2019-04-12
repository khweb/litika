<?php
/**
 * Single.php
 *
 * @package lptheme
 * @since 1.0
 */
get_header();
$title_wrapper = ( animo_get_opt('title-wrapper')) ? animo_get_opt('title-wrapper-template'):'default';
get_template_part('templates/title-wrapper/'.$title_wrapper);
?>

<div class="page-content-wrapper">

  <div class="container">
    <?php get_template_part('templates/global/blog-before-content'); ?>
      <div class="blog-container">
        <?php
          while ( have_posts() ) : the_post();
            get_template_part('templates/blog/blog-single/content', get_post_type());
          endwhile;
        ?>
      </div>
    <?php get_template_part('templates/global/blog-after-content'); ?>
	</div>
	<div class="post-nav-wr">
		<?php get_template_part('templates/blog/blog-single/next','post'); ?>
	</div>
	
</div>

<?php

get_footer();

