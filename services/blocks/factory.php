<?php
/**
 *
 * @package primetime
 * @copyright (c) 2013 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace primetime\core\services\blocks;

class factory
{
	/** @var array */
	private $blocks;

	/**
	 * Constructor
	 */
	public function __construct($blocks)
	{
		$this->load_blocks($blocks);
	}

	/**
	 * Load available blocks
	 */
	public function load_blocks($blocks)
	{
		$this->blocks = array();
		foreach ($blocks as $service => $driver)
		{
			$this->blocks[$service] = $driver->get_name();
		}
	}

	/**
	 * Get available primetime blocks
	 */
	public function get_all_blocks()
	{
		return $this->blocks;
	}
}