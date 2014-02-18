<script type='text/javascript'>
</script>
<?
	
	$arr_chunk = array_chunk($arr_arch, round(count($arr_arch) / 4));
	
	$str = gettext("ARCHITECTURES");
	echo "<h4>$str</h4>\n";
	
	echo "<hr class='pkg_rule' style='margin-bottom: 15px; margin-top: 15px;'>\n";
	
	echo "<div class='about'>\n";
	
	$str = gettext("Pick the architectures to display:");
	
	if($lingua == 'en') {
		echo "<p>Gentoo supports a lot of architectures, which makes things interesting (especially for coding this site).  By default, Znurt only shows the \"main\" Linux arches, but there are a lot more available.</p>\n";
		
		echo "<p>If you want to display other ones, you can select your preferences here, which will be stored in a cookie.</p>\n";
	} elseif($lingua == 'es') {
		echo "<p>Elige las arquitecturas que quieres ver:</p>\n";
	} elseif($lingua == "de") {
		echo "<p>Anzuzeigende Architekturen auswählen:</p>\n";
	}
		
	$x = 0;
	
	echo "<form method='get' action='preferences.php' id='architectures' autocomplete='off'>\n";
	echo "<input type='hidden' name='section' value='architectures'>\n";
	$str = gettext("Submit");
	echo "<div><input type='submit' value='Yay, cookies!' name='submit'>";
	$str = gettext("Reset Architectures");
	if(count($_COOKIE['arch']))
		echo " &nbsp; <input type='submit' value='Reset Arches' name='submit'>";
	echo "</div>\n";
	
	echo "<p><table cellpadding='0' cellspacing='0' style='width: 100%;'>\n";
	echo "<tr>\n";
	
	foreach($arr_chunk as $arr) {
		
		echo "<td valign='top'>\n";
		echo "<table cellpadding='4' cellspacing='0' style='width: 100%;'>\n";
	
		$x = 0;
	
		foreach($arr as $name) {
		
			$class = getRowClass($x++);
		
			$str = "";
			if(in_array($name, $arr_display_arch))
				$str = "checked";
		
			echo "<tr class='$class'><td><input type='checkbox' name='arch[$name]' value='1' $str></td><td>$name</td></tr>\n";
		
		}
		
		echo "</table>\n";
		echo "</td>\n";
		
	}
	
	echo "</tr>\n";
	echo "</table></p>\n";
	
	echo "</form>\n";
	
	echo "<div id='js_options' style='display: none;'>\n";
	$str = gettext("Select All");
	echo "<input type='button' id='select_all' value='Select All'> &nbsp; ";
	$str = gettext("Select None");
	echo "<input type='button' id='select_none' value='Select None'> &nbsp; ";
	$str = gettext("Reset");
	echo "<input type='button' value='Reset' onclick=\"Form.reset('architectures');\">";
	echo "</div>\n";

	echo "</div>\n";
		

?>
<script type='text/javascript'>
	
	var checkboxes = $$('#architectures input[type=checkbox]');
	var check_all = $('select_all');
	var check_none = $('select_none');
	
	check_all.observe('click', function() {
		checkboxes.each(function(box) {
			box.checked = true;
		});
	});
	
	check_none.observe('click', function() {
		checkboxes.each(function(box) {
			box.checked = false;
		});
	});
	
 	$('js_options').show();
	
</script>
