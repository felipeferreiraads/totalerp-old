<?php $placeholder = "Buscar"; ?>

<form role="search" method="get" class="form-inline mv-searchform" action="<?php bloginfo('url'); ?>">
    <div class="input-group">
        <input type="search" class="form-control" id="search-press" value="<?php echo get_search_query() ?>" placeholder="<?php echo $placeholder; ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
        <div class="input-group-btn">
        	<input type="hidden" name="post_type" value="produtos" />
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>          
        </div>                  
    </div>                  
</form>