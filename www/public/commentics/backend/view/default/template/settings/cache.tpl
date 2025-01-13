<?php echo $header; ?>

<div id="settings_cache_page">

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

    <form action="index.php?route=settings/cache" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_type; ?></label>
            <select name="cache_type">
                <option value="" <?php if ($cache_type == '') { echo 'selected'; } ?>><?php echo $lang_select_none; ?></option>
                <option value="file" <?php if ($cache_type == 'file') { echo 'selected'; } ?>><?php echo $lang_select_file; ?></option>
                <option value="memcached" <?php if ($cache_type == 'memcached') { echo 'selected'; } ?>><?php echo $lang_select_memcached; ?></option>
                <option value="redis" <?php if ($cache_type == 'redis') { echo 'selected'; } ?>><?php echo $lang_select_redis; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_type; ?>">[?]</a>
            <?php if ($error_cache_type) { ?>
                <span class="error"><?php echo $error_cache_type; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_time; ?></label>
            <input type="text" required name="cache_time" class="small_plus" value="<?php echo $cache_time; ?>" maxlength="9">
            <span class="note"><?php echo $lang_note_seconds; ?></span>
            <?php if ($error_cache_time) { ?>
                <span class="error"><?php echo $error_cache_time; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset extra_section">
            <label><?php echo $lang_entry_host; ?></label>
            <input type="text" name="cache_host" class="medium" value="<?php echo $cache_host; ?>" maxlength="250">
            <?php if ($error_cache_host) { ?>
                <span class="error"><?php echo $error_cache_host; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset extra_section">
            <label><?php echo $lang_entry_port; ?></label>
            <input type="text" name="cache_port" class="small_plus" value="<?php echo $cache_port; ?>" maxlength="250">
            <?php if ($error_cache_port) { ?>
                <span class="error"><?php echo $error_cache_port; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>