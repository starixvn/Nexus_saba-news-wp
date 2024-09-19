<?php 
/**
 * Meta box for post reviews.
 * 
 * @var Bunyad_Admin_MetaRenderer $this
 */

include trailingslashit(__DIR__) . 'options.php';
$basic_options  = $this->options($basic_options);
$schema_options = $this->options($schema_options);

$this->default_values = array_replace($this->default_values, [
	'_bunyad_review_overall' => $this->default_values['_bunyad_review_overall'] ?? '',
	'_bunyad_review_cons'    => $this->default_values['_bunyad_review_cons'] ?? '',
	'_bunyad_review_pros'    => $this->default_values['_bunyad_review_pros'] ?? '',
	'_bunyad_review_pros_title' => $this->default_values['_bunyad_review_pros_title'] ?? '',
	'_bunyad_review_cons_title' => $this->default_values['_bunyad_review_cons_title'] ?? '',
	'_bunyad_review_percent' => $this->default_values['_bunyad_review_percent'] ?? '' 
]);

$review_scale  = intval(Bunyad::options()->review_scale);
$criteria_data = Bunyad::reviews()->get_criteria();
$pros_data     = maybe_unserialize($this->default_values['_bunyad_review_pros'] ?? []);
$cons_data     = maybe_unserialize($this->default_values['_bunyad_review_cons'] ?? []);

$render_options = function($options) {

	foreach ($options as $element): 
		$class = $element['name'];
		if (isset($element['group']) && $element['group'] === 'schema') {
			$class .= ' is-schema-option';
		}
	?>
	<div class="option <?php echo esc_attr($class); ?>">
		<span class="label"><?php echo esc_html($element['label']); ?></span>
		<span class="field">
			<?php echo $this->render($element); ?>

			<?php if (!empty($element['desc'])): ?>
			
			<p class="description"><?php echo esc_html($element['desc']); ?></p>
		
			<?php endif;?>
		</span>
	</div>
	
	<?php 
	endforeach; 
};

?>

<div class="bunyad-meta bunyad-review bunyad-meta-editor cf">
<input type="hidden" name="bunyad_meta_box[]" value="<?php echo esc_attr($box_id); ?>" />

	<input type="hidden" name="_bunyad_review_percent" value="<?php echo esc_attr($this->default_values['_bunyad_review_percent']); ?>" size="3" />

	<?php 
		// Render basic options.
		$render_options($basic_options);
	?>

	<div class="option bunyad-review-criteria">
		<span class="label"><?php esc_html_e('Criteria', 'bunyad-admin'); ?></span>
		<div class="field criteria">
		
			<p>
				<input type="button" class="button add-more" value="<?php esc_attr_e('Add More Criteria', 'bunyad-admin'); ?>" 
					data-type="criteria" />
			</p>
			<p><?php esc_html_e('Overall rating auto-calculated:', 'bunyad-admin'); ?> <strong>
				<input type="text" name="_bunyad_review_overall" value="<?php echo esc_attr($this->default_values['_bunyad_review_overall']); ?>" size="3" />
				</strong></p>
				
			<div class="bunyad-group-fields fields"></div>
		</div>
	</div>

	<div class="option bunyad-review-pros">
		<span class="label"><?php esc_html_e('Pros', 'bunyad-admin'); ?></span>
		<div class="field">
			<p>
				<?php esc_html_e('Heading:', 'bunyad-admin'); ?> &nbsp;
				<input type="text" placeholder="<?php esc_attr_e('The Good', 'bunyad'); ?>" name="_bunyad_review_pros_title" value="<?php echo esc_attr($this->default_values['_bunyad_review_pros_title']); ?>" class="input" />
			</p>		
			<p>
				<input type="button" class="button add-more" 
					value="<?php esc_attr_e('Add A Pro', 'bunyad-admin'); ?>" 
					data-type="pros" />
			</p>
			
			<?php // Hidden field needed to allow metabox delete if none exist. ?>
			<input type="hidden" name="_bunyad_review_pros[]" value="" />

			<div class="bunyad-group-fields fields"></div>
		</div>
	</div>

	<div class="option bunyad-review-cons">
		<span class="label"><?php esc_html_e('Cons', 'bunyad-admin'); ?></span>
		<div class="field">
			<p>
				<?php esc_html_e('Heading:', 'bunyad-admin'); ?> &nbsp;
				<input type="text" placeholder="<?php esc_attr_e('The Bad', 'bunyad'); ?>" name="_bunyad_review_cons_title" value="<?php echo esc_attr($this->default_values['_bunyad_review_cons_title']); ?>" class="input" />
			</p>

			<p>
				<input type="button" class="button add-more" 
					value="<?php esc_attr_e('Add A Con', 'bunyad-admin'); ?>" 
					data-type="cons" />
			</p>

			<?php // Hidden field needed to allow metabox delete if none exist. ?>
			<input type="hidden" name="_bunyad_review_cons[]" value="" />
				
			<div class="bunyad-group-fields fields"></div>
		</div>
	</div>

	<div class="option-sep"></div>

	<?php 
		// Render schema options.
		$render_options($schema_options);
	?>

</div>

<script type="text/html" class="bunyad-review-tpl-criteria">
	<div class="criterion bunyad-group-item">
		<span class="delete dashicons dashicons-dismiss"></span>

		<span class="group-label"><?php esc_html_e('Criterion', 'bunyad-admin'); ?> &mdash;</span>
		<label>
			<?php esc_html_e('Label:', 'bunyad-admin'); ?> 
			<input type="text" name="_bunyad_criteria_label_%number%" class="input-rating" />
		</label>
		<label><?php esc_html_e('Rating:', 'bunyad-admin'); ?>
			<input type="text" name="_bunyad_criteria_rating_%number%" size="3" /> / <?php echo $review_scale; ?>
		</label>
	</div>
