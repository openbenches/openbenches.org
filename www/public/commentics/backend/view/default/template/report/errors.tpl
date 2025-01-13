<?php echo $header; ?>

<div id="report_errors_page">

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

    <div class="description"><?php echo $lang_description; ?></div>

    <form action="index.php?route=report/errors" class="controls" method="post">
        <textarea name="errors" readonly><?php echo $errors; ?></textarea>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" class="button" value="<?php echo $lang_button_reset; ?>" title="<?php echo $lang_button_reset; ?>"></p>

        <p><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></p>
    </form>

</div>

<?php echo $footer; ?>