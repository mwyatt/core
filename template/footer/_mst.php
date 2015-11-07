<?php if (isset($asset['mustache'])): ?>
	<?php foreach ($asset['mustache'] as $path): ?>

<script id="mst-<?php echo str_replace('/', '-', $path) ?>" type="x-tmpl-mustache">

<?php echo file_get_contents($this->getPathTemplate('mst/' . $path, 'mst')) ?>

</script>
		
	<?php endforeach ?>
<?php endif ?>
