$decoded_data = urldecode($_POST['order']);
$array = unserialize($decoded_data);
var_dump($array);