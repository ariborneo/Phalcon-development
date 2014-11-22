<?php
namespace Libraries\CacheManagement;

/**
 * AwareInterface. Implementing rules necessary functionality for cache classes
 * @package Phalcon
 * @subpackage  Libraries\CacheManagement
 * @since PHP >=5.4
 * @version 1.0
 * @author Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @filesource apps/Libraries/CacheManagement/AwareInterface.php
 */
interface AwareInterface {

	/**
	 *  __construct(\Phalcon\Config $configuration) Init connection
	 * @param \Phalcon\Config $configuration Phalcon config
	 * @access public
	 */
	public function __construct(\Phalcon\Config $configuration);

	/**
	 * Get server information
	 * @return array
	 */
	public function getServerStatus();

	/**
	 * Get storage information
	 * @return array
	 */
	public function getStorageStatus();

	/**
	 * Get storage content
	 * @return array | mixed
	 */
	public function getPool();

	/**
	 * Invalidate all existing items
	 * @return boolean | null
	 */
	public function flushData();

	/**
	 * Set item to storage
	 * @param string $key
	 * @param mixed $value
	 * @return boolean | null
	 */
	public function setItem($key, $value);

	/**
	 * Delet item from storage
	 * @param string $key
	 * @return boolean | null
	 */
	public function deleteItem($key);

}