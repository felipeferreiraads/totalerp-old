<?php 

/**
Template Name: Home
***/

?>

<?php get_header(); 


if(have_posts()) {
	while(have_posts()) { 
		the_post();
   

   		if(wp_is_mobile()){
   			get_template_part('topo');
   		} else {
   			if(get_field('video') != '') {
		    	get_template_part('video', 'bg');    	
		    } else {
		    	get_template_part('topo');
			} 

   		}
	}
}



if(wp_is_mobile()) {
	get_template_part('mob', 'netflix');
} else {
	get_template_part('section', 'netflix');

}

get_template_part('section', 'modulares');

get_template_part('section', 'experimente');

get_template_part('section', 'depoimentos');

get_template_part('modal');

get_footer(); ?>