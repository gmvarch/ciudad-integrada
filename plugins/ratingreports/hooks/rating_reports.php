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
		if ( isset(Auth::instance()->get_user()->id) )
		{
			$this->user_id = Auth::instance()->get_user()->id;
		} else {
			$this->user_id = 0;
		}
		
	}
	
	/**
	* Adds all the events to the main Ushahidi application	
	**/
	public function add()
	{		
		// Only add the events if we are on that controller
		if (Router::$controller == 'obras' AND Router::$method == 'view')
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
		$report->incident_id = $id;		
		$report->user =  $this->user_id;		
		$report->user_vote = $this->_get_vote($id, $this->user_id);
		$report->ratings = $this->_get_categories_ratings();
		$report->monitoreos = $this->_get_report_ratings($id);
		$report->render(true);		
	}
	

	public function _get_vote($id = FALSE, $user = FALSE)
	{
		

		$result = ORM::factory("rating_reports")
			->where("incident_id", $id)
			->where("user_id", $user)
			->find_all();

		if ($result->count() == 0) {
			return 0 ;
		} else { 
			return 1;
		}
	}

	/**
	 * Retrieves Total Rating For Specific Post
	 * Also Updates The Incident & Comment Tables (Ratings Column)
	 */
	private function _get_report_ratings($id = FALSE)
	{
		
		if (empty($id))
			return 0;
					
		$monitoreos = [];
		$result = FALSE;		


		$result = ORM::factory("rating_reports")
			->select("rating_id, COUNT( * ) as cant")
			->where("incident_id", $id)
			->groupby("rating_id")
			->find_all();
		//$result = $this->db->query('SELECT rating_id, COUNT( * ) as count_rating FROM '.$this->table_prefix.'rating_reports WHERE incident_id = ? GROUP BY rating_id', $id);
			
		//if ($result->count() == 0 OR $result->current()->monitoreos == NULL) return 0;
		foreach ($result as $result_cat) {
			$title = $this->_get_category_ranking($result_cat->rating_id);
			$monitoreos[$title] = $result_cat->cant;
		}
		//$monitoreos = $result->current()->monitoreos;
		
		return $monitoreos;
	}
	
	private function _get_category_ranking($id = FALSE)
	{
		$result = ORM::factory("rating_options")
					->where("id", $id)
					->find();

		return $result->rating_title;
	}

	private function _get_categories_ratings()
	{
		return ORM::factory("rating_options")->find_all();
	}

	
}

new rating_reports;
