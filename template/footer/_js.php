<?php if (!empty($assetJs)) : ?>
	<?php foreach ($assetJs as $path) : ?>

<script src="<?php echo $url->generateVersioned($this->getPathBase(), 'asset/' . $path . '.js') ?>"></script>
		
	<?php
endforeach ?>
<?php endif ?>
