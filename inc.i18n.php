<?

	header('Content-type: text/html; charset=utf-8');

	$arr_locales = array(
		'cs_CZ' => 'Czech',
		'de_DE' => 'German',
		'el_GR' => 'Greek',
		'en_US' => 'English',
		'es_US' => 'Spanish',
		'fr_FR' => 'French',
		'tr_TR' => 'Turkish',
		'it_IT' => 'Italian',
		'ru_RU' => 'Russian',
	);
	
	if($_SERVER['HTTP_HOST'] == "cs.znurt.org" || $_SERVER['HTTP_HOST'] == "cz.znurt.org") {
		$lingua = "cz";
		$locale = "cs_CZ";
	} elseif($_SERVER['HTTP_HOST'] == "de.znurt.org" || $lingua == "de") {
		$i18n = true;
		$lingua = "de";
		$locale = "de_DE";
	} elseif($_SERVER['HTTP_HOST'] == "es.znurt.org" || $lingua == "es") {
		$i18n = true;
		$lingua = "es";
		$locale = "es_US";
	} elseif($_SERVER['HTTP_HOST'] == "fr.znurt.org" || $lingua == "fr") {
		$lingua = "fr";
		$locale = "fr_FR";
	} elseif($_SERVER['HTTP_HOST'] == "it.znurt.org" || $lingua == "it") {
 		$lingua = "it";
 		$locale = "it_IT";
	} elseif($_SERVER['HTTP_HOST'] == "tr.znurt.org" || $lingua == "tr") {
 		$lingua = "tr";
 		$locale = "tr_TR";
 	} elseif($_SERVER['HTTP_HOST'] == "ru.znurt.org" || $lingua == "ru") {
 		$lingua = "ru";
 		$locale = "ru_RU";
	} else
		$lingua = "en";

	$img_flag = $base_uri.'img/flags/'.$lingua.'.png';

	if($locale && in_array($locale, array_keys($arr_locales))) {
	
		setlocale(LC_MESSAGES, $locale);
		bindtextdomain("messages", "./locale");
		bind_textdomain_codeset("messages", 'UTF-8'); 
		textdomain("messages");
		
	}
	
	if($i18n) {
		$url_new_packages = str_replace(" ", "_", gettext("new packages"));
		$url_categories = gettext("categories");
		$url_useflags = str_replace(" ", "_", gettext("use flags"));
		$url_arch = gettext("architectures");
		$url_linguas = gettext("linguas");
		$url_about = gettext("about");
		$url_feeds = strtolower(gettext("Subscription Feeds"));
	} else {
		$url_new_packages = "new_packages";
		$url_categories = "categories";
		$url_useflags = "useflags";
		$url_arch = "arch";
		$url_linguas = "linguas";
		$url_about = "about";
		$url_feeds = "feeds";
	}
	
	date_default_timezone_set('UTC');
?>
