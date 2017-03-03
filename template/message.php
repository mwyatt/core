<?php include($template->getTemplate('header')) ?>

<div class="message-container">
    <h1><?php echo $title ?></h1>
    <p><?php echo $description ?></p>

<?php if (!empty($systemError)) : ?>

    <p><?php echo $systemError ?></p>
    
<?php endif ?>

</div>

<?php include($template->getTemplate('footer')) ?>
