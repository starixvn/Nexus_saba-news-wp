<?php 
/**
 * Partial template for Author Box
 */

?>
	<section class="author-info">
	
		<?php echo get_avatar(get_the_author_meta('user_email'), 95); ?>
		
		<div class="description">
			<?php the_author_posts_link(); ?>
			
			<ul class="social-icons">
			<?php 
			
				// Author fields.
				$fields = array(
					'url' => array('icon' => 'home', 'label' => esc_html__('Website', 'bunyad')),
					'bunyad_facebook' => array('icon' => 'facebook', 'label' => esc_html__('Facebook', 'bunyad')),
					'bunyad_twitter' => array('icon' => 'twitter', 'label' => esc_html__('X (Twitter)', 'bunyad')),
					'bunyad_pinterest' => array('icon' => 'pinterest-p', 'label' => esc_html__('Pinterest', 'bunyad')),
					'bunyad_instagram' => array('icon' => 'instagram', 'label' => esc_html__('Instagram', 'bunyad')),
					'bunyad_tumblr' => array('icon' => 'tumblr', 'label' => esc_html__('Tumblr', 'bunyad')),
					'bunyad_bloglovin' => array('icon' => 'heart', 'label' => esc_html__('BlogLovin', 'bunyad')),
					'bunyad_linkedin' => array('icon' => 'linkedin', 'label' => esc_html__('LinkedIn', 'bunyad')),
					'bunyad_dribbble' => array('icon' => 'dribbble', 'label' => esc_html__('Dribble', 'bunyad')),
				);
				
				foreach ($fields as $meta => $data): 
				
					if (!get_the_author_meta($meta)) {
						
						// Check legacy without prefix or by core.
						$meta = str_replace('bunyad_', '', $meta);
						if (!get_the_author_meta($meta)) {
							continue;
						}
					}
					
					$type = $data['icon'];
			?>
				
				<li>
					<a href="<?php echo esc_url(get_the_author_meta($meta)); ?>" class="icon tsi tsi-<?php echo esc_attr($type); ?>" title="<?php echo esc_attr($data['label']); ?>"> 
						<span class="visuallyhidden"><?php echo esc_html($data['label']); ?></span></a>				
				</li>
				
				
			<?php endforeach; ?>
			</ul>
			
			<p class="bio"><?php the_author_meta('description'); ?></p>
		</div>
		
	</section>