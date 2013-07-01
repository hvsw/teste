<?php
function printr($data) {
	echo "<pre>";
	print_r($data);	
}
function printrx($data) {
	die(printr($data));
}
