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
<div class="container">
	<div class="section">
		<h4>
			<?php echo empty($title)?'Places':$title ?>
		</h4>
		<div class="row">
			<div class="col s12 m6 l6">
				<form class="col s12" method="post" action="<?php echo site_url("/places/search") ?>">
					<input type="hidden" name="type" value="name">
					<div class="input-field">
						<i class="material-icons prefix">search</i>
						<input name="keyword" value="<?php echo $keyword ?>" id="frm_keyword" type="text" 
							class="validate" placeholder="enter a keyword">
					</div>
					<button class="btn" type="submit">Search</button>
				</form>
			</div>
			<div class="col s12 m6 l6">
				<p>
					Can't find your favorite place? You can add it 
					<a href="<?php echo site_url("/places/add") ?>">here</a>
				</p>
			</div>
		</div>
	</div>
	<div class="section">
		<?php if (empty($places)): ?>
			No places matching those conditions. Try again.
		<?php else: ?>
			
			<div class="row">
				<?php foreach ($places as $i => $place): ?>

					<div class="col s12 m4 l3">
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
					<?php if (($i+1) % 4 == 0): ?>
					</div>
					<div class="row">
					<?php endif ?>
		
				<?php endforeach ?>
			</div>
	
		<?php endif ?>
	</div>
</div>
