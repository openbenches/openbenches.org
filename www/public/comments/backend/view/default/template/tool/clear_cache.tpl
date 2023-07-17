<?php echo $header; ?>

<div id="tool_clear_cache_page">

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

    <form action="index.php?route=tool/clear_cache" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_database; ?></label>
            <input type="checkbox" name="database" value="1" <?php if ($database) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_database; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_modification; ?></label>
            <input type="checkbox" name="modification" value="1" <?php if ($modification) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_modification; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_template; ?></label>
            <input type="checkbox" name="template" value="1" <?php if ($template) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_template; ?>">[?]</a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" class="button" value="<?php echo $lang_button_clear; ?>" title="<?php echo $lang_button_clear; ?>"></p>
    </form>

</div>

<?php echo $footer; ?>