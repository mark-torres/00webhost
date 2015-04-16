<?php
if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";}

$type = !empty($params['type']) ? $params['type'] : "";
$keyword = !empty($params['keyword']) ? $params['keyword'] : "";

if($type == 'tag') $keyword = "";

// shorten texts (if necessary)
foreach ($places as $key => $place)
{
	if(strlen($place['info']) > PREVIEW_TEXT_LEN)
	{
		$places[$key]['info'] = substr($place['info'], 0, PREVIEW_TEXT_LEN)."...";
	}
}

?>
<style media="screen">
	.place_list-item {
		min-height: 120px;
	}
</style>
<h4>
	<?php echo empty($title)?'Places':$title ?>
</h4>
<form method="post" action="/places/search">
	<input type="hidden" name="type" value="name">
	<input size="30" placeholder="enter a keyword" type="text" name="keyword" value="<?php echo $keyword ?>" id="frm_keyword">
	<button class="small blue"><i class="icon-search"></i> Find places</button>
</form>
<p>
	Can't find your favorite place? You can add it <a href="/places/add">here</a>.
</p>
<hr/>
<?php if (empty($places)): ?>
	No places matching those conditions. Try again.
<?php else: ?>
	
	<?php foreach ($places as $place): ?>

		<div class="col_3 place_list-item">
			<!--
			<img class="align-left" src="http://placehold.it/150x150/4D99E0/ffffff.png&text=150x150" width="150" height="150" />
			-->
			<p>
				<a href="<?php echo site_url("/places/details/".$place['id']) ?>">
				<strong>
					<?php echo $place['name'] ?>
				</strong>
				</a>
				<br>
				<?php echo $place['info'] ?>
			</p>
		</div>
		
	<?php endforeach ?>
	
<?php endif ?>
<script type="text/javascript">
	jQuery('#frm_keyword').focus();
</script>
