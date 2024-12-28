<?php 
get_header();

if (locate_template('inc/templates/archive-' . cwp_get_template_type() . '.php') !== '') {
	get_template_part('inc/templates/archive', cwp_get_template_type());
}
else {
	get_template_part('inc/templates/archive', 'default');
}

get_footer();
