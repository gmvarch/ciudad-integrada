 <div class="container-fluid wg-inner-header">

      <div class="container">
        <div class="starter-template" style="margin-top: 50px;">
          <div class="row">
             <div class="col-md-10 col-xs-12 text-left">
                  <h1 class="upper bold-italic">Obras Públicas</h1>   
             </div>
             </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->

	<div class="container main-content generic">
	<p class="lead">Acá podes encontrar todos las obras públicas</p>
	<div class="row content-info">
		<div id="reports-list" class="col-md-8 col-xs-12 colleft">
			<?php echo $obra_listing_view; ?>
		</div>
    <div id="filter" class="col-md-4 col-xs-12 colleft">
      <h2 class="red upper bold-italic"><?php //echo Kohana::lang('ui_main.filter_reports_by'); ?>Filtrar por</h2>
      <p>Seleccioná las categorías que querés elegir y hacé click en filtrar</p>
          <div class="col-md-12 col-xs-12">
              <h3 class="red upper bold-italic">
                <?php echo Kohana::lang('ui_main.category')?>
                <a href="#" class="small-link-button f-clear reset" onclick="removeParameterKey('c', 'fl-categories');">
                  <?php //echo Kohana::lang('ui_main.clear')?>
                </a>
              </h3>
              <div class="row">
                <div class="col-md-12 col-xs-12">
              <div class="f-category-box">
                <ul class="filter-list fl-categories" id="category-filter-list">
                  <li>
                    <a href="#"><?php
                    $all_cat_image = '&nbsp';
                    $all_cat_image = '';
                    if($default_map_all_icon != NULL) {
                      $all_cat_image = html::image(array('src'=>$default_map_all_icon));
                    }
                    ?>
                    <span class="item-swatch" style="background-color: #<?php echo Kohana::config('settings.default_map_all'); ?>">
                      <?php echo $all_cat_image ?>
                    </span>
                    <span class="item-title"><?php echo Kohana::lang('ui_main.all_categories'); ?></span>
                    <span class="item-count" id="all_report_count"><?php echo $report_stats->total_reports; ?></span>
                    </a>
                  </li>
                  <?php echo $category_tree_view; ?>
                </ul>
              </div>
                </div>
        </div>
      </div>
      <div id="filter-controls" class="col-md-12 col-xs-12">
        <p>
          <a href="#" class="small-link-button pull-left" id="reset_all_filters"><?php echo Kohana::lang('ui_main.reset_all_filters'); ?></a> 
          <a href="#" id="applyFilters" class="filter-button"><?php //echo Kohana::lang('ui_main.filter_reports'); ?>FILTRAR</a>
        </p>
        <?php
        // Action, allows plugins to add custom filter controls
        Event::run('ushahidi_action.report_filters_controls_ui');
        ?>
      </div>
    </div>
	</div>

</div>
