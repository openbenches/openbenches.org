<?php echo $header; ?>

<div id="settings_viewers_page">

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

    <form action="index.php?route=settings/viewers" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_enabled; ?></label>
            <input type="checkbox" name="viewers_enabled" value="1" <?php if ($viewers_enabled) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_enabled; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_timeout; ?></label>
            <input type="text" required name="viewers_timeout" class="small_plus" value="<?php echo $viewers_timeout; ?>" maxlength="5">
            <span class="note"><?php echo $lang_note_seconds; ?></span>
            <a class="hint" data-hint="<?php echo $lang_hint_timeout; ?>">[?]</a>
            <?php if ($error_viewers_timeout) { ?>
                <span class="error"><?php echo $error_viewers_timeout; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>