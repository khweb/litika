<?php
/**
 * Single template file
 *
 * @package lptheme
 * @since 1.0
 */
$userID = wp_get_current_user()->ID;
$userCurs = get_field('curs-items', 'user_' . $userID);
$curs = get_the_terms($post->ID, 'curs')[0]->term_id;

if (isset($userCurs)) {
	foreach ($userCurs as $item) {
		if ($item->term_id == $curs) {
			$acces = true; 
			break;
		}else {
			$acces = false;
		}
	}
}

if( current_user_can('editor') || current_user_can('administrator') ){
	$acces = true;
}
?>

<article>
  <div class="content">
    <header>
      <h1><?php the_title(); ?></h1>
			<?php get_template_part('templates/blog/blog-single/parts/single', 'media'); ?>
		</header>

		<?php if (is_user_logged_in() && isset($acces)) : ?>
			<?php the_content(); ?>
		<?php else :?>
		<p>Для доступа необходимо приобрести курс</p>
		<?php endif;?>
    
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
