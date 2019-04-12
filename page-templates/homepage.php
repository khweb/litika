<?php
/**
 * Template Name: Главная
 *
 * @package animo
*/
get_header();
$title_wrapper = ( animo_get_opt('title-wrapper')) ? animo_get_opt('title-wrapper-template'):'default';
get_template_part('templates/title-wrapper/'.$title_wrapper);
?>

<div class="slider_block">
	<?php if( have_rows('slider') ): ?>

		<div id="home-carousel" class="carousel_container">
		<div class="owl-carousel owl-theme">
				<?php while( have_rows('slider') ): the_row(); 
					$image = get_sub_field('image');
					$caption = get_sub_field('caption');
					$subcaption = get_sub_field('subcaption');
					$link = get_sub_field('link');
				?>
					<div class="slide-block" style="background-image: url(<?php echo $image['url']; ?>);">

						<div class="inside">
							<div class="slide-caption">
							<?php echo $caption; ?>
							</div>
							<p>
							<?php echo $subcaption; ?>
							</p>
							<a href="#" class="js-modal-btn" data-video-id="<?php echo $link; ?>"><i class="fa fa-play"></i></a>
						</div>

					</div>
				<?php endwhile; ?>
			</div>
		</div>

	<?php endif; ?>
</div>

<div class="home-s-program section bg-gray">
	<div class="container">

		<div class="section-header">
			<div class="section-title">Программы обучения</div>
			<div class="section-desc">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты. Меня несколько однажды продолжил заманивший!</div>
		</div>

		<div class="row justify-content-center programs">

			<?php 
				$cursItems = get_terms( array( 
					'taxonomy' => 'curs',
					'orderby' => 'meta_value_num',
					'order' => 'ASC',
					'hide_empty' => false,
					'hierarchical' => false,
					'parent' => 0,
					'meta_query' => [[
						'key' => 'curs-filter',
						'type' => 'NUMERIC',
					]],
					) 
				);
			?>

			<?php if (isset($cursItems)) : ?>
			<?php foreach ($cursItems as $item) : ?>

				<div class="col-md-6 col-lg-4">
					<div class="program-item">
						<a href="<?php echo get_term_link($item->term_id); ?>">
							<div class="program-item-title"><?php echo $item->name?></div>
						</a>
						<div class="program-item-body" style="background-image: url(<?= get_field('curs-img', 'product_cat_' . $item->term_id)['url'];?>);"></div>
						<div class="program-item-footer">
							<div class="program-item-price">
								<?= get_field('curs-price', 'product_cat_' . $item->term_id);?>
								<a href="<?= get_home_url(); ?>/?add-to-cart=<?= get_field('cursProduct', 'product_cat_' . $item->term_id); ?>" class="btn">Оплатить</a>
							</div>
						</div>
					</div>
				</div>

			<?php endforeach; endif;?>

		</div>

	</div>
</div>

<div class="home-s-step section mask">
		<div class="section-header">
			<div class="section-title white">Этапы обучения</div>
			<div class="section-desc white">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты. Меня несколько однажды продолжил заманивший!</div>
		</div>

		<div class="container">
			<div class="row step-items">

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">1</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">2</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">3</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">6</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">5</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="step-item">
						<div class="step-item-icon">4</div>
						<div class="step-item-desc">Далеко-далеко за словесными, горами в стране</div>
					</div>
				</div>

			</div>
		</div>

</div>


<div class="home-s-advantages section">
	<div class="section-header">
		<div class="section-title">Наши преимущества</div>
		<div class="section-desc">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты. Меня несколько однажды продолжил заманивший!</div>
	</div>

	<div class="container">
		<div class="row advantages">
			<div class="col-lg-6">
				<ul>
						<li class="active">
								<div class="card text-center">
										<div class="card-body">
											<svg viewBox="0 0 64 64">
												<path d="M24 30h11a6.8 6.8 0 0 0 7-7c0-4.1-2.6-7-6.7-7H24v32h12.7c4.8 0 8.3-4.2 8.3-9s-4-9-10-9m-7-14v-4m6 4v-4m-6 40v-4m6 4v-4"
												fill="none" stroke="#111111" stroke-miterlimit="10" stroke-width="2" stroke-linejoin="round"
												stroke-linecap="round"></path>
												<path fill="none" stroke="#111111" stroke-miterlimit="10"
												stroke-width="2" d="M32 4.5L38 2l4.5 4.6H49l2.5 5.9 5.9 2.5v6.5L62 26l-2.5 6 2.5 6-4.6 4.5V49l-5.9 2.5-2.5 5.9h-6.5L38 62l-6-2.5-6 2.5-4.5-4.6H15l-2.5-5.9L6.6 49v-6.5L2 38l2.5-6L2 26l4.6-4.5V15l5.9-2.5L15 6.6h6.5L26 2l6 2.5z"
												stroke-linejoin="round" stroke-linecap="round"></path>
											</svg>
											<h5 class="card-title">Преимущества 1</h5>
											<p class="card-text">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты.</p>
										</div>
								</div>
						</li>
						<li>
								<div class="card text-center">
										<div class="card-body">
											<svg viewBox="0 0 64 64">
												<path d="M24 30h11a6.8 6.8 0 0 0 7-7c0-4.1-2.6-7-6.7-7H24v32h12.7c4.8 0 8.3-4.2 8.3-9s-4-9-10-9m-7-14v-4m6 4v-4m-6 40v-4m6 4v-4"
												fill="none" stroke="#111111" stroke-miterlimit="10" stroke-width="2" stroke-linejoin="round"
												stroke-linecap="round"></path>
												<path fill="none" stroke="#111111" stroke-miterlimit="10"
												stroke-width="2" d="M32 4.5L38 2l4.5 4.6H49l2.5 5.9 5.9 2.5v6.5L62 26l-2.5 6 2.5 6-4.6 4.5V49l-5.9 2.5-2.5 5.9h-6.5L38 62l-6-2.5-6 2.5-4.5-4.6H15l-2.5-5.9L6.6 49v-6.5L2 38l2.5-6L2 26l4.6-4.5V15l5.9-2.5L15 6.6h6.5L26 2l6 2.5z"
												stroke-linejoin="round" stroke-linecap="round"></path>
											</svg>
											<h5 class="card-title">Преимущества 2</h5>
											<p class="card-text">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты.</p>
										</div>
								</div>
						</li>
						<li>
								<div class="card text-center">
										<div class="card-body">
											<svg viewBox="0 0 64 64">
												<path d="M24 30h11a6.8 6.8 0 0 0 7-7c0-4.1-2.6-7-6.7-7H24v32h12.7c4.8 0 8.3-4.2 8.3-9s-4-9-10-9m-7-14v-4m6 4v-4m-6 40v-4m6 4v-4"
												fill="none" stroke="#111111" stroke-miterlimit="10" stroke-width="2" stroke-linejoin="round"
												stroke-linecap="round"></path>
												<path fill="none" stroke="#111111" stroke-miterlimit="10"
												stroke-width="2" d="M32 4.5L38 2l4.5 4.6H49l2.5 5.9 5.9 2.5v6.5L62 26l-2.5 6 2.5 6-4.6 4.5V49l-5.9 2.5-2.5 5.9h-6.5L38 62l-6-2.5-6 2.5-4.5-4.6H15l-2.5-5.9L6.6 49v-6.5L2 38l2.5-6L2 26l4.6-4.5V15l5.9-2.5L15 6.6h6.5L26 2l6 2.5z"
												stroke-linejoin="round" stroke-linecap="round"></path>
											</svg>
											<h5 class="card-title">Преимущества 3</h5>
											<p class="card-text">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты.</p>
										</div>
								</div>
						</li>
						<li>
								<div class="card text-center">
										<div class="card-body">
											<svg viewBox="0 0 64 64">
												<path d="M24 30h11a6.8 6.8 0 0 0 7-7c0-4.1-2.6-7-6.7-7H24v32h12.7c4.8 0 8.3-4.2 8.3-9s-4-9-10-9m-7-14v-4m6 4v-4m-6 40v-4m6 4v-4"
												fill="none" stroke="#111111" stroke-miterlimit="10" stroke-width="2" stroke-linejoin="round"
												stroke-linecap="round"></path>
												<path fill="none" stroke="#111111" stroke-miterlimit="10"
												stroke-width="2" d="M32 4.5L38 2l4.5 4.6H49l2.5 5.9 5.9 2.5v6.5L62 26l-2.5 6 2.5 6-4.6 4.5V49l-5.9 2.5-2.5 5.9h-6.5L38 62l-6-2.5-6 2.5-4.5-4.6H15l-2.5-5.9L6.6 49v-6.5L2 38l2.5-6L2 26l4.6-4.5V15l5.9-2.5L15 6.6h6.5L26 2l6 2.5z"
												stroke-linejoin="round" stroke-linecap="round"></path>
											</svg>
											<h5 class="card-title">Преимущества 4</h5>
											<p class="card-text">Далеко-далеко за словесными, горами в стране гласных и согласных живут рыбные тексты.</p>
										</div>
								</div>
						</li>
				</ul>
			</div>
			<div class="col-lg-6 d-flex align-items-center justify-content-center">
				<img src="<?= get_template_directory_uri()?>/img/slider-m-1.png" alt="<?php the_title();?>">
			</div>
		</div>
	</div>
</div>

<div class="home-s-info section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="home-s-info-txt">
					К теоретическому курсу обучения <span>6 месяцев</span> online поддержки
				</div>
			</div>
		</div>
	</div>
</div>

<div class="home-s-lessons section bg-gray">
	<div class="container">
		<div class="section-title">Регулярные online уроки и вебинары с учениками</div>
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="lessons-content">Время проведения уроков и вебинаров смотри в разделе "Объявления администрации" и получай в рассылке</div>
			</div>
			<div class="col-lg-6 d-flex align-items-center justify-content-center">
				<img src="<?= get_template_directory_uri()?>/img/team-bg.png" alt="<?php the_title();?>">
			</div>
		</div>
	</div>
</div>

<!-- <div class="seotext-area">
  <div class="container">
    <?php //get_template_part('templates/content/content-page'); ?>
  </div>
</div> -->

<?php
get_footer();
