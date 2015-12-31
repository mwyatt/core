<?php if (isset($assetMst)) : ?>
	<?php foreach ($assetMst as $path) : ?>

<script id="mst-<?php echo str_replace('/', '-', $path) ?>" type="x-tmpl-mustache">

<?php echo file_get_contents($this->getPathTemplate('mst/' . $path, 'mst')) ?>

</script>
		
	<?php
endforeach ?>
<?php endif ?>
