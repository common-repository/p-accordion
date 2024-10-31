<?php
//prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

function p_accordion_check_css ($key_var, $sub_var){
    $check = esc_attr( get_option($key_var) );
	if ($check) {
		 echo $check;
	} else {
		echo $sub_var;
	}
}

header( "Content-type: text/css; charset: UTF-8" );
 ?>
/* 
ACCORDION 
 */
button.accordion 
{
   background-color:  <?php  p_accordion_check_css('p_accordion_section', '#CCCCCC'); ?>; 
   color: <?php p_accordion_check_css('p_accordion_section_color', '#5e5e5e'); ?> !important;
   cursor: pointer;
   padding: 18px;
   width: 100%;
   border: none;
   text-align: left;
   outline: none;
   font-size: <?php p_accordion_check_css('p_accordion_section_font_size', '15'); ?>px;
   transition: 0.4s;
   border: 1px solid #777;
   border-radius: <?php p_accordion_check_css('p_accordion_radius', '10'); ?>px;
}
button.accordion:active, button.accordion:hover 
{
   background-color:<?php p_accordion_check_css('p_accordion_trans_color', '#d67e95'); ?>;
}
div.panel 
{
   margin-bottom: 5px !important;
   padding: 0 18px;
   background-color: <?php p_accordion_check_css('p_accordion_section_panel', 'ffffff'); ?>;
   max-height: 0;
   overflow: hidden;
   transition: max-height 0.2s ease-out;
}
/* ICONS ACCORDIONS SEZ */
button.accordion:after {
    content: '\02795'; /* Unicode character for "plus" sign (+) */
    font-size: <?php p_accordion_check_css('p_accordion_section_font_size', '15'); ?>px;  
    color: <?php p_accordion_check_css('p_accordion_section_color', '#5e5e5e'); ?> !important;
    float: left;
    margin-right: 3px;
}

button.accordion.active:after {
    content: "\2796"; /* Unicode character for "minus" sign (-) */
	font-size: <?php p_accordion_check_css('p_accordion_section_font_size', '15'); ?>px;
	color: <?php p_accordion_check_css('p_accordion_section_color', '#5e5e5e'); ?> !important;
}

 
/* 
ACCORDION GATEGORIES
 */
button.accordion-categorie {
   background-color: <?php p_accordion_check_css('p_accordion_categorie', '#FFFFF'); ?> ;
   color: <?php p_accordion_check_css('p_accordion_categorie_color', '#777'); ?> !important; 
   cursor: pointer;
   padding: 18px;
   width: 100%;
   border: none;
   text-align: left;
   outline: none;
   font-size: <?php p_accordion_check_css('p_accordion_categorie_font_size', '20'); ?>px;
   transition: 0.4s;
   border: 1px solid #777;
   border-radius: <?php p_accordion_check_css('p_accordion_radius', '10'); ?>px;
   margin-top: 10px;
}

button.accordion-categorie:active, button.accordion-categorie:hover 
{
   background-color:<?php p_accordion_check_css('p_accordion_trans_color', '#d67e95'); ?>;
}

div.panel-categories
{
   padding: 0 8px;
   background-color:<?php p_accordion_check_css('p_accordion_categorie_panel', '#FFFFFF'); ?>; 
   /* max-height: 0; */
   display: none;
   overflow: hidden;
   transition: max-height 0.2s ease-out;
   margin-top: 10px;
}
/* ICONS ACCORDIONS SEZ */
button.accordion-categorie:after {
    content: '\003E'; /* Unicode character for "plus" sign (+) */
    font-size: <?php p_accordion_check_css('p_accordion_categorie_font_size', '20'); ?> px;
    color: <?php p_accordion_check_css('p_accordion_categorie_color', '#777'); ?> !important;
    float: left;
    margin-right: 3px;
}

button.accordion-categorie.active:after {
    content: "\003E"; /* Unicode character for "minus" sign (-) */
	font-size: <?php p_accordion_check_css('p_accordion_categorie_font_size', '20'); ?> px;
	color: <?php p_accordion_check_css('p_accordion_categorie_color', '#777'); ?> !important;
}
