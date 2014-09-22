<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This controller is used to list/ view and edit reports
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Rating_Reports_Controller extends Main_Controller {
	
	/**
	 * Whether an admin console user is logged in
	 * @var bool
	 */
	var $logged_in;

	public function __construct()
	{
		parent::__construct();
		// Is the Admin Logged In?
		$this->logged_in = Auth::instance()->logged_in();
	}

	/**
	 * Displays all reports.
	 */
	public function index()
	{
		// Cacheable Controller
		$this->is_cachable = TRUE;
		$this->template->header->this_page = 'rating_reports';
		$this->template->content = new View('rating_reports');	
		$this->template->user = $this->user->id;
	}


	/**
	 * Report monitorear.
	 * @param boolean $id If id is supplied, a rating will be applied to selected report
	 */
	public function monitorear($id = false)
	{
		$this->template = "";
		$this->auto_render = FALSE;

		if (!$id)
		{
			echo json_encode(array("status"=>"error", "message"=>"ERROR!"));
		}
		else
		{
			$action = $_POST['cat'];

			// Has this User or IP Address rated this post before?
			if ($this->user)
			{
				$filter = array("user_id" => $this->user->id);
			}
			else
			{
				$filter = array("rating_ip" => $_SERVER['REMOTE_ADDR']);
			}

			$previous = ORM::factory('rating')
					->where('incident_id',$id)
					->where($filter)
					->find();



			// If previous exits... update previous vote
			$rating = new Rating_reports_Model($previous->id);

			$rating->incident_id = $id;

			// Is there a user?
			if ($this->user)
			{
				$rating->user_id = $this->user->id;

				if ($rating->incident->user_id == $this->user->id)
				{
					echo json_encode(array("status"=>"error", "message"=>"Can't rate your own Reports!"));
					exit;
				}

			}

			$rating->rating_id = $action;
			$rating->rating_ip = $_SERVER['REMOTE_ADDR'];
			$rating->rating_date = date("Y-m-d H:i:s",time());
			$rating->save();



			echo json_encode(array("status"=>"saved", "message"=>"SAVED!"));


		}
	}

}
