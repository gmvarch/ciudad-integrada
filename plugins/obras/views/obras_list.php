<?php
/**
 * View file for updating the reports display
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team - http://www.ushahidi.com
 * @package    Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */
?>
		<!-- Top reportbox section-->
		<div class="row button-filter">
			<div class="col-md-4 col-xs-12 filter">
			  	<div class="btn-group link-toggle report-list-toggle lt-icons-and-text">
		            <a href="#rb_list-view" class="btn btn-default btn-sm list">
		            	<span class="glyphicon glyphicon-th-list"></span> <?php echo Kohana::lang('ui_main.list'); ?>
		            </a> 
		            <a href="#rb_map-view" class="btn btn-default btn-sm map">
		           		<span class="glyphicon glyphicon-globe"></span> <?php echo Kohana::lang('ui_main.map'); ?>
		            </a>
		        </div>
			</div>
			<div class="col-md-8 col-xs-12 filter">
				<?php //echo $pagination; ?>
				<?php//  echo $stats_breadcrumb; ?>

				<div class="pagina btn-group pull-right link-toggle lt-icons-only">
					<?php //@todo Toggle the status of these links depending on the current page ?>
					<a href="#page_<?php echo $previous_page; ?>" class="prev btn btn-default btn-sm">&laquo; <?php echo Kohana::lang('ui_main.previous'); ?></a>
					<a href="#page_<?php echo $next_page; ?>" class="next btn btn-default btn-sm"><?php echo Kohana::lang('ui_main.next'); ?> &raquo;</a>
				</div>
			</div>		
		</div>
		<!-- /Top reportbox section-->
		
		<!-- Report listing -->
		<div class="r_cat_tooltip"><a href="#" class="r-3"></a></div>
		<div class="rb_list-and-map-box row related">
			<div id="rb_list-view" class="col-md-12 col-xs-12 lista-reportes" style="padding:0 15px ;">
			<div class="wg-list-article">
				<ul>
				<?php
					foreach ($incidents as $incident)
					{
						$incident_id = $incident->incident_id;
						$incident_title = $incident->incident_title;
						$incident_description = $incident->incident_description;
						$incident_url = 'obras/view/'.$incident_id;
						//$incident_category = $incident->incident_category;
						// Trim to 150 characters without cutting words
						// XXX: Perhaps delcare 150 as constant

						$incident_description = text::limit_chars(html::strip_tags($incident_description), 140, "...", true);
						$incident_date = date('H:i M d, Y', strtotime($incident->incident_date));
						//$incident_time = date('H:i', strtotime($incident->incident_date));
						$location_id = $incident->location_id;
						$location_name = $incident->location_name;
						$incident_verified = $incident->incident_verified;

						if ($incident_verified)
						{
							$incident_verified = '<span class="r_verified">'.Kohana::lang('ui_main.verified').'</span>';
							$incident_verified_class = "verified";
						}
						else
						{
							$incident_verified = '<span class="r_unverified">'.Kohana::lang('ui_main.unverified').'</span>';
							$incident_verified_class = "unverified";
						}

						$comment_count = ORM::Factory('comment')->where('incident_id', $incident_id)->count_all();

						$incident_thumb = url::file_loc('img')."media/img/report-thumb-default.jpg";
						$media = ORM::Factory('media')->where('incident_id', $incident_id)->find_all();
						if ($media->count())
						{
							foreach ($media as $photo)
							{
								if ($photo->media_thumb)
								{ // Get the first thumb
									$incident_thumb = url::convert_uploaded_to_abs($photo->media_thumb);
									break;
								}
							}
						} else {
							$categories = ORM::Factory('category')
										->join('incident_category', 'category_id', 'category.id')
										->where('incident_id', $incident_id)
										->limit('1')
									->find_all();
							foreach ($categories as $category):
								if ($category->id == '1'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-infraestructura2.png";	
								elseif ($category->id == '6'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-infraestructura2.png";
								elseif ($category->id == '7'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-agua2.png";
								elseif ($category->id == '8'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-cloacas2.png";
								elseif ($category->id == '9'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-luz2.png";
								elseif ($category->id == '10'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-salud2.png";
								elseif ($category->id == '11'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-calles2.png";
								elseif ($category->id == '12'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-basura2.png";
								elseif ($category->id == '13'): 
									$incident_thumb = url::file_loc('images')."themes/ci-theme/images/iconos/icon-obras2.png";
								else:
								endif; 
							endforeach;						 
						}						
					?>
					<li id="incident_<?php echo $incident_id ?>" class="<?php //echo $incident_verified_class; ?>">
						<div class="col-md-2 hidden-xs thumbnail" style="">
							<a href="<?php echo $incident_url; ?>">
								<img alt="<?php echo html::escape($incident_title); ?>" style="height:90px;" height="90" width="110" src="<?php echo $incident_thumb; ?>" /> 
							</a>
							

							<!-- Only show this if the report has a video 
							<p class="r_video" style="display:none;"><a href="#"><?php echo Kohana::lang('ui_main.video'); ?></a></p>
							-->

							<!-- Category Selector -->
							<div class="r_categories">
								<h4><?php echo Kohana::lang('ui_main.categories'); ?></h4>
								<?php
								$categories = ORM::Factory('category')->join('incident_category', 'category_id', 'category.id')->where('incident_id', $incident_id)->find_all();
								foreach ($categories as $category): ?>
									
									<?php // Don't show hidden categories ?>									
							
									<?php if ($category->category_image_thumb): ?>
										<?php $category_image = url::site(Kohana::config('upload.relative_directory')."/".$category->category_image_thumb); ?>
										<a class="r_category" href="<?php echo url::site("reports/?c=$category->id") ?>">
											<span class="r_cat-box"><img src="<?php echo $category_image; ?>" height="16" width="16" /></span> 
											<span class="r_cat-desc"><?php echo Category_Lang_Model::category_title($category->id); ?></span>
										</a>
									<?php else:	?>
										<a class="r_category" href="<?php echo url::site("reports/?c=$category->id") ?>">
											<span class="r_cat-box" style="background-color:#<?php echo $category->category_color;?>;"></span> 
											<span class="r_cat-desc"><?php echo Category_Lang_Model::category_title($category->id); ?></span>
										</a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<?php
							// Action::report_extra_media - Add items to the report list in the media section
							Event::run('ushahidi_action.report_extra_media', $incident_id);
							?>
						</div>

						<div class="col-md-10 col-xs-12 info">
							<h4 class="upper bold-italic">
								<a class="r_title" href="<?php echo $incident_url; ?>">
									<?php echo html::escape($incident_title); ?>
								</a>
								<small class="italic">
									<?php echo html::specialchars($location_name); ?>
								</small>
								<!--<a href="<?php echo "$incident_url#discussion"; ?>" class="r_comments">
									<?php //echo $comment_count; ?></a> -->
									<?php //echo $incident_verified; ?>
							</h4>
							<p>
								<?php echo $incident_description; ?>  
								<a class="btn-show btn-more" href="#incident_<?php echo $incident_id ?>"><?php echo Kohana::lang('ui_main.more_information'); ?> &raquo;</a> 
								<a class="btn-show btn-less" href="#incident_<?php echo $incident_id ?>">&laquo; <?php echo Kohana::lang('ui_main.less_information'); ?></a> 
							</p>
							<div class="row">
								<div class="col-sm-6 col-xs-6">
									<a href="<?php echo $incident_url; ?>" type="button" class="btn btn-success">Más Info</a>
								</div>
								<div class="col-sm-6 col-xs-6">
									<div class="row">
										<div class="col-sm-6 col-xs-6">
											<?php 
												//echo $incident_verified;
											?>
										</div>
										<div class="col-sm-6 col-xs-6">
											<p class="r_date r-3 bottom-cap"><span class="glyphicon glyphicon-time"></span> <?php echo $incident_date; ?></p>
										</div>
									</div>
								</div>
							</div>	
							
							<?php
							// Action::report_extra_details - Add items to the report list details section
							Event::run('ushahidi_action.report_extra_details', $incident_id);
							?>
						</div>
					</li>
				<?php } ?>
				</ul>
			</div>
			</div>
			<div id="rb_map-view" style="padding:40px 15px 40px 15px !important;">
			</div>
		</div>
		<!-- /Report listing -->
		
		<!-- Bottom paginator -->
		<div class="row ">
			<div class="col-md-4 col-xs-12">
			  	<div class="btn-group link-toggle report-list-toggle lt-icons-and-text">
		            <a href="#rb_list-view" class="btn btn-default btn-sm">
		            	<span class="glyphicon glyphicon-th-list"></span> <?php echo Kohana::lang('ui_main.list'); ?>
		            </a> 
		            <a href="#rb_map-view" class="btn btn-default btn-sm">
		           		<span class="glyphicon glyphicon-globe"></span> <?php echo Kohana::lang('ui_main.map'); ?>
		            </a>
		        </div>
			</div>
			<div class="col-md-8 col-xs-12 filter">
				<?php // echo $pagination; ?>
				<?php//  echo $stats_breadcrumb; ?>

				<div class="pagina btn-group pull-right link-toggle lt-icons-only">
					<?php //@todo Toggle the status of these links depending on the current page ?>
					<a href="#page_<?php echo $previous_page; ?>" class="prev btn btn-default btn-sm">&laquo; <?php echo Kohana::lang('ui_main.previous'); ?></a>
					<a href="#page_<?php echo $next_page; ?>" class="next btn btn-default btn-sm"><?php echo Kohana::lang('ui_main.next'); ?> &raquo;</a>
				</div>
			</div>		
		</div>
		<!-- /Bottom paginator -->
	        
