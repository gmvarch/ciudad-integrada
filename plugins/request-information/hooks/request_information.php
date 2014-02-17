<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Download Reports Hook.
 * This hook will take care of adding a link in the nav_main_top section.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */


// Hook into the nav main_top

class requestinformation {

public function __construct()
	{	
		
		Event::add('ushahidi_action.nav_main_top', array($this, 'add'));
          
	}	

public function add()
	{	
		// Add plugin link to nav_main_top		
		echo "<li><a href='" . url::site() . "request_information'>" . strtoupper(Kohana::lang('ui_main.request_information')) . "</a></li>";
		
	}

}
new requestinformation();


