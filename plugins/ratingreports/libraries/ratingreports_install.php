<?php
/**
 * Performs install/uninstall methods for the incidentstatus plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author Jose Teneb
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Ratingreports_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the actionable plugin
	 */
	public function run_install()
	{
		// Creates the database tables
		$this->db->query('
			CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'rating_reports` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`user_id` int(11) unsigned DEFAULT \'0\',
					`incident_id` bigint(20) unsigned DEFAULT NULL,
					`rating_id` tinyint(4) DEFAULT \'0\',
					`rating_ip` varchar(100) DEFAULT NULL,
					`rating_date` datetime DEFAULT NULL,
					PRIMARY KEY (`id`),
					KEY `user_id` (`user_id`),
					KEY `incident_id` (`incident_id`),
					KEY `rating_id` (`rating_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');
		
		$this->db->query('
			CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'rating_options` (
					`id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
					`rating_title` varchar(255) DEFAULT NULL,
					`rating_description` text,
					`rating_visible` tinyint(4) NOT NULL DEFAULT \'1\',
					PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');		

	}

	/**
	 * Deletes the database tables
	 */
	public function uninstall()
	{
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'ratings_reports`');
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'ratings_options`');
		
	}
}
