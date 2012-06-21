		<div id="recentPackages">
				<h4><?=gettext('RECENT CHANGES');?></h4>
				<ul class="recent_packages" id='recent1'>
					<?
						
						for($x = 0; $x < $amount; $x++) {
							$row = $arr_recent_packages[$x];
							
							$url = $base_uri.$row['category_name'].'/'.$row['package_name'];
							$name = $row['package_name'].' '.$row['pvr'];
							
							echo "<li><a href='$url'>$name</a></li>\n";
							
						}
					
					?>
				</ul>
				<a style="margin-left:20px;" href="<?=$base_uri;?>" onclick="$(this).hide(); $('recent1').hide(); $('recent2').show(); return false;"><?=gettext('View More');?> ...</a>
				<ul class="recent_packages" id='recent2' style='display: none;'>
					<?
						
						for($x = 0; $x < count($arr_recent_packages); $x++) {
							$row = $arr_recent_packages[$x];
							
							$url = $base_uri.$row['category_name'].'/'.$row['package_name'];
							$name = $row['package_name'].' '.$row['pvr'];
							
							echo "<li><a href='$url'>$name</a></li>\n";
							
						}
					
					?>
				</ul>
				<hr>
			</div>	