<?php
global $link;
get_header();
pr('asda');
get_template_part( 'main' );

$result = do_query('SELECT * FROM message');
while($row = mysqli_fetch_array($result)){
	pr($row);
}

get_footer();
