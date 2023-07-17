<?php echo $header; ?>

<div id="settings_error_reporting_page">

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

    <form action="index.php?route=settings/error_reporting" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_frontend; ?></label>
            <input type="checkbox" name="error_reporting_frontend" value="1" <?php if ($error_reporting_frontend) { echo 'checked'; } ?>>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_backend; ?></label>
            <input type="checkbox" name="error_reporting_backend" value="1" <?php if ($error_reporting_backend) { echo 'checked'; } ?>>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_method; ?></label>
            <select name="error_reporting_method">
                <option value="log" <?php if ($error_reporting_method == 'log') { echo 'selected'; } ?>><?php echo $lang_select_log; ?></option>
                <option value="screen" <?php if ($error_reporting_method == 'screen') { echo 'selected'; } ?>><?php echo $lang_select_screen; ?></option>
            </select>
            <?php if ($error_error_reporting_method) { ?>
                <span class="error"><?php echo $error_error_reporting_method; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_file; ?></label>
            <a href="<?php echo $link_log; ?>"><?php echo $lang_entry_view; ?></a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>