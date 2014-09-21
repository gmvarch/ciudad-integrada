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

class Obras_Controller extends Main_Controller {
	
	/**
	 * Whether an admin console user is logged in
	 * @var bool
	 */
	var $logged_in;

	public function __construct()
	{
		parent::__construct();

		// Is the Admin Logged In?
		//$this->logged_in = Auth::instance()->logged_in();
	}

	/**
	 * Displays all reports.
	 */
	public function index()
	{
		// Cacheable Controller
		//$this->is_cachable = TRUE;
		$this->template->header->header_block = $this->themes->header_block();
		$this->template->header->this_page = 'obras';		
		$this->template->content = new View('obras');
		$this->themes->js = new View('reports_js');

		//$this->template->header->page_title .= Kohana::lang('ui_main.reports').Kohana::config('settings.title_delimiter');

		// Store any exisitng URL parameters
		$this->themes->js->url_params = json_encode($_GET);

		// Enable the map
		$this->themes->map_enabled = TRUE;

		// Set the latitude and longitude
		$this->themes->js->latitude = Kohana::config('settings.default_lat');
		$this->themes->js->longitude = Kohana::config('settings.default_lon');
		$this->themes->js->default_map = Kohana::config('settings.default_map');
		$this->themes->js->default_zoom = Kohana::config('settings.default_zoom');

		// Get Default Color
		$this->themes->js->default_map_all = $this->template->content->default_map_all = Kohana::config('settings.default_map_all');
		
		// Get default icon
		$this->themes->js->default_map_all_icon = $this->template->content->default_map_all_icon = '';
		if (Kohana::config('settings.default_map_all_icon_id'))
		{
			$icon_object = ORM::factory('media')->find(Kohana::config('settings.default_map_all_icon_id'));
			$this->themes->js->default_map_all_icon = $this->template->content->default_map_all_icon = Kohana::config('upload.relative_directory')."/".$icon_object->media_thumb;
		}

		// Load the alert radius view
		$alert_radius_view = new View('alerts/radius');
		$alert_radius_view->show_usage_info = FALSE;
		$alert_radius_view->enable_find_location = FALSE;
		$alert_radius_view->css_class = "rb_location-radius";

		$this->template->content->alert_radius_view = $alert_radius_view;

		// Get locale
		$l = Kohana::config('locale.language.0');

		// Get the report listing view
		$obra_listing_view = $this->_get_obra_listing_view($l);

		// Set the view
		$this->template->content->obra_listing_view = $obra_listing_view;

		// Load the category
		$category_id = (isset($_GET['c']) AND intval($_GET['c']) > 0)? intval($_GET['c']) : 0;
		$category = ORM::factory('category', $category_id);

		if ($category->loaded)
		{
			// Set the category title
			$this->template->content->category_title = Category_Lang_Model::category_title($category_id,$l);
		}
		else
		{
			$this->template->content->category_title = "";
		}

		// Collect report stats
		$this->template->content->report_stats = new View('reports/stats');
		
		// Total Reports
		$total_reports = Incident_Model::get_total_reports(TRUE);

		// Get the date of the oldest report
		if (isset($_GET['s']) AND !empty($_GET['s']) AND intval($_GET['s']) > 0)
		{
			$oldest_timestamp =  intval($_GET['s']);
		}
		else
		{
			$oldest_timestamp = Incident_Model::get_oldest_report_timestamp();
		}

		// Get the date of the latest report
		if (isset($_GET['e']) AND !empty($_GET['e']) AND intval($_GET['e']) > 0)
		{
			$latest_timestamp = intval($_GET['e']);
		}
		else
		{
			$latest_timestamp = Incident_Model::get_latest_report_timestamp();
		}

		// Round the number of days up to the nearest full day
		$days_since = ceil((time() - $oldest_timestamp) / 86400);
		$avg_reports_per_day = ($days_since < 1)? $total_reports : round(($total_reports / $days_since),2);

		// Percent Verified
		$total_verified = Incident_Model::get_total_reports_by_verified(TRUE);
		$percent_verified = ($total_reports == 0) ? '-' : round((($total_verified / $total_reports) * 100),2).'%';

		// Category tree view
		$this->template->content->category_tree_view = category::get_category_tree_view();

		// Additional view content
		/*
		$this->template->content->custom_forms_filter = new View('submit_custom_forms');
		$this->template->content->custom_forms_filter->disp_custom_fields = customforms::get_custom_form_fields();
		$this->template->content->custom_forms_filter->search_form = TRUE;
		*/
		$this->template->content->oldest_timestamp = $oldest_timestamp;
		$this->template->content->latest_timestamp = $latest_timestamp;
		$this->template->content->report_stats->total_reports = $total_reports;
		$this->template->content->report_stats->avg_reports_per_day = $avg_reports_per_day;
		$this->template->content->report_stats->percent_verified = $percent_verified;
		$this->template->content->services = Service_Model::get_array();
		
	}

	/**
	 * Helper method to load the report listing view
	 */
	private function _get_obra_listing_view($locale = '')
	{
		// Check if the local is empty
		if (empty($locale))
		{
			$locale = Kohana::config('locale.language.0');
		}

		// Load the report listing view
		$obra_listing = new View('obras_list');

		// Fetch all incidents
		$incidents = obras::fetch_incidents(TRUE);

		// Pagination
		$pagination = obras::$pagination;

		// For compatibility with older custom themes:
		// Generate array of category titles with their proper localizations using an array
		// DO NOT use this in new code, call Category_Lang_Model::category_title() directly
		foreach(Category_Model::categories() as $category)
		{
			$localized_categories[$category['category_title']] = Category_Lang_Model::category_title($category['category_id']);
		}

		// Set the view content
		$obra_listing->incidents = $incidents;
		$obra_listing->localized_categories = $localized_categories;

		//Set default as not showing pagination. Will change below if necessary.
		$obra_listing->pagination = "";

		// Pagination and Total Num of Report Stats
		$plural = ($pagination->total_items == 1)? "" : "s";

		// Set the next and previous page numbers
		$obra_listing->next_page = $pagination->next_page;
		$obra_listing->previous_page = $pagination->previous_page;

		if ($pagination->total_items > 0)
		{
			$current_page = ($pagination->sql_offset / $pagination->items_per_page) + 1;
			$total_pages = ceil($pagination->total_items / $pagination->items_per_page);

			if ($total_pages >= 1)
			{
				$obra_listing->pagination = $pagination;

				// Show the total of report
				// @todo This is only specific to the frontend reports theme
				$obra_listing->stats_breadcrumb = $pagination->current_first_item.'-'
											. $pagination->current_last_item.' of '.$pagination->total_items.' '
											. Kohana::lang('ui_main.reports');
			}
			else
			{ 
				// If we don't want to show pagination
				$obra_listing->stats_breadcrumb = $pagination->total_items.' '.Kohana::lang('ui_admin.reports');
			}
		}
		else
		{
			$obra_listing->stats_breadcrumb = '('.$pagination->total_items.' report'.$plural.')';
		}

		// Return
		return $obra_listing;
	}

	public function fetch_reports()
	{
		$this->template = "";
		$this->auto_render = FALSE;
		
		$obra_listing_view = $this->_get_obra_listing_view();
		print $obra_listing_view;
	}

	 /**
	 * Displays a report.
	 * @param boolean $id If id is supplied, a report with that id will be
	 * retrieved.
	 */
	public function view($id = FALSE)
	{
		$this->template->header->this_page = 'obras';
		$this->template->content = new View('obra_detail');

	
		// Sanitize the report id before proceeding
		$id = intval($id);

		if ($id > 0 AND Incident_Model::is_valid_incident($id,TRUE))
		{
			$incident = ORM::factory('incident')
				->where('id',$id)
				->where('incident_active',1)
				->find();
				
			// Not Found
			if ( ! $incident->loaded) 
			{
				url::redirect('obras/view/');
			}

			// Comment Post?
			// Setup and initialize form field names


			// Filters
			$incident_title = $incident->incident_title;
			$incident_description = $incident->incident_description;
			Event::run('ushahidi_filter.report_title', $incident_title);
			Event::run('ushahidi_filter.report_description', $incident_description);

			//$this->template->header->page_title .= $incident_title.Kohana::config('settings.title_delimiter');

			// Add Features
			$this->template->content->features_count = $incident->geometry->count();
			$this->template->content->features = $incident->geometry;
			$this->template->content->incident_id = $incident->id;
			$this->template->content->incident_title = $incident_title;
			$this->template->content->incident_description = $incident_description;
			$this->template->content->incident_location = $incident->location->location_name;
			$this->template->content->incident_latitude = $incident->location->latitude;
			$this->template->content->incident_longitude = $incident->location->longitude;
			$this->template->content->incident_date = date('M j Y', strtotime($incident->incident_date));
			$this->template->content->incident_time = date('H:i', strtotime($incident->incident_date));
			$this->template->content->incident_category = $incident->incident_category;

			// Incident rating
			//$this->template->content->incident_rating = $this->_get_rating($incident->id, 'original');

			// Retrieve Media
			$incident_news = array();
			$incident_video = array();
			$incident_photo = array();

			foreach ($incident->media as $media)
			{
				if ($media->media_type == 4)
				{
					$incident_news[] = $media->media_link;
				}
				elseif ($media->media_type == 2)
				{
					$incident_video[] = $media->media_link;
				}
				elseif ($media->media_type == 1)
				{
					$incident_photo[] = array(
						'large' => url::convert_uploaded_to_abs($media->media_link),
						'thumb' => url::convert_uploaded_to_abs($media->media_thumb)
						);
				}
			}

			$this->template->content->incident_verified = $incident->incident_verified;

		}
		else
		{
			url::redirect('obras');
		}

		// Add Neighbors
		$this->template->content->incident_neighbors = Incident_Model::get_neighbouring_incidents($id, TRUE, 0, 5);

		// News Source links
		$this->template->content->incident_news = $incident_news;


		// Video links
		$this->template->content->incident_videos = $incident_video;

		// Images
		$this->template->content->incident_photos = $incident_photo;

		// Create object of the video embed class
		$video_embed = new VideoEmbed();
		$this->template->content->videos_embed = $video_embed;

		// Javascript Header
		$this->themes->map_enabled = TRUE;
		$this->themes->photoslider_enabled = TRUE;
		$this->themes->validator_enabled = TRUE;
		$this->themes->js = new View('view_js');
		$this->themes->js->incident_id = $incident->id;
		$this->themes->js->default_map = Kohana::config('settings.default_map');
		$this->themes->js->default_zoom = Kohana::config('settings.default_zoom');
		$this->themes->js->latitude = $incident->location->latitude;
		$this->themes->js->longitude = $incident->location->longitude;
		$this->themes->js->incident_zoom = $incident->incident_zoom;
		$this->themes->js->incident_photos = $incident_photo;

		// Initialize custom field array
		$this->template->content->custom_forms = new View('detail_custom_forms');
		$form_field_names = customforms::get_custom_form_fields($id, $incident->form_id, FALSE, "view");
		$this->template->content->custom_forms->form_field_names = $form_field_names;

		// If the Admin is Logged in - Allow for an edit link
		$this->template->content->logged_in = $this->logged_in;
	}

	
	
}
