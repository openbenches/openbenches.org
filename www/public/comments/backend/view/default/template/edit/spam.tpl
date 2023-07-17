<?php echo $header; ?>

<div id="edit_spam_page">

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

    <form action="index.php?route=edit/spam&amp;id=<?php echo $id; ?>" class="controls" method="post">
        <div class="section">
            <div>
                <input type="radio" name="delete" id="delete_this" value="delete_this" <?php if ($delete == 'delete_this') { echo 'checked'; } ?>> <label for="delete_this"><?php echo $lang_text_delete_this; ?></label><br>
                <input type="radio" name="delete" id="delete_all" value="delete_all" <?php if ($delete == 'delete_all') { echo 'checked'; } ?>> <label for="delete_all"><?php echo $lang_text_delete_all; ?></label>
                <?php if ($error_delete) { ?>
                    <span class="error"><?php echo $error_delete; ?></span>
                <?php } ?>
            </div>

            <div>
                <input type="radio" name="ban" id="ban" value="ban" <?php if ($ban == 'ban') { echo 'checked'; } ?>> <label for="ban"><?php echo $lang_text_ban; ?></label><br>
                <input type="radio" name="ban" id="no_ban" value="no_ban" <?php if ($ban == 'no_ban') { echo 'checked'; } ?>> <label for="no_ban"><?php echo $lang_text_no_ban; ?></label>
                <?php if ($error_ban) { ?>
                    <span class="error"><?php echo $error_ban; ?></span>
                <?php } ?>
            </div>

            <div>
                <input type="checkbox" name="add_name" id="add_name" value="1" <?php if ($add_name) { echo 'checked'; } ?>> <label for="add_name"><?php echo $lang_text_add_name; ?></label><br>
                <?php if ($has_email) { ?>
                    <input type="checkbox" name="add_email" id="add_email" value="1" <?php if ($add_email) { echo 'checked'; } ?>> <label for="add_email"><?php echo $lang_text_add_email; ?></label><br>
                <?php } ?>
                <?php if ($has_website) { ?>
                    <input type="checkbox" name="add_website" id="add_website" value="1" <?php if ($add_website) { echo 'checked'; } ?>> <label for="add_website"><?php echo $lang_text_add_website; ?></label>
                <?php } ?>
            </div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_confirm; ?>" title="<?php echo $lang_button_confirm; ?>"></div>

        <div class="links"><a id="back"><?php echo $lang_link_back; ?></a></div>
    </form>

    <div id="spam_dialog" title="<?php echo $lang_dialog_spam_title; ?>" class="hide">
        <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_spam_content; ?>
    </div>

</div>

<?php echo $footer; ?>