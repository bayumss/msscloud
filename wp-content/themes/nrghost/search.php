<?php
/**
 * Search Page
 *
 * @package nrghost
 * @since 1.0.0
 *
 */

global $nrghost_opt;

get_header(); ?>

<div class="container-above-header"></div>

<div class="blocks-container">

	<div class="container blog-wrapper">
		<div class="row">
			<div class="col-md-9 col-sm-12">
				<?php if ( have_posts() ) : ?>
					<h2 class="search-heading title"><?php esc_html_e( 'Results for searching ', 'nrghost' ); ?>"<?php echo esc_textarea( get_search_query() ); ?>":</h2>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php $post_format = ( get_post_format() == true ) ? get_post_format() : 'standard';

						if ( $post_format == 'gallery' ) {
							$gallery = nrghost_get_post_gallery( $post->ID );
						} elseif ( $post_format == 'video' OR $post_format == 'audio' ) {
							$iframe = nrghost_get_first_tag_from_string( $post->post_content );
							$post->post_content = str_replace( $iframe, '', $post->post_content );
						} elseif( $post_format == 'quote' ) {
							$quote = nrghost_get_first_tag_from_string( $post->post_content, 'blockquote' );
							$post->post_content = str_replace( $quote, '', $post->post_content );
						} ?>
						<div id="post-id-<?php the_ID(); ?>" <?php post_class( 'blog-entry wow fadeInLeft' ); ?>>
							<div class="data-column">
								<?php $date_format = '\<\s\p\a\n\>d\<\/\s\p\a\n\>M Y'; ?>
								<div class="date"><?php the_time( $date_format ); ?></div>
								<?php nrghost_post_like_link( $post->ID ); ?>
								<div class="data-entry"><span class="icon-entry views"></span><br/><span class="count"><?php nrghost_post_views(); ?></span></div>
								<?php if ( comments_open( $post->ID ) ) { ?><div class="data-entry"><span class="data-entry scrollto"><span class="icon-entry comments"></span><br/><span class="count"><?php comments_number('0','1', '%'); ?></span></span></div><?php } ?>
							</div>
							<div class="content">
								<div class="thumbnail-entry">
									<?php if ( $post_format == 'video' && !empty( $iframe ) ) { ?>
										<div class="embed-responsive embed-responsive-16by9"><?php print $iframe; ?></div>
									<?php } elseif ( $post_format == 'audio' && !empty( $iframe ) ) { ?>
										<div class="soundcloud-wrapper"><?php print $iframe; ?></div>
									<?php } elseif ( $post_format == 'quote' && !empty( $quote ) ) { ?>
										<?php print $quote; ?>
									<?php } elseif ( $post_format == 'gallery' && !empty( $gallery ) ) { ?>
										<div class="blog-swiper">
											<div class="swiper-container" data-autoplay="0" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
												<div class="swiper-wrapper">
													<?php foreach ( $gallery as $image ) { ?>
														<div class="swiper-slide">
															<img class="center-image" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
														</div>
													<?php } ?>
												</div>
												<div class="pagination style-1"></div>
											</div>
										</div>
									<?php } else {
										nrghost_post_thumbnail( $post->ID, 'thumbnail-img' );
									} ?>
								</div>

								<a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
								<div class="author">
									<?php nrghost_post_categories( ', ' ); ?> by <b><?php the_author_meta('display_name') ?></b>
									<?php the_tags('<div class="post-tags"> <span class="fa fa-tag col-red"></span>', '', '</div>'); ?>
								</div>

								<div class="typography-block">
									<div class="medium-font">
										<?php the_excerpt(); ?>
									</div>
								</div>
								<a class="button" href="<?php the_permalink(); ?>">Read More</a>
							</div>
						</div>
					<?php endwhile; ?>
				<?php else: ?>
					<h2 class="title text-center"><?php esc_html_e( 'Nothing found for', 'nrghost' ); ?> "<?php echo esc_textarea( get_search_query() ); ?>"</h2>
					<h3 class="title text-center">You can go to <a href="<?php echo esc_url( home_url() ); ?>" class="go-home"><?php esc_html_e( 'Home Page', 'nrghost' ); ?></a><br><?php esc_html_e( 'or try to use another words to search...', 'nrghost' ); ?></h3>
				<?php endif; ?>

				<?php $paginator = get_the_posts_pagination( array(
					'mid_size' => 3,
					'prev_text' => esc_html__( 'Prev page', 'nrghost' ),
					'next_text' => esc_html__( 'Next page', 'nrghost' ),
				) );
				$paginator = str_replace( 'class="next', 'class="next button', $paginator );
				$paginator = str_replace( 'class="prev', 'class="prev button', $paginator );
				print $paginator;
				?>

			</div>
			<div class="sidebar col-md-3 col-sm-12 wow fadeInRight">
				<?php get_sidebar( 'sidebar' ); ?>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>