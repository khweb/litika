<?php
/**
 * Template Name: Blog
 *
 * @package LPtheme
*/
get_header();
$title_wrapper = ( animo_get_opt('title-wrapper')) ? animo_get_opt('title-wrapper-template'):'default';
get_template_part('templates/title-wrapper/'.$title_wrapper);

if (get_query_var('paged')) {
  $paged = get_query_var('paged');
} elseif (get_query_var('page')) { // applies when this page template is used as a static homepage in WP3+
  $paged = get_query_var('page');
} else {
  $paged = 1;
}
$post_per_page = animo_get_post_opt('blog-posts-per-page');
if (!$post_per_page) {
  $post_per_page = get_option('posts_per_page');
}

$post_args = array(
  'posts_per_page' => $post_per_page,
  'orderby'        => 'date',
  'paged'          => $paged,
  'order'          => 'DESC',
  'post_type'      => 'post',
  'post_status'    => 'publish'
);

$categories = animo_get_post_opt('category');
if (is_array($categories)) {
  $post_args['category__in'] =  $categories;
}

$query = new WP_Query( $post_args );
if(is_page()) {
  $max_num_pages = $query -> max_num_pages;
} else {
  global $wp_query;
  $term = $wp_query->get_queried_object();
	$max_num_pages = false;
	$query = new WP_Query( array(
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'hide_empty' => false,
		'meta_query' => [[
			'key' => 'lessons-num',
			'type' => 'NUMERIC',
		]],
		'post_type'       => 'lessons',
		'tax_query'       => array(
			array(
					'taxonomy' => 'curs',
					'field' => 'term_id',
					'terms' => $term->term_id,
					)
			)
	) );
}
?>

<div class="archive-content">
  <div class="container">

		<?php get_template_part('templates/global/blog-before-content'); ?>
		<h1><?php the_archive_title(); ?></h1>
    <div class="blog-container row">
      <?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>
      <article <?php post_class('col-12 col-sm-6 col-md-6'); ?>>
        <div class="single-post-card">
          <?php  ?>
          <div class="content">

            <h4><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h4>

            <p>
              <?php echo animo_auto_post_excerpt(30); ?>
            </p>
						<a href="<?php echo esc_url(get_the_permalink()); ?>" class="read-more"><?php esc_html_e('Read More', 'animo'); ?></a>
						<div class="num-lessons"><?php the_field('lessons-num'); ?></div>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); else:
          get_template_part('templates/content', 'none');
        endif; ?>
    </div><!-- /blog-container -->

    <?php animo_paging_nav($max_num_pages, 'default'); ?>
    <?php get_template_part('templates/global/blog-after-content'); ?>

  </div><!-- /container -->
</div>

<?php
get_footer();
