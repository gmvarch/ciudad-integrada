<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Caminos de la Villa Hook - Load All Events
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

class layercv {
	
	private $layers;
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{		
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		Event::add('ushahidi_filter.map_base_layers', array($this, '_add_layer'));
		if (Router::$controller != 'settings')
		{
			Event::add('ushahidi_filter.map_layers_js', array($this, '_add_map_layers_js'));
		}
	}
	
	public function _add_layer()
	{
		$this->layers = Event::$data;
		$this->layers = $this->_create_layer();
		

		Event::$data = $this->layers;
	}
	
	public function _create_layer()
	{

		// MapBoxStreets
		$layer = new stdClass();
		$layer->active = TRUE;
		$layer->name = 'mapbox_streets';
		$layer->openlayers = "XYZ";
		$layer->title = 'Mapbox Streets';
		$layer->description = '.';
		$layer->api_url = '';
		$layer->data = array(
			'baselayer' => TRUE,
			'attribution' => '',
			'url' => 'http://a.tiles.mapbox.com/v3/jose23.map-k9zj1kfd/${z}/${x}/${y}.png',
			//http://{switch:a,b,c,d}.tile.openstreetmap.de/tiles/osmde/{zoom}/{x}/{y}.png
			//'url' => 'http://b.tile.openstreetmap.de/tiles/osmde/${z}/${x}/${y}.png',			
			'type' => ''
		);
		$this->layers[$layer->name] = $layer;

		$layer = new stdClass();
		$layer->active = TRUE;
		$layer->name = 'caminos_de_villa_streets';
		$layer->openlayers = "XYZ";
		$layer->title = 'Caminos de la Villa Streets';
		$layer->description = '.';
		$layer->numZoomLevels = '17';
		$layer->api_url = '';
		$layer->data = array(
			'baselayer' => TRUE,
			'attribution' => '',
			'url' => 'http://caminosdelavilla.org/tiles/${z}/${x}/${y}.png',	
			'type' => ''
		);
		$this->layers[$layer->name] = $layer;
		
		
		return $this->layers;
	}

	function _add_map_layers_js ()
	{
		$js = Event::$data;
		
		// Next get the default base layer
		$default_map = Kohana::config('settings.default_map');

		Event::$data = $js;
	}
}

new layercv;
