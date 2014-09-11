<?php 
/**
 * Reports Helper class.
 *
 * This class holds functions used for new report submission from both the backend and frontend.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @category   Helpers
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
class obras_Core {
	
	/**
	 * Maintains the list of parameters used for fetching incidents
	 * in the fetch_incidents method
	 * @var array
	 */
	public static $params = array();
	
	/**
	 * Pagination object user in fetch_incidents method
	 * @var Pagination
	 */
	public static $pagination = array();
	
	/**
	 * Helper function to fetch and optionally paginate the list of 
	 * incidents/reports via the Incident Model using one or all of the 
	 * following URL parameters
	 *	- category
	 *	- location bounds
	 *	- incident mode
	 *	- media
	 *	- location radius
	 *
	 * @param bool $paginate Optionally paginate the incidents - Default is FALSE
	 * @param int $items_per_page No. of items to show per page
	 * @return Database_Result
	 */
	public static function fetch_incidents($paginate = FALSE, $items_per_page = 0)
	{
		// Reset the paramters
		self::$params = array();
		
		// Initialize the category id
		$category_id = 0;
		
		$table_prefix = Kohana::config('database.default.table_prefix');
		
		// Fetch the URL data into a local variable
		$url_data = $_GET;
		
		// Split selected parameters on ","
		// For simplicity, always turn them into arrays even theres just one value
		$exclude_params = array('c', 'v', 'm', 'mode', 'sw', 'ne', 'start_loc');
		foreach ($url_data as $key => $value)
		{
			if (in_array($key, $exclude_params) AND ! is_array($value))
			{
				$url_data[$key] = explode(",", $value);
			}
		}
		
		
		/*
		//> BEGIN PARAMETER FETCH
		// 
		// Check for the category parameter
		// 
		if (isset($url_data['c']) AND is_array($url_data['c']))
		{
			// Sanitize each of the category ids
			$category_ids = array();
			foreach ($url_data['c'] as $c_id)
			{
				if (intval($c_id) > 0)
				{
					$category_ids[] = intval($c_id);
				}
			}
			
			// Check if there are any category ids
			if (count($category_ids) > 0)
			{
				$category_ids = implode(",", $category_ids);
			
				array_push(self::$params,
					'(c.id IN ('.$category_ids.') OR c.parent_id IN ('.$category_ids.'))',
					'c.category_visible = 1'
				);
			}
		}
		
		// 
		// Incident modes
		// 
		if (isset($url_data['mode']) AND is_array($url_data['mode']))
		{
			$incident_modes = array();
			
			// Sanitize the modes
			foreach ($url_data['mode'] as $mode)
			{
				if (intval($mode) > 0)
				{
					$incident_modes[] = intval($mode);
				}
			}
			
			// Check if any modes exist and add them to the parameter list
			if (count($incident_modes) > 0)
			{
				array_push(self::$params, 
					'i.incident_mode IN ('.implode(",", $incident_modes).')'
				);
			}
		}
		
		// 
		// Location bounds parameters
		// 
		if (isset($url_data['sw']) AND isset($url_data['ne']))
		{
			$southwest = $url_data['sw'];
			$northeast = $url_data['ne'];
			
			if ( count($southwest) == 2 AND count($northeast) == 2 )
			{
				$lon_min = (float) $southwest[0];
				$lon_max = (float) $northeast[0];
				$lat_min = (float) $southwest[1];
				$lat_max = (float) $northeast[1];
			
				// Add the location conditions to the parameter list
				array_push(self::$params, 
					'l.latitude >= '.$lat_min,
					'l.latitude <= '.$lat_max,
					'l.longitude >= '.$lon_min,
					'l.longitude <= '.$lon_max
				);
			}
		}
		
		// 
		// Location bounds - based on start location and radius
		// 
		if (isset($url_data['radius']) AND isset($url_data['start_loc']))
		{
			//if $url_data['start_loc'] is just comma delimited strings, then make it into an array
			if (intval($url_data['radius']) > 0 AND is_array($url_data['start_loc']))
			{
				$bounds = $url_data['start_loc'];
				if (count($bounds) == 2 AND is_numeric($bounds[0]) AND is_numeric($bounds[1]))
				{
					self::$params['radius'] = array(
						'distance' => intval($url_data['radius']),
						'latitude' => $bounds[0],
						'longitude' => $bounds[1]
					);
				}
			}
		}
		
		// 
		// Check for incident date range parameters
		// 
		if (!empty($url_data['from']))
		{
			// Add hours/mins/seconds so we still get reports if from and to are the same day
			$date_from = date('Y-m-d 00:00:00', strtotime($url_data['from']));
			
			array_push(self::$params, 
				'i.incident_date >= "'.$date_from.'"'
			);
		}
		if (!empty($url_data['to']))
		{
			// Add hours/mins/seconds so we still get reports if from and to are the same day
			$date_to = date('Y-m-d 23:59:59', strtotime($url_data['to']));
			
			array_push(self::$params, 
				'i.incident_date <= "'.$date_to.'"'
			);
		}
		
		// Additional checks for date parameters specified in timestamp format
		// This only affects those submitted from the main page
		
		// Start Date
		if (isset($_GET['s']) AND intval($_GET['s']) > 0)
		{
			$start_date = intval($_GET['s']);
			array_push(self::$params, 
				'i.incident_date >= "'.date("Y-m-d H:i:s", $start_date).'"'
			);
		}

		// End Date
		if (isset($_GET['e']) AND intval($_GET['e']))
		{
			$end_date = intval($_GET['e']);
			array_push(self::$params, 
				'i.incident_date <= "'.date("Y-m-d H:i:s", $end_date).'"'
			);
		}
		
		// 
		// Check for media type parameter
		// 
		if (isset($url_data['m']) AND is_array($url_data['m']))
		{
			// An array of media filters has been specified
			// Validate the media types
			$media_types = array();
			foreach ($url_data['m'] as $media_type)
			{
				if (intval($media_type) > 0)
				{
					$media_types[] = intval($media_type);
				}
			}
			if (count($media_types) > 0)
			{
				array_push(self::$params, 
					'i.id IN (SELECT DISTINCT incident_id FROM '
						.$table_prefix.'media WHERE media_type IN ('.implode(",", $media_types).'))'
				);
			}
			
		}
		
		// 
		// Check if the verification status has been specified
		// 
		if (isset($url_data['v']) AND is_array($url_data['v']))
		{
			$verified_status = array();
			foreach ($url_data['v'] as $verified)
			{
				if (intval($verified) >= 0)
				{
					$verified_status[] = intval($verified);
				}
			}
			
			if (count($verified_status) > 0)
			{
				array_push(self::$params, 
					'i.incident_verified IN ('.implode(",", $verified_status).')'
				);
			}
		}
		*/
		
		//
		// Check if they're filtering over custom form fields
		//
		if (isset($url_data['cff']) AND is_array($url_data['cff']))
		{
			$where_text = "";
			$i = 0;
			foreach ($url_data['cff'] as $field)
			{			
				$field_id = $field[0];
				if (intval($field_id) < 1)
					continue;

				$field_value = $field[1];
				if (is_array($field_value))
				{
					$field_value = implode(",", $field_value);
				}
								
				$i++;
				if ($i > 1)
				{
					$where_text .= " OR ";
				}
				
				$where_text .= "(form_field_id = ".intval($field_id)
					. " AND form_response = '".Database::instance()->escape_str(trim($field_value))."')";
			}
			
			// Make sure there was some valid input in there
			if ($i > 0)
			{
				// Get the valid IDs - faster in a separate query as opposed
				// to a subquery within the main query
				$db = new Database();

				$rows = $db->query('SELECT DISTINCT incident_id FROM '
				    .$table_prefix.'form_response WHERE '.$where_text);
				
				$incident_ids = '';
				foreach ($rows as $row)
				{
					if ($incident_ids != '')
					{
							$incident_ids .= ',';
					}

					$incident_ids .= $row->incident_id;
				}
				//make sure there are IDs found
				if ($incident_ids != '')
				{
					array_push(self::$params, 'i.id IN ('.$incident_ids.')');
				}
				else
				{
					array_push(self::$params, 'i.id IN (0)');
				}
			}
			
		} // End of handling cff
		
		// In case a plugin or something wants to get in on the parameter fetching fun
		Event::run('ushahidi_filter.fetch_incidents_set_params', self::$params);
		
		//> END PARAMETER FETCH
		array_push(self::$params,
					'(c.id IN (13) OR c.parent_id IN (13))',
					'c.category_visible = 1'
				);
		/*
		 * array_push(self::$params,
					'(c.id IN (2) OR c.parent_id IN (2))',
					'c.category_visible = 0'
				);
		 * */
		// Check for order and sort params
		$order_field = NULL; $sort = NULL;
		$order_options = array(
			'title' => 'i.incident_title',
			'date' => 'i.incident_date',
			'id' => 'i.id'
		);
		if (isset($url_data['order']) AND isset($order_options[$url_data['order']]))
		{
			$order_field = $order_options[$url_data['order']];
		}
		if (isset($url_data['sort']))
		{
			$sort = (strtoupper($url_data['sort']) == 'ASC') ? 'ASC' : 'DESC';
		}
		
		if ($paginate)
		{
			// Fetch incident count
			$incident_count = Incident_Model::get_incidents(self::$params, false, $order_field, $sort, TRUE);
			
			// Set up pagination
			$page_limit = (intval($items_per_page) > 0)
			    ? $items_per_page 
			    : intval(Kohana::config('settings.items_per_page'));
					
			$total_items = $incident_count->current()
					? $incident_count->current()->report_count
					: 0;
			
			$pagination = new Pagination(array(
					'style' => 'front-end-reports',
					'query_string' => 'page',
					'items_per_page' => $page_limit,
					'total_items' => $total_items
				));
			
			Event::run('ushahidi_filter.pagination',$pagination);
			
			self::$pagination = $pagination;
			
			// Return paginated results
			return Incident_Model::get_incidents(self::$params, self::$pagination, $order_field, $sort);
		}
		else
		{
			// Return
			return Incident_Model::get_incidents(self::$params, false, $order_field, $sort);;
		}
	}	
}
?>
