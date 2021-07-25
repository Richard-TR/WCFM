  
<?php
/**  do not copy. just for syntax highlighting

How to create a shortcode from an ACF field.

In this example we have a custom field called Start Time with the ACF field 
name of course_start. The shortcode we produce will insert "Start Time:" 
text and then add the ACF field result after it. eg: Start Time: 18:00.

In this example the shortcode created is [ACF_course_start]

--------------------------------------------------------------------------

/** Create shortcode  **/
function ACF_course_start() { 
global $product;
if(get_field('course_start')) {
echo '<p><strong>Start Time:</strong> ' . get_field('course_start') . '</p>';
} 
}
add_shortcode( 'ACF_course_start', 'ACF_course_start' );

--------------------------------------------------------------------------
