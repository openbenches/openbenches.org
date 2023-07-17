<?php echo $header; ?>

<div id="extension_themes_page">

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

    <form action="index.php?route=extension/themes" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_frontend; ?></label>
            <select name="theme_frontend" class="medium">
            <?php foreach ($frontend_themes as $key => $value) { ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $theme_frontend) { echo 'selected'; } ?>><?php echo $key; ?></option>
            <?php } ?>
            </select>
            <?php if ($error_theme_frontend) { ?>
                <span class="error"><?php echo $error_theme_frontend; ?></span>
            <?php } ?>
        </div>

        <a href="#" class="gallery"><img id="theme-preview-frontend" class="theme_preview" src="" alt=""></a>

        <div class="fieldset">
            <label><?php echo $lang_entry_backend; ?></label>
            <select name="theme_backend" class="medium">
            <?php foreach ($backend_themes as $key => $value) { ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $theme_backend) { echo 'selected'; } ?>><?php echo $key; ?></option>
            <?php } ?>
            </select>
            <?php if ($error_theme_backend) { ?>
                <span class="error"><?php echo $error_theme_backend; ?></span>
            <?php } ?>
        </div>

        <a href="#" class="gallery"><img id="theme-preview-backend" class="theme_preview" src="" alt=""></a>

        <h2><?php echo $lang_subheading; ?></h2>

        <div class="fieldset">
            <label><?php echo $lang_entry_auto_detect; ?></label>
            <input type="checkbox" name="auto_detect" value="1" <?php if ($auto_detect) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_auto_detect; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_optimize; ?></label>
            <input type="checkbox" name="optimize" value="1" <?php if ($optimize) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_optimize; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_jquery; ?></label>
            <select name="jquery_source">
                <option value="" <?php if ($jquery_source == '') { echo 'selected'; } ?>><?php echo $lang_select_no; ?></option>
                <option value="local" <?php if ($jquery_source == 'local') { echo 'selected'; } ?>><?php echo $lang_select_local; ?></option>
                <option value="google" <?php if ($jquery_source == 'google') { echo 'selected'; } ?>><?php echo $lang_select_google; ?></option>
                <option value="jquery" <?php if ($jquery_source == 'jquery') { echo 'selected'; } ?>><?php echo $lang_select_jquery; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_jquery_source; ?>">[?]</a>
            <?php if ($error_jquery_source) { ?>
                <span class="error"><?php echo $error_jquery_source; ?></span>
            <?php } ?>
        </div>

        <p><?php echo $lang_text_parts; ?></p>

        <div class="sortable">
            <ul id="sortable">
                <?php if ($order_parts == 'form,comments') { ?>
                    <li data-id="form" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $lang_text_form; ?></li>
                    <li data-id="comments" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $lang_text_comments; ?></li>
                <?php } else { ?>
                    <li data-id="comments" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $lang_text_comments; ?></li>
                    <li data-id="form" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $lang_text_form; ?></li>
                <?php } ?>
            </ul>
            <?php if ($error_order_parts) { ?>
                <span class="error"><?php echo $error_order_parts; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="order_parts" value="<?php echo $order_parts; ?>">

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>