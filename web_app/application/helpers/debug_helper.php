<?php
/**
 * Created by PhpStorm.
 * User: mfabbu
 * Date: 1/4/2018
 * Time: 11:56 AM
 */

function console_log( $data ){
	echo '<script>';
	echo 'console.log('. json_encode( $data ) .')';
	echo '</script>';
}