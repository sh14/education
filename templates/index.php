<?php
global $link;
get_header();

get_template_part( 'registration' );

$result = do_query('SELECT * FROM message');
while($row = mysqli_fetch_array($result)){
	pr($row);
}

get_footer();
