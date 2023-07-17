<?php echo $header; ?>

<div id="add_site_page">

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

    <form action="index.php?route=add/site" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_name; ?></label>
            <input type="text" required name="name" class="large" value="<?php echo $name; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_name; ?>">[?]</a>
            <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_domain; ?></label>
            <input type="text" required name="domain" class="large" value="<?php echo $domain; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_domain; ?>">[?]</a>
            <?php if ($error_domain) { ?>
                <span class="error"><?php echo $error_domain; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_url; ?></label>
            <input type="text" required name="url" class="large" value="<?php echo $url; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_url; ?>">[?]</a>
            <?php if ($error_url) { ?>
                <span class="error"><?php echo $error_url; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_iframe; ?></label>
            <input type="checkbox" name="iframe_enabled" value="1" <?php if ($iframe_enabled) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_iframe; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_new_pages; ?></label>
            <input type="checkbox" name="new_pages" value="1" <?php if ($new_pages) { echo 'checked'; } ?>>
            <a class="hint" data-hint="<?php echo $lang_hint_new_pages; ?>">[?]</a>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_from_name; ?></label>
            <input type="text" name="from_name" class="large" value="<?php echo $from_name; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_from_name; ?>">[?]</a>
            <?php if ($error_from_name) { ?>
                <span class="error"><?php echo $error_from_name; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_from_email; ?></label>
            <input type="text" name="from_email" class="large" value="<?php echo $from_email; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_from_email; ?>">[?]</a>
            <?php if ($error_from_email) { ?>
                <span class="error"><?php echo $error_from_email; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_reply_email; ?></label>
            <input type="text" name="reply_email" class="large" value="<?php echo $reply_email; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_reply_email; ?>">[?]</a>
            <?php if ($error_reply_email) { ?>
                <span class="error"><?php echo $error_reply_email; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_add; ?>" title="<?php echo $lang_button_add; ?>"></div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>