<?php echo $header; ?>

<div id="module_akismet_page">

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

    <form action="index.php?route=module/akismet" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_enabled; ?></label>
            <input type="checkbox" name="akismet_enabled" value="1" <?php if ($akismet_enabled) { echo 'checked'; } ?>>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_api_key; ?></label>
            <input type="text" required name="akismet_key" class="large" value="<?php echo $akismet_key; ?>" maxlength="250">
            <?php if ($error_akismet_key) { ?>
                <span class="error"><?php echo $error_akismet_key; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_logging; ?></label>
            <input type="checkbox" name="akismet_logging" value="1" <?php if ($akismet_logging) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_logging; ?>">[?]</a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>