<?php echo $header; ?>

<div id="settings_approval_page">

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

    <form action="index.php?route=settings/approval" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_approve_comments; ?></label>
            <input type="checkbox" name="approve_comments" value="1" <?php if ($approve_comments) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_approve_comments; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_approve_notifications; ?></label>
            <input type="checkbox" name="approve_notifications" value="1" <?php if ($approve_notifications) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_approve_notifications; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_trust_previous_users; ?></label>
            <input type="checkbox" name="trust_previous_users" value="1" <?php if ($trust_previous_users) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_trust_previous_users; ?>">[?]</a>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>