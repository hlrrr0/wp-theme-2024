<?php 
get_header();

if (locate_template('inc/templates/single-' . cwp_get_template_type() . '.php') !== '') {
	get_template_part('inc/templates/single', cwp_get_template_type());
}
else {
	get_template_part('inc/templates/single', 'default');
}

get_footer();
