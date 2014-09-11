<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Incident Status Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Jose Teneb
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class rating_reports {
	/**
	 *  Registers the main event add method
	**/ 
	var $logged_in;
	
	public function __construct()
	{				
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
		$this->logged_in = Auth::instance()->logged_in();
		
	}
	
	/**
	* Adds all the events to the main Ushahidi application	
	**/
	public function add()
	{

		
			// Only add the events if we are on that controller
		if (Router::$controller == 'reports' AND Router::$method == 'view')
		{
			Event::add('ushahidi_action.report_meta', array($this, '_report_view'));
		}	
	}	
	
	
	
	/**
	 * Render the Incident Status Information to the Report on the front end
	 */
	public function _report_view()
	{
		$id = Event::$data;
		if ($id) {}
		else {}
		$report = View::factory('rating_report');	
		$report->logged_in = $this->logged_in;	
		$report->user_vote = 0;
		$report->render(true);		
	}
	
	/**
	 * Retrieves Total Rating For Specific Post
	 * Also Updates The Incident & Comment Tables (Ratings Column)
	 */
	private function _get_report_ratings($id = FALSE)
	{
		if (empty($id))
			return 0;
					
		$total_rating = 0;
		$results = FALSE;		

		$result = $this->db->query('SELECT SUM(rating) as total_rating FROM '.$this->table_prefix.'rating WHERE incident_id = ?', $id);
			
		if ($result->count() == 0 OR $result->current()->total_rating == NULL) return 0;
		
		$total_rating = $result->current()->total_rating;
		
		return $total_rating;
	}
	

	
}

new rating_reports;
