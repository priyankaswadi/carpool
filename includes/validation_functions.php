<?php




function has_presence($value) {
	return isset($value) && $value !== "";
}
// * string length
// max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function validateEMAIL($EMAIL) {
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
    return preg_match($regex, $EMAIL);
}

?>