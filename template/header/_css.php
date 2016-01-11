<?php if (!empty($assetCss)) : ?>
	<?php foreach ($assetCss as $path) : ?>

<link href="<?php echo $url->generateVersioned($this->getPathBase(), 'asset/' . $path . '.css') ?>" media="screen, projection, print" rel="stylesheet" type="text/css" />
        
	<?php
endforeach ?>
<?php endif ?>
