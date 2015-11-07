<?php if (!empty($asset['js'])) : ?>
	<?php foreach ($asset['js'] as $path) : ?>

<script src="<?php echo $this->url->generateVersioned('asset/' . $path . '.js') ?>"></script>
		
	<?php
endforeach ?>
<?php endif ?>
