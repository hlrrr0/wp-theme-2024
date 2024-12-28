<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
    <div>
        <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
        <select name="status" id="status">
            <option value="">Select Status</option>
            <option value="onsale" <?php selected('onsale', get_query_var('status')); ?>>On Sale</option>
            <option value="sold" <?php selected('sold', get_query_var('status')); ?>>Sold</option>
        </select>
    </div>
    <div>
        <input type="hidden" name="post_type" value="puppies" />
        <input type="submit" id="searchsubmit" value="Search" />
    </div>
</form>
