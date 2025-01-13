<?php echo $header; ?>

<div id="settings_administrator_page">

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

    <form action="index.php?route=settings/administrator" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_username; ?></label>
            <input type="text" required name="username" class="large" value="<?php echo $username; ?>" maxlength="250">
            <?php if ($error_username) { ?>
                <span class="error"><?php echo $error_username; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_password_1; ?></label>
            <input type="password" name="password_1" class="large" value="" maxlength="250">
            <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_password_2; ?></label>
            <input type="password" name="password_2" class="large" value="" maxlength="250">
            <?php if ($error_password) { ?>
                <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_strength; ?></label>
            <span id="password_strength" class="strength_0"></span>
            <span id="password_description"></span>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_email; ?></label>
            <input type="email" required name="email" class="large" value="<?php echo $email; ?>" maxlength="250">
            <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
        </div>

        <h2><?php echo $lang_subheading; ?></h2>

        <div class="fieldset">
            <label><?php echo $lang_entry_ban; ?></label>
            <input type="checkbox" name="receive_email_ban" value="1" <?php if ($receive_email_ban) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_ban; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_comment_approve; ?></label>
            <input type="checkbox" name="receive_email_comment_approve" value="1" <?php if ($receive_email_comment_approve) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_comment_approve; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_comment_success; ?></label>
            <input type="checkbox" name="receive_email_comment_success" value="1" <?php if ($receive_email_comment_success) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_comment_success; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_flag; ?></label>
            <input type="checkbox" name="receive_email_flag" value="1" <?php if ($receive_email_flag) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_flag; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_edit; ?></label>
            <input type="checkbox" name="receive_email_edit" value="1" <?php if ($receive_email_edit) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_edit; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_delete; ?></label>
            <input type="checkbox" name="receive_email_delete" value="1" <?php if ($receive_email_delete) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_delete; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_format; ?></label>
            <select name="format">
                <option value="html" <?php if ($format == 'html') { echo 'selected'; } ?>><?php echo $lang_select_html; ?></option>
                <option value="text" <?php if ($format == 'text') { echo 'selected'; } ?>><?php echo $lang_select_text; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_format; ?>">[?]</a>
            <?php if ($error_format) { ?>
                <span class="error"><?php echo $error_format; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>