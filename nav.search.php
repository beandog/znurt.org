			<div id="search">
				<h4><?=gettext('SEARCH');?></h4>
				<form class="searchForm" action="<?=$base_uri;?>search.php" method="get" id="searchForm">
					<input type="text" name="search" class='search' size="17">
					<input type="text" name="q" class='q' size="17">
					<input type="image" src="<?=$base_uri;?>img/search.png" value="<?=gettext('Search');?>" alt="<?=gettext('Search');?>">
<!-- 					<a class="copyright" href="">Advanced Search</a> -->
				</form>
			</div>