<?php echo $header; ?>

<div id="task_delete_voters_page">

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

    <form action="index.php?route=task/delete_voters" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_enabled; ?></label>
            <input type="checkbox" name="task_enabled_delete_voters" value="1" <?php if ($task_enabled_delete_voters) { echo 'checked'; } ?>>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_days; ?></label>
            <input type="text" required name="days_to_delete_voters" class="small" value="<?php echo $days_to_delete_voters; ?>" maxlength="4">
            <?php if ($error_days_to_delete_voters) { ?>
                <span class="error"><?php echo $error_days_to_delete_voters; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>