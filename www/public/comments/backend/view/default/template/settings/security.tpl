<?php echo $header; ?>

<div id="settings_security_page">

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

    <form action="index.php?route=settings/security" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_check_referrer; ?></label>
            <input type="checkbox" name="check_referrer" value="1" <?php if ($check_referrer) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_check_referrer; ?>">[?]</a>
            <?php if ($error_check_referrer) { ?>
                <span class="error"><?php echo $error_check_referrer; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_check_config; ?></label>
            <input type="checkbox" name="check_config" value="1" <?php if ($check_config) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_check_config; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_check_honeypot; ?></label>
            <input type="checkbox" name="check_honeypot" value="1" <?php if ($check_honeypot) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_check_honeypot; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_check_time; ?></label>
            <input type="checkbox" name="check_time" value="1" <?php if ($check_time) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_check_time; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_check_ip_address; ?></label>
            <input type="checkbox" name="check_ip_address" value="1" <?php if ($check_ip_address) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_check_ip_address; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_ssl_certificate; ?></label>
            <input type="checkbox" name="ssl_certificate" value="1" <?php if ($ssl_certificate) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_ssl_certificate; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_delete_install; ?></label>
            <input type="checkbox" name="delete_install" value="1">
            <a class="hint" data-hint="<?php echo $lang_hint_delete_install; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_ban_cookie; ?></label>
            <input type="text" required name="ban_cookie_days" class="small" value="<?php echo $ban_cookie_days; ?>" maxlength="4">
            <span class="note"><?php echo $lang_note_days; ?></span>
            <a class="hint" data-hint="<?php echo $lang_hint_ban_cookie; ?>">[?]</a>
            <?php if ($error_ban_cookie_days) { ?>
                <span class="error"><?php echo $error_ban_cookie_days; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>