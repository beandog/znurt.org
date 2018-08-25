<?php

	$locale = "el_GR";

	putenv("LC_ALL=$locale");
	$var = setlocale(LC_ALL, $locale);

	var_dump($var);

	$name = "messages";

	$str = bindtextdomain("messages", "./locale");

	var_dump($str);

	$str = bind_textdomain_codeset("messages", "utf-8");

 	var_dump($str);

	$str = textdomain("messages");

	var_dump($str);

  	echo gettext("the fresh ebuilds");

// 	$str = sprintf(gettext('SEARCH RESULTS FOR %1$s &nbsp; (%2$u)'), "\"query\"", 15);
// 	echo $str;

	$str = gettext("ABOUT");
	echo $str;

?>
