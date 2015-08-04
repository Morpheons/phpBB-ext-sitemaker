<?php
/**
 *
 * @package sitemaker
 * @copyright (c) 2015 Daniel A. (blitze)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace blitze\sitemaker\tests\event;

use Symfony\Component\HttpFoundation\Response;
use blitze\sitemaker\event\listener;

class listener_base extends \phpbb_database_test_case
{
	public $request;

	/**
	* Define the extensions to be tested
	*
	* @return array vendor/name of extension(s) to test
	*/
	static protected function setup_extensions()
	{
		return array('blitze/sitemaker');
	}

	/**
	 * Load required fixtures.
	 *
	 * @return mixed
	 */
	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/user.xml');
	}

	/**
	 * Configure the test environment.
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		require_once dirname(__FILE__) . '/../../../../../includes/functions.php';
	}

	/**
	 * Create the listener object
	 *
	 * @return \blitze\sitemaker\event\listener
	 */
	protected function get_listener()
	{
		global $phpbb_dispatcher, $cache, $phpbb_root_path, $phpEx;

		$table_prefix = 'phpbb_';
		$blocks_table = $table_prefix . 'sm_blocks';
		$blocks_config_table = $table_prefix . 'sm_blocks_config';
		$block_routes_table = $table_prefix . 'sm_block_routes';

		$db = $this->new_dbal();
		$this->config = new \phpbb\config\config(array());
		$this->cache = $cache = new \phpbb\cache\service(new \phpbb\cache\driver\null, $this->config, $db, $phpbb_root_path, $phpEx);

		$this->user = $this->getMock('\phpbb\user', array(), array('\phpbb\datetime'));

		$this->user->expects($this->any())
			->method('lang')
			->willReturnCallback(function () {
				return implode(' ', func_get_args());
			});

		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();

		$auth = $this->getMock('\phpbb\auth\auth');

		$this->request = $this->getMock('\phpbb\request\request_interface');

		$this->container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');

		$this->container->expects($this->any())
			->method('has')
			->will($this->returnCallback(function($service_name) {
				return ($service_name === 'foo.bar.controller') ? true : false;
			}));

		$this->container->expects($this->any())
			->method('get')
			->will($this->returnCallback(function($service_name) {
				switch ($service_name)
				{
					case 'controller.helper':
						$controller_helper = $this->getMockBuilder('\phpbb\controller\helper')
							->disableOriginalConstructor()
							->getMock();

						$controller_helper->expects($this->any())
							->method('route')
							->willReturnCallback(function ($route, array $params = array()) {
								return $route . '#' . serialize($params);
							});
						return $controller_helper;

					case 'foo.bar.controller':
						$dummy = $this->getMockbuilder('stdClass')
							->setMockClassName('foo_bar_controller')
							->setMethods(array('handle'))
							->getMock();
						$dummy->expects($this->any())
							->method('handle')
							->willReturnCallback(function ($page) {
								return new Response('Viewing page: ' . $page);
							});
						return $dummy;
				}
			}));

		$this->sitemaker = $this->getMockBuilder('\blitze\sitemaker\services\util')
			->disableOriginalConstructor()
			->getMock();

		$this->blocks = $this->getMockBuilder('\blitze\sitemaker\services\blocks\display')
			->disableOriginalConstructor()
			->getMock();

		return new listener($this->cache, $this->config, $this->request, $this->container, $this->template, $this->user, $this->sitemaker, $this->blocks, $phpbb_root_path, $phpEx);
	}
}