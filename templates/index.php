<?php
global $link;

get_template_part( 'main' );
get_template_part('profile_edit');

$result = do_query('SELECT * FROM message');
while($row = mysqli_fetch_array($result)){
	pr($row);
}