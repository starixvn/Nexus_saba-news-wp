<div class="wrap">
	<h1 class="wp-heading-inline">Replace Word</h1>
	<hr class="wp-header-end">
	<div class="tablenav top">
		<ul class="subsubsub">
			Enter your word below and replace with word.
		</ul>
	</div>
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<input type="hidden" name="setup-update" />
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox">
					</td>
					<th scope="col" id="title">Find</th>
					<th scope="col" id="author">Replace</th>
					<th scope="col" id="link">Link</th>
					<th scope="col" id="tags">Tags</th>
					<th scope="col" id="remove">Remove</th>
				</tr>
			</thead>
			<tbody id="rw_itemlist">
				<?php
					$rwallsetting = get_option('rw_plugin_settings');
					if (is_array($rwallsetting['rwsearch'])){  //if there are any finds already set
						$i=1;
						foreach($rwallsetting['rwsearch'] as $key => $search){ 
							$replace = $rwallsetting['rwchange'][$key];	
							$rwlinkdata = $rwallsetting['rwlink'][$key];	
							$rwtagdata = $rwallsetting['rwtagdata'][$key];?>
							<tr id="row<?php echo $i;?>" class="iedit author-self level-0 post-<?php echo $i;?> type-post">
								<th scope="row" class="check-column">
									<label class="screen-reader-text" for="cb-select-1">Select Hello world!</label>
									<input id="cb-select-1" type="checkbox" name="post[]" value="1">
									<div class="locked-indicator">
										<span class="locked-indicator-icon" aria-hidden="true"></span>
										<span class="screen-reader-text"></span>
									</div>
								</th>
								<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
									<textarea class='left' name='rwsearch[<?php echo $i;?>]' id='rwsearch<?php echo $i;?>'><?php echo esc_textarea( $search );?></textarea>
								</td>
								<td class="author column-author" data-colname="Author">
									<textarea class='left' name='rwchange[<?php echo $i;?>]' id='rwchange<?php echo $i;?>'><?php echo esc_textarea( $replace );?></textarea>
								</td>
								<td class="categories column-link" data-colname="Link">
									<input type='text' class='left' name='rwlink[<?php echo $i;?>]' id='rwlink<?php echo $i;?>' value='<?php echo esc_textarea( $rwlinkdata );?>'/>
								</td>
								<td class="tags column-tags" data-colname="Tags">
									<select class='left' name='rwtagdata[<?php echo $i;?>]' id='rwtagdata<?php echo $i;?>'>
										<option value=''/>Select</option>
										<option value="h1" <?php echo ($rwtagdata=='h1') ? "selected='selected'" : ""; ?> >h1</option>
										<option value="h2" <?php echo ($rwtagdata=='h2') ? "selected='selected'" : ""; ?> >h2</option>
										<option value="h3" <?php echo ($rwtagdata=='h3') ? "selected='selected'" : ""; ?> >h3</option>
										<option value="h4" <?php echo ($rwtagdata=='h4') ? "selected='selected'" : ""; ?> >h4</option>
										<option value="h5" <?php echo ($rwtagdata=='h5') ? "selected='selected'" : ""; ?> >h5</option>
										<option value="h6" <?php echo ($rwtagdata=='h6') ? "selected='selected'" : ""; ?> >h6</option>
										<option value="span" <?php echo ($rwtagdata=='span') ? "selected='selected'" : ""; ?> >span</option>
									</select>
								</td>
								<td><input type='button' class='button left remove' value='Remove' onClick='removeFormField("#row<?php echo $i;?>"); return false;' /></p></td>
							</tr>
						<?php $i++; }
						} else {
						echo 'Click "Add" to begin.';
					} ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td>
					<th scope="col" id="title">Find</th>
					<th scope="col" id="author">Replace</th>
					<th scope="col" id="link">Link</th>
					<th scope="col" id="tags">Tags</th>
					<th scope="col" id="remove">Remove</th>
				</tr>
			</tfoot>
		</table>
		<div id="divTxt"></div>
	    <div class="clearpad"></div>
		<input type="button" class="button action" value="Add" onClick="addNewField(); return false;" />
		<input type="submit" class="button action" value="Update Settings" />
	 	<input type="hidden" id="id" value="<?php echo $i; ?>" />
	</form>
</div>