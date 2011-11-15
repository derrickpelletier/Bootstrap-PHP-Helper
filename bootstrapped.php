<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Name: Twitter Bootstrap PHP Helper Library
 * Author: Derrick Pelletier - http://dpelletier.com
 * Description: Twitter Bootstrap form element generator
 */

class Bootstrapped {
		
	public $error_callback = FALSE;
	
	public function __construct() {
		if($this->error_callback == FALSE && function_exists('form_error')){
			$this->error_callback = "form_error";
		}
	}
	
	/**
	 * Set the error callback method for checking to see if there was an error with the field
	 */
	public function set_error_method($callback) {
		$this->error_callback = $callback;
	}
	
	/**
	 * Error check handler.
	 * Returns the boolean result of the error_callback method if set
	 */
	private function error($name) {
		return !empty($this->error_callback) && call_user_func($this->error_callback, $name);
	}
	
	/**
 	* Text Input
 	* Supports the prepend feature
 	*/
	function input($label, $name, $value, $args){
		return $this->proxied($label, $name, $value, "input", $args);
	}   

	/**
	 * Textarea
	 * Supports 'rows'
	 */
	function textarea($label, $name, $value, $args){
		return $this->proxied($label, $name, $value, "textarea", $args);
	}   

	/**
	 * Select dropdowns
	 * Supports 'options' which is a k,v array
	 */
	function select($label, $name, $value, $args){
		return $this->proxied($label, $name, $value, "select", $args);
	}   

	/**
	 * Multiselect
	 * Supports 'options' which is a k,v array
	 */
	function multiselect($label, $name, $value, $args){
		if(!strstr($name, "[]")) $name .= "[]";
		return $this->proxied($label, $name, $value, "multiselect", $args);
	}   

	/**
	 * Checkbox list
	 * Supports a multi-dimensional array  in order of label, name, value.
	 */
	function checks($label, $args){
		return $this->proxied($label, "", "", "checklist", $args);
	}   

	/**
	 * Boot Proxied
	 * This method is called from most of the other ones, just to keep things clean. 
	 * Handles building all the elements.
	 * Returns the element as a string.
	 */
	private function proxied($label, $name, $value, $type, $args){
		$opts = array_merge(array(
							"class"		=> "",
							"prepend"	=> "",
							"id"		=> "",
							"rows"		=> "3",
							"disabled"	=> false,
							"options"	=> array()
						), $args);

		if (empty($opts['id'])) :
			$opts['id'] = "boot_".$name;
		endif;
		
		$out = '<div class="clearfix'.($this->error($name) ? " error" : "").'">';
		$out .= '<label for="'.$opts['id'].'">'. $label .'</label><div class="input">';
		
		$classes = 'class="'. $opts['class'] . ($this->error($name)?" error":"") .'"';
		$id = empty($opts['id']) ? '' : 'id="'.$opts['id'].'"';	
		
		switch($type) :
			case "checklist":
				$out .= '<ul class="inputs-list">';
				foreach($opts['options'] as $o) :
					$out .= '<li><label><input type="checkbox" name="'.$o[1].'" value="'.$o[2].'"><span>'.$o[0].'</span></label></li>';
				endforeach;
				$out .= '</ul>';
				break;
			case "textarea":
				$rows = empty($opts['rows']) ? '' : 'rows="'.$opts['rows'].'"';	
				$out .= "<textarea name=\"$name\" $id $classes $rows>".$value."</textarea>";
				
				break;
			case "multiselect":
			case "select":
				$out .= "<select name=\"$name\" $id $classes". (($type=="multiselect")?"multiple=\"multiple\"":"") .">";
				foreach($opts['options'] as $k=>$v):
					$out .= "<option value=\"$k\"".(($value == $k)?" selected=\"selected\"":"") .">$v</option>";
				endforeach;
				$out .= "</select>";
				break;
			case "input":
			default:
				if(!empty($opts['prepend'])) $out .= '<div class="input-prepend"><span class="add-on">'.$opts['prepend'].'</span>';
				$out .= "<input name=\"$name\" $id $classes value=\"$value\" />";
				if(!empty($opts['prepend'])) $out .= '</div>';
				break;
		endswitch;
		
		$out .= '</div></div>';
		return $out;
	}
	
}