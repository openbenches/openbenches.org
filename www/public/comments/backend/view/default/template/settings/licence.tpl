<?php echo $header; ?>

<div id="settings_licence_page">

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

    <form action="index.php?route=settings/licence" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_licence; ?></label>
            <input type="text" name="licence" class="large" value="<?php echo $licence; ?>" maxlength="250">
            <?php if ($error_licence) { ?>
                <span class="error"><?php echo $error_licence; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_forum_user; ?></label>
            <input type="text" name="forum_user" class="medium" value="<?php echo $forum_user; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_forum_user; ?>">[?]</a>
            <?php if ($error_forum_user) { ?>
                <span class="error"><?php echo $error_forum_user; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>