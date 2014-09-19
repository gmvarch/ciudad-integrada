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

class obras {
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
		// Only add the events if we are on that controller
		if (Router::$controller == 'obras')
		{
			switch (Router::$method)
			{
				// Hook into the User Report view 
				case 'view':					
					Event::add('ushahidi_action.report_meta', array($this, '_obra_view'));
					break;
			}
		}
		
	}
	
	

	/**
	 * Render the Incident Status Information to the Report on the front end
	 */
	public function _obra_view()
	{
		$report = View::factory('report');
		$report->render(TRUE);		
	}
	
}

new obras;
