<?php echo $header; ?>

<div id="extension_languages_page">

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

    <form action="index.php?route=extension/languages" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_frontend; ?></label>
            <select name="language_frontend" class="medium">
            <?php foreach ($frontend_languages as $key => $value) { ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $language_frontend) { echo 'selected'; } ?>><?php echo $key; ?></option>
            <?php } ?>
            </select>
            <?php if ($error_language_frontend) { ?>
                <span class="error"><?php echo $error_language_frontend; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_backend; ?></label>
            <select name="language_backend" class="medium">
            <?php foreach ($backend_languages as $key => $value) { ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $language_backend) { echo 'selected'; } ?>><?php echo $key; ?></option>
            <?php } ?>
            </select>
            <?php if ($error_language_backend) { ?>
                <span class="error"><?php echo $error_language_backend; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_rtl; ?></label>
            <input type="checkbox" name="rtl" value="1" <?php if ($rtl) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_rtl; ?>">[?]</a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>