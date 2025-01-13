<?php echo $header; ?>

<div id="settings_flooding_page">

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

    <form action="index.php?route=settings/flooding" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_delay; ?></label>
            <input type="checkbox" name="flood_control_delay_enabled" value="1" <?php if ($flood_control_delay_enabled) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_delay; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_time; ?></label>
            <input type="text" required name="flood_control_delay_time" class="small" value="<?php echo $flood_control_delay_time; ?>" maxlength="4">
            <span class="note"><?php echo $lang_note_seconds; ?></span>
            <?php if ($error_flood_control_delay_time) { ?>
                <span class="error"><?php echo $error_flood_control_delay_time; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset divide_after">
            <label><?php echo $lang_entry_all_pages; ?></label>
            <input type="checkbox" name="flood_control_delay_all_pages" value="1" <?php if ($flood_control_delay_all_pages) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_all_pages; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_maximum; ?></label>
            <input type="checkbox" name="flood_control_maximum_enabled" value="1" <?php if ($flood_control_maximum_enabled) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_amount; ?></label>
            <input type="text" required name="flood_control_maximum_amount" class="small" value="<?php echo $flood_control_maximum_amount; ?>" maxlength="4">
            <?php if ($error_flood_control_maximum_amount) { ?>
                <span class="error"><?php echo $error_flood_control_maximum_amount; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_period; ?></label>
            <input type="text" required name="flood_control_maximum_period" class="small" value="<?php echo $flood_control_maximum_period; ?>" maxlength="4">
            <span class="note"><?php echo $lang_note_hours; ?></span>
            <?php if ($error_flood_control_maximum_period) { ?>
                <span class="error"><?php echo $error_flood_control_maximum_period; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_all_pages; ?></label>
            <input type="checkbox" name="flood_control_maximum_all_pages" value="1" <?php if ($flood_control_maximum_all_pages) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_all_pages; ?>">[?]</a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>