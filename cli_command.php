<?PHP
$a = (int)readline('How many rows you want to create: ');
file_get_contents('http://localhost/assignment/assignment/create_n_unique_records/'.$a, true);
?>