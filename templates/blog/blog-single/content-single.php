<?php
/**
 * Single template file
 *
 * @package lptheme
 * @since 1.0
 */
?>

<article>
  <div class="content">
    <header>
      <h1><?php the_title(); ?></h1>
     <div class="meta">
  <span><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('d.m.Y'); ?></time></span>
  <span><a href="#"><?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?></a></span>
  <ul class="categories">
          <li><?php echo get_the_category_list( __( ' ', 'animo' ) );?></li>
        </ul>
</div><!-- /meta -->
      <?php get_template_part('templates/blog/blog-single/parts/single', 'media'); ?>
    </header>
    <?php the_content(); ?>
  </div><!-- /content -->

  <?php if(get_the_tag_list()): ?>
  <div class="article-tags">
    <p><?php esc_html_e('Tags:', 'animo'); ?></p>
    <?php echo get_the_tag_list('<ul><li>','</li><li>','</li></ul>'); ?>
  </div>
  <?php endif; ?>


  <?php
    // If comments are open or we have at least one comment, load up the comment template
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;
  ?>
</article>
