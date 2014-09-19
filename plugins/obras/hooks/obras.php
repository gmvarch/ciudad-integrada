<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Incident Status Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Marco Gnazzo
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class obraspublicas {
	/**
	 *  Registers the main event add method
	**/ 
	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	* Adds all the events to the main Ushahidi application	
	**/
	public function add()
	{

		
	}
	
	


	
}

new obraspublicas;
