<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2015 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\model\menus\collections;

use blitze\sitemaker\model\base_collection;

class menus extends base_collection
{
	protected $entity_class = 'blitze\sitemaker\model\menus\entity\menu';
}
