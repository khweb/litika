<?php
/**
 * Blog next post navigation
 *
 * @package animo
 * @since 1.0
 */
?>
<?php if (get_post_type() == 'lessons') : ?>
<?php
	$termId = get_the_terms($post->ID, 'curs')[0]->term_id;

// get_posts in same custom taxonomy
$postlist_args = array(
	'numberposts'  => -1,
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
			'terms' => $termId,
			)
	)
); 
$postlist = get_posts( $postlist_args );
// get ids of posts retrieved from get_posts
$ids = array();
foreach ($postlist as $thepost) {
	$ids[] = $thepost->ID;
}

// get and echo previous and next post in the same taxonomy        
if (count($ids) >= 2) {

	$thisindex = array_search($post->ID, $ids);
	$previd = isset($ids[$thisindex - 1]) ? $ids[$thisindex - 1] : '';
	$nextid = isset($ids[$thisindex + 1]) ? $ids[$thisindex + 1] : '';
	if ( isset($previd) && $previd != '' ) {
		echo '<a class="post-nav-btn post-prev" href="' . get_permalink($previd). '"></a>';
	}
	if ( isset($nextid) && $nextid != '') {
		echo '<a class="post-nav-btn post-next" href="' . get_permalink($nextid). '"></a>';
	}

}
endif;
?>
