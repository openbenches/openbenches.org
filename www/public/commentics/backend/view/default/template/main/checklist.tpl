<?php echo $header; ?>

<div id="main_checklist_page">

    <div class='page_help_block'><?php echo $page_help_link; ?></div>

    <h1><?php echo $lang_heading; ?></h1>

    <hr>

    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <?php if ($info) { ?>
        <div class="info"><?php echo $info; ?></div>
    <?php } ?>

    <?php if ($error) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <?php if ($warning) { ?>
        <div class="warning"><?php echo $warning; ?></div>
    <?php } ?>

    <p><?php echo $lang_text_welcome; ?></p>

    <ul>
        <li><?php echo $lang_text_permissions; ?></li>
        <li><?php echo $lang_text_email; ?></li>
        <li><?php echo $lang_text_fields; ?></li>
        <li><?php echo $lang_text_approval; ?></li>
    </ul>

    <p><?php echo $lang_text_integrate; ?></p>

    <p><?php echo $lang_text_return; ?></p>

    <form action="index.php?route=main/checklist" class="controls" method="post">
        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" class="button" value="<?php echo $lang_button_completed; ?>" title="<?php echo $lang_button_completed; ?>"></p>
    </form>

</div>

<?php echo $footer; ?>