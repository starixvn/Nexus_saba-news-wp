<?php
/**
 * 
 * @access      public
 * @since       1.2
 * @return      $Front-action
*/
function rw_main_core_call($buffer){ 
	// $buffer contains entire page
		$rwallsetting = get_option('rw_plugin_settings');
		if (is_array($rwallsetting['rwsearch'])){
			foreach ($rwallsetting['rwsearch'] as $key => $search){
				$pattern = "/(".$search.")(?=[^>]*(<|$))/";
				if(isset($rwallsetting['rwlink'][$key]) && $rwallsetting['rwlink'][$key]!='')
				{
					if(isset($rwallsetting['rwtagdata'][$key]) && $rwallsetting['rwtagdata'][$key]!=''){
						$newchange = '<a href="'.$rwallsetting['rwlink'][$key].'" target="_blank">'.'<'.$rwallsetting['rwtagdata'][$key].'>'.$rwallsetting['rwchange'][$key].'</'.$rwallsetting['rwtagdata'][$key].'>'.'</a>';
					}else{
						$newchange = '<a href="'.$rwallsetting['rwlink'][$key].'" target="_blank">'.$rwallsetting['rwchange'][$key].'</a>';
					}
					
					$buffer = preg_replace($pattern, $newchange, $buffer);
				}
				elseif (isset($rwallsetting['rwtagdata'][$key]) && $rwallsetting['rwtagdata'][$key]!='')
				{
					if($rwallsetting['rwtagdata'][$key]=='a')
					{	
						$newchange = '<a href="'.$rwallsetting['rwlink'][$key].'" target="_blank">'.$rwallsetting['rwchange'][$key].'</a>';
					}
					if($rwallsetting['rwtagdata'][$key]=='span')
					{	
						$newchange = '<span>'.$rwallsetting['rwchange'][$key].'</span>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h1')
					{	
						$newchange = '<h1>'.$rwallsetting['rwchange'][$key].'</h1>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h2')
					{	
						$newchange = '<h2>'.$rwallsetting['rwchange'][$key].'</h2>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h3')
					{	
						$newchange = '<h3>'.$rwallsetting['rwchange'][$key].'</h3>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h4')
					{	
						$newchange = '<h4>'.$rwallsetting['rwchange'][$key].'</h4>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h5')
					{	
						$newchange = '<h5>'.$rwallsetting['rwchange'][$key].'</h5>';
					}
					if($rwallsetting['rwtagdata'][$key]=='h6')
					{	
						$newchange = '<h6>'.$rwallsetting['rwchange'][$key].'</h6>';
					}
					$buffer = preg_replace($pattern, $newchange, $buffer);

				}
				else
				{
					$buffer = preg_replace($pattern, $rwallsetting['rwchange'][$key], $buffer);
				}
			}
		}
	return $buffer;
}
function rw_func_call(){
	ob_start('rw_main_core_call');
}
add_action('template_redirect', 'rw_func_call');