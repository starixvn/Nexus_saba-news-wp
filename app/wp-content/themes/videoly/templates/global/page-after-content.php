<?php
/**
 *
 * @package videoly
 */
$layout = (is_archive() || is_search()) ? 'right_sidebar':videoly_get_opt('main-layout');
if ($layout == 'left_sidebar'): ?>
  </div>
</div><!-- .row -->
<?php elseif ($layout == 'right_sidebar'): ?>
  </div>
  <?php get_sidebar(); ?>
 </div><!-- .row -->
<?php else: ?>
	</div>
</div>
<?php endif; ?>
