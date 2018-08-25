<?php

	/**
	 * A note about cookies
	 *
	 * Use the strict mode on in_array() since some funky stuff can go in there
	 * and be impossible to unset.  For example, I accidentally, somehow, set
	 * the key name of an array to zero, and couldn't unset it.  Whoops.
	 */


	$nocache = true;

	require_once 'inc.header1.php';

	$section = $_GET['section'];
	$submit = $_GET['submit'];

	switch($section) {

		case 'architectures':

			if($submit == 'Yay, cookies!')
				$set = true;
			elseif($submit == 'Reset Arches')
				$reset = true;

			if($set) {

				if(!headers_sent() && count($_GET['arch'])) {

					$arr_keys = array_keys($_GET['arch']);

					// Check to see if the arches they selected aren't just
					// the same ones as the default.

					$cookie = false;

					foreach($arr_keys as $name) {
						if($cookie == false && in_array($name, $arr_arch, true) && !in_array($name, $arr_default_arch, true)) {
							$cookie = true;
						}
					}

					// Add a cookie if their preferences are different
					// and the arches selected are actually in there
					if($cookie) {

						$expiration = time() + (86400 * 365);

						foreach($arr_arch as $name) {
							if(in_array($name, $arr_keys, true)) {
								setcookie("arch[$name]", 1, $expiration);
							}
						}
					}
				}
			}

			if($reset || ($set && !$cookie)) {

				$time = time() - 86400;

				setcookie("arch[0]", "", $time);

				if(!headers_sent() && count($_COOKIE['arch'])) {
					$arr_keys = array_keys($_COOKIE['arch']);

					foreach($arr_keys as $name) {
 						setcookie("arch[$name]", "", $time);
					}
				}
			}

			break;

	}

	// Send them back to the homepage if they actually made changes
	// Otherwise, let them play with the page some more.

	$url = $_SERVER['HTTP_REFERER'];

	if(!headers_sent() && $url)
		header("Location: $url");

?>
