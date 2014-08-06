<div id="" class="report_detail container main-content">

    <div class="row header-info">
          
          <div class="col-md-12 col-xs-12">
            <div class="row">
              <div class="col-md-8 col-xs-12 colleft">
                  <div class="main-header">
                      <div class="row">
                        <div class="col-md-12 col-xs-12">
                          <div class="row">
                            <div class="col-md-9 col-xs-6">
                              <span class="lugar"><?php echo html::specialchars($incident_location); ?></span>
                            </div>
                            <?php
                            // If Admin is Logged In - Allow For Edit Link
							if ($logged_in)
							{
							echo '<div class="col-md-3 col-xs-6">
	                               <div class="edit-links">
	                                  <a href="" id="help-quicklink" data-toggle="tooltip" data-placement="top" title="Necesitas ayuda?"><span>Ayuda</span></a>
	                                  <a href="'.url::site()."admin/reports/edit/".$incident_id.'" id="edit-quicklink" data-toggle="tooltip" data-placement="top" title="Editar"><span>Edita</span></a>
	                               </div>
	                            </div>';
							}
							?>

                          </div>    
                          <h1 class="bold-italic upper"><?php echo html::escape($incident_title); ?></h1>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-8 col-xs-12">
                          <p>Fecha: <strong><?php echo $incident_time.' '.$incident_date; ?></strong></p>
                          <?php Event::run('ushahidi_action.report_meta_after_time', $incident_id); ?>
                        </div>
                        <div class="col-md-offset-1 col-md-3 col-xs-12">
                         <?php
					    	  if ($incident_verified)
					    		{
					    			//echo '<p class="r_verified">'.Kohana::lang('ui_main.verified').'</p>';
					    			echo '<span class="verified-big"><img src="'.url::file_loc('images').'themes/ci-theme/images/verified-big.png" alt="Denuncia Verificada"/></span>';
					    		}
					    		else
					    		{
					    			echo '<p class="r_unverified">'.Kohana::lang('ui_main.unverified').'</p>';
					    		}
					  	  ?>                         
                        </div>  
                      </div>                  
                  </div> <!-- /. main-header -->
                </div> <!-- /. colleft -->
                <div class="col-md-4 col-xs-12 colleft categorys">

                      <h3 class="section-title">Categoría</h3> 
                      	<?php
							foreach ($incident_category as $category)
							{
								// don't show hidden categoies
								if ($category->category->category_visible == 0)
								{
									continue;
								}
									$style = "background-color:#".$category->category->category_color;
								
								?>
									<a href="<?php echo url::site()."reports/?c=".$category->category->id; ?>" title="<?php echo Category_Lang_Model::category_description($category->category_id); ?>" type="button" class="btn btn-md btn-warning upper bold-italic" style="<?php echo $style ?>">
									<?php echo Category_Lang_Model::category_title($category->category_id); ?>
									</a> 
								<?php 
							}
						?>
                </div> 
             </div>
            </div> 
         </div>
	
         <div class="row content-info">
          
	         <div class="col-md-12 col-xs-12">
	            <div class="row">
	              	<div class="col-md-8 col-xs-12 colleft">
	                  	<div class="row map-wrap">
	                      	<div class="col-md-12 col-xs-12">                        
	                        	<div id="map-init-detail">
	                        		<div id="report-map" class="report-map">
										<div class="map-holder" id="map"></div>
					        		</div>                        	
	                        	</div>
	                      	</div>
	                    </div>  <!-- /. map-wrap --> 
						<!--
	                    <div class="row button-big">
	                      <div class="col-md-12 col-xs-12">
	                          <button type="button" class="btn btn-lg btn-success upper bold-italic">Suscribirse a este reporte</button>
	                      </div>
	                    </div>  <!-- /. button-big --> 
					
	                    <div class="row the-content">
	                      	<div class="col-md-12 col-xs-12">
	                          
	                          	<div class="col-md-6 col-xs-12 right extra-content">
									<?php
									// Action::report_display_media - Add content just above media section
								    Event::run('ushahidi_action.report_display_media', $incident_id);
									if( count($incident_photos) > 0 || count($incident_videos) > 0){ 
										if( count($incident_photos) > 0 )
										{
											echo '<div class="img-thumb">';
											foreach ($incident_photos as $photo)
											{
												echo '<img alt="'.html::escape($incident_title).'" src="'.$photo['thumb'].'"/><a class="photothumb" rel="lightbox-group1" href="'.$photo['large'].'"class="btn btn-warning upper animate">+</a>';
											};
											echo '</div>';
										}

										// if there are videos, show those too
										if( count($incident_videos) > 0 )
										{
											echo "<div id=\"report-video\"><ul>\n";
											// embed the video codes
											foreach( $incident_videos as $incident_video)
											{
												echo "<li>\n\t";
												echo $videos_embed->embed($incident_video, FALSE, FALSE);
												echo "\n</li>\n";
											};
							  			echo "</ul></div>";

										}
									}
									?>                            	
	                                

	                          	</div>

	                          	<h3><?php echo Kohana::lang('ui_main.reports_description');?></h3>

	                          	<p><?php echo html::clean(nl2br($incident_description)); ?></p>	                         

	                      	</div>

	                    </div>  <!-- /. the-content --> 
	                    <div class="row related">
	                    		                    	<?php /*
	                    	<div class="col-md-12 col-xs-12" id="denuncias-cercanas">                          
	                         	<div class="wg-pod-header">
	                            	<h3><?php echo Kohana::lang('ui_main.additional_reports');?></h3>
	                          	</div>
	                          	<div class="wg-list-article">
                            		<ul>
                            			<?php foreach($incident_neighbors as $neighbor) { ?>
                            			<li>
                            				<a href="<?php echo url::site(); ?>reports/view/<?php echo $neighbor->id; ?>">
                                				<div class="col-md-10 col-xs-12 info">
				                                	<h4 class="upper bold-italic">
				                                		<?php echo $neighbor->incident_title; ?>
				                                		<small class="italic"><?php echo $neighbor->location_name.", ".round($neighbor->distance, 2); ?> Kms</small>
				                                	</h4>
													<p><?php echo $neighbor->incident_description; ?></p>
												</div>
								  			</a>
								    	<?php } ?>
                            		</ul>
                            	</div>
							</div>
							*/ ?>
							<?php
						    // Action::report_extra - Allows you to target an individual report right after the description
				            Event::run('ushahidi_action.report_extra', $incident_id);

							// Filter::comments_block - The block that contains posted comments
							Event::run('ushahidi_filter.comment_block', $comments);
							//echo $comments;
							?>

							<?php
								// Filter::comments_form_block - The block that contains the comments form
								Event::run('ushahidi_filter.comment_form_block', $comments_form);
								//echo $comments_form;
							?>
						</div><!-- /. related -->
					
					</div> <!-- /. colleft -->
					<div class="col-md-4 col-xs-12 colleft">
						<div class="buttons">
                          <a href="<?php echo url::site()."reports/submit"; ?>" role="button" class="btn btn-lg btn-danger upper bold-italic">NUEVO RECLAMO<?php// echo Kohana::lang('ui_main.submit'); ?></a>
                          <a href="<?php echo url::site()."contact"; ?>" type="button" class="btn btn-lg btn-warning upper bold-italic">Informar Error</a> 
                          <!--<button type="button" class="btn btn-lg btn-success upper bold-italic">Seguir Reporte</button> -->
                        </div>
						<div id="denuncias-cercanas">
							<h3 class="section-title">
								<?php echo Kohana::lang('ui_main.additional_reports');?>
							</h3>
	                       	<div class="wg-list-article">
	                    		<ul>
	                    			<?php foreach($incident_neighbors as $neighbor) { ?>
	                    			<li>
	                    				<a href="<?php echo url::site(); ?>reports/view/<?php echo $neighbor->id; ?>">
	                        				<div class="col-md-12 col-xs-12 info">
			                                	<h4 class="upper bold-italic">
			                                		<?php echo $neighbor->incident_title; ?>
			                                		<small class="italic"><?php echo $neighbor->location_name.", ".round($neighbor->distance, 2); ?> Kms</small>
			                                	</h4>
												<p><?php echo $neighbor->incident_description; ?></p>
											</div>
							  			</a>
							    	<?php } ?>
	                    		</ul>
	                        </div>
                        </div>
						<!-- start news source link -->
						<?php if( count($incident_news) > 0 ) { ?>
						<h3 class="section-title">Otros Reclamos<?php //echo Kohana::lang('ui_main.reports_news');?></h3>
						<ul>
								<?php
									foreach( $incident_news as $incident_new)
									{
										?>
										<li>
										 <h5 class="bold"><a href="<?php echo $incident_new; ?> " target="_blank"><?php
										echo $incident_new;?></a></h5>									
										</li>
										<?php
									}
						?>
						</ul>
						<?php } ?>

						<!-- end news source link -->
						<?php
						// Action::report_view_sidebar - This gives plugins the ability to insert into the sidebar (below the map and above additional reports)
						Event::run('ushahidi_action.report_view_sidebar', $incident_id);
						?>
					</div>
				</div>
			</div>
	</div>

	</div>
		
</div>