</script>

<script type="text/html" class="bunyad-review-tpl-pros">
	<div class="bunyad-group-item">
		<span class="delete dashicons dashicons-dismiss"></span>

		<label>
			<span class="group-label"><?php esc_html_e('Pro', 'bunyad-admin'); ?></span>
			<input type="text" name="_bunyad_review_pros[]" value="%value%" />
		</label>
	</div>
</script>

<script type="text/html" class="bunyad-review-tpl-cons">
	<div class="bunyad-group-item">
		<span class="delete dashicons dashicons-dismiss"></span>

		<label>
			<span class="group-label"><?php esc_html_e('Con', 'bunyad-admin'); ?></span>
			<input type="text" name="_bunyad_review_cons[]" value="%value%" />
		</label>
	</div>
</script>

<script>
jQuery(function($) {
	"use strict";

	const repeatField = (optionId, tplId, value, number) => {
		const option = $(optionId);

		// Current count
		let fieldsCount = option.data('fieldsCount') || 0;
		fieldsCount++;

		if (number) {
			fieldsCount = number;
		}

		// Get our template and modify it
		var html = $(tplId).html();
		html = html.replace(/%number%/g, fieldsCount);
		html = html.replace(/%value%/g, ['string', 'number'].includes(typeof value) ? value : '');
		
		option.find('.fields').append(html);

		// Update counter
		option.data('fieldsCount', fieldsCount);
	}
	
	const addMore = function(e, number) {
		const type = $(e.currentTarget).data('type');
		if (!type) {
			return false;
		}

		repeatField(`.bunyad-review-${type}`, `.bunyad-review-tpl-${type}`, number);
		return false;
	};

	var overall_rating = function() {
		var count = 0, total = 0, number = null; 
		$('.bunyad-review-criteria input[name*="criteria_rating"]').each(function() {

			number = parseFloat($(this).val());

			if (!isNaN(number)) {
				total += number;
				count++;
			}
		});

		var rating = (total/count).toFixed(1);
		$('.bunyad-review-criteria .overall-rating').html(rating);
		$('.bunyad-review-criteria input[name="_bunyad_review_overall"]').val(rating);
		$('.bunyad-review-criteria input[name="_bunyad_review_percent"]').val(Math.round(rating / <?php echo $review_scale; ?> * 100));	
	};

	$('.bunyad-review-criteria').on('blur', 'input[name*="criteria_rating"]', function() {
		if ($(this).val() > <?php echo $review_scale; ?>) {
			alert("<?php printf(esc_attr__('Rating cannot be greater than %d.', 'bunyad-admin'), $review_scale); ?>");
			$(this).val(<?php echo $review_scale; ?>);
		}

		overall_rating();
	});

	$('.bunyad-review-criteria .add-more').on('click', addMore);

	$('.bunyad-review-criteria').on('click', '.delete', function() {
		$(this).parents('.criterion').remove();
	});

	/**
	 * Add criteria
	 */
	var criteriaData = <?php echo json_encode($criteria_data); ?>;
	if (criteriaData.length) { 
		$.each(criteriaData, function(i, value) {
			repeatField(
				`.bunyad-review-criteria`, 
				`.bunyad-review-tpl-criteria`, 
				value, 
				value.number
			);

			$('[name=_bunyad_criteria_label_' + value.number + ']').val(value.label);
			$('[name=_bunyad_criteria_rating_' + value.number + ']').val(value.rating);
		});

		overall_rating();
	}
	else {
		$('.bunyad-review-criteria .add-more').trigger('click');
	}

	/**
	 * Add pros and cons.
	 */
	const doRepeater = (type, data) => {
		const optionId = `.bunyad-review-${type}`;

		$(`${optionId} .add-more`).on('click', addMore);

		$(optionId).on('click', '.delete', function() {
			$(this).parents('.bunyad-group-item').remove();
		});

		console.log(data);
		if (!data) {
			return;
		}

		Object.values(data).forEach(value => {
			repeatField(
				optionId,
				`.bunyad-review-tpl-${type}`,
				value
			);
		});

		$(optionId + ' .bunyad-group-fields').sortable();
	}

	const consData = <?php echo json_encode($cons_data); ?>;
	const prosData = <?php echo json_encode($pros_data); ?>;

	doRepeater('cons', consData);
	doRepeater('pros', prosData);

	/**
	 * Conditional show/hide
	 */
	$('[name=_bunyad_review_schema]').on('change', function() {

		var current = $(this).val();
		
		const getSelector = d => d.map(item => '._bunyad_review_' + item).join(',');
		
		const hideShow = (types, fields) => {
			const select = getSelector(fields);
			types.includes(current) ? $(select).show() : $(select).hide()
		}		
		
		const selector = getSelector([
			'item_author',
			'item_author_type',
			'item_link',
			'item_name'
		]);
		current === 'none' ? $(selector).hide() : $(selector).show();

		hideShow(['SoftwareApplication'], [
			'item_os',
			'item_app_cat'
		]);
		
		// Offers schema
		hideShow(['', 'SoftwareApplication', 'Product'], [
			'item_price',
			'item_currency'
		]);

		return;
	})
	.trigger('change');


	/**
	 * Show / hide all options.
	 */
	const handleShow = function() {
		const checked = $(this).is(':checked');
		const elements = $(this).closest('.bunyad-review').find('.option:not(._bunyad_reviews), .option-sep');

		checked ? elements.show() : elements.hide();
		return;
	}

	const element = $('[name=_bunyad_reviews]');
	element.on('click', handleShow)
	handleShow.call(element);
		
	
});
</script>