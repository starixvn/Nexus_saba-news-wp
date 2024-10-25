<?php
/**
 * Template Name: Authors List
 */

get_header();

?>

<div <?php Bunyad::markup()->attribs('main'); ?>>

	<div class="ts-row">
		<div class="col-8 main-content">
			
			<?php if (have_posts()): the_post(); endif; // load the page ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if (Bunyad::posts()->meta('page_title') !== 'no'): ?>
			
				<header class="post-header">
					<h1 class="main-heading">
						<?php the_title(); ?>
					</h1>
				</header><!-- .post-header -->
				
			<?php endif; ?>
		
			<div class="post-content">
				<?php Bunyad::posts()->the_content(); ?>
			</div>
		
			<div class="authors-list">
				
				<?php 
					
					$per_page = 10;
					$paged = get_query_var('paged');

					// setup user query
					$args = array(
						'orderby'    => 'post_count',
						'order'      => 'DESC',
						'capability' => ['edit_posts'],
						'offset'     => $paged ? (($paged - 1) * $per_page) : 0,
						'number'     => $per_page,
						'count_total' => true,
					);

					// Capability queries were only introduced in WP 5.9.
					if (version_compare($GLOBALS['wp_version'], '5.9', '<')) {
						$args['who'] = 'authors';
						unset($args['capability']);
					}

					$user_query = new WP_User_Query($args);
					
					// how many pages?
					$total_users = $user_query->get_total();
					$pages = ceil($total_users / $per_page);
					
					// get authors
					$authors = (array) $user_query->get_results();
					
					foreach ($authors as $author) {
						
						$post_count = count_user_posts($author->ID);
						
						if ($post_count > 0) {
							$author->description .= '<span class="posts"><a href="'. get_author_posts_url($author->ID) .'" class="ts-button smaller" title="'. esc_attr(__('Browse Author Articles', 'bunyad')) .'">' 
								. sprintf(__('%s Articles', 'bunyad'), '<strong>'. $post_count .'</strong>') . '</a></span>';
						}
						
						$authordata = $author;
						get_template_part('partials/author');
						
						echo '<hr class="separator" />';
					}
					
				?>
				
			</div>

			</article>
			
			<?php 
			if ($pages > 1): 
				$query = $user_query;
				$query->set('paged', $paged);
			?>
			
			<div class="main-pagination pagination-numbers">
				<?php echo Bunyad::posts()->paginate(['total' => $pages], $query); ?>
			</div>
			
			<?php endif; ?>
			
		</div>
		
		<?php Bunyad::core()->theme_sidebar(); ?>
		
	</div> <!-- .row -->
</div> <!-- .main -->

<?php get_footer(); ?>
