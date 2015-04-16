<?php if(!empty($debug)) {echo "<pre>";var_dump($debug);echo "</pre>";} ?>

<?php
// shorten texts
foreach ($popular as $key => $place)
{
	if(strlen($place['info']) > PREVIEW_TEXT_LEN)
	{
		$popular[$key]['info'] = substr($place['info'], 0, PREVIEW_TEXT_LEN)."...";
	}
}
?>
<h3>
	Welcome to My Places!
</h3>
<p>
	Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet
	risus. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.
	Cras justo odio, dapibus ac facilisis in, egestas eget quam. Integer posuere erat a ante venenatis dapibus
	posuere velit aliquet.
</p>
<hr/>

<h4>
	Popular places
</h4>
<?php if (empty($popular)): ?>
	There are no popular places.
<?php else: ?>
	
	<?php foreach ($popular as $pop_place): ?>

		<div class="col_3">
			<!--
			<img class="align-left" src="http://placehold.it/150x150/4D99E0/ffffff.png&text=150x150" width="150" height="150" />
			-->
			<p>
				<a href="<?php echo site_url("/places/details/".$pop_place['id']) ?>">
					<strong>
						<?php echo $pop_place['name'] ?>
					</strong>
				</a>
				<br>
				<?php echo $pop_place['info'] ?>
				<br>
				<i class="icon-thumbs-up"></i>
				<?php echo sprintf("%.1f %%", $pop_place['popularity']) ?>
			</p>
		</div>
		
	<?php endforeach ?>
	
<?php endif ?>
<hr/>

<h4>
	Search places by tag
</h4>
<p class="tags">
<?php if (empty($tags)): ?>
	No tags found
<?php else: ?>
	<?php foreach ($tags as $tag): ?>
		<button href="/places/search/type/tag/keyword/<?php echo $tag['name'] ?>" class="link small pill">
			<i class="icon-tag"></i>
			<?php echo $tag['display_name'] ?>
		</button>
	<?php endforeach ?>
<?php endif ?>
</p>
