<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Download Reports Controller.
 * This controller will take care of downloading reports.
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author Marco Gnazzo
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */


class How_Controller extends Main_Controller
{
	function __construct()
	{
        	parent::__construct();
   	}

	function index()
	{
		
		$this->template->this_page = 'how';
		$this->template->header->header_block = $this->themes->header_block();
		$this->template->header->this_page ='how';
   		$this->template->content = new View('main');
		$this->template->content->title = Kohana::lang('Como Funciona');
		

	}
}

?>
