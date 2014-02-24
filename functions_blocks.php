<?php
/**
 *
 * @package primetime
 * @copyright (c) 2013 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

function build_multi_select($option_ary, $selected_items, $key)
{
	global $user;

	$selected_items = explode(',', $selected_items);

	$html = '<select id="' . $key . '" name="config[' . $key . '][]" multiple="multiple">';
	foreach ($option_ary as $value => $title)
	{
		$title = (isset($user->lang[$title])) ? $user->lang[$title] : $title;
		$selected = (in_array($value, $selected_items)) ? ' selected="selected"' : '';
		$html .= '<option value="' . $value . '"' . $selected . '>' . $title . '</option>';
	}
	$html .= '</select>';

	return $html;
}

function build_checkbox($option_ary, $selected_items, $key)
{
	global $user;

	$selected_items = explode(',', $selected_items);
	$id_assigned = false;
	$html = '';

	foreach ($option_ary as $value => $title)
	{
		$selected = (in_array($value, $selected_items)) ? ' checked="checked"' : '';
		$title = (isset($user->lang[$title])) ? $user->lang[$title] : $title;
		$html .= '<label><input type="checkbox" name="config[' . $key . '][]"' . ((!$id_assigned) ? ' id="' . $key . '"' : '') . ' value="' . $value . '"' . $selected . (($key) ? ' accesskey="' . $key . '"' : '') . ' class="checkbox" /> ' . $title . '</label><br />';
		$id_assigned = true;
	}

	return $html;
}