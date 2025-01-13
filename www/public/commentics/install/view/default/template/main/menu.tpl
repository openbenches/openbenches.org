<?php echo $header; ?>

<div id="menu_page">

    <p><?php echo $lang_text_action; ?></p>

    <form action="index.php?route=menu" method="post">
        <div class="choice"><input type="radio" name="action" id="install" value="install" checked> <label for="install"><?php echo $lang_entry_install; ?></label></div>
        <div class="choice"><input type="radio" name="action" id="upgrade" value="upgrade"> <label for="upgrade"><?php echo $lang_entry_upgrade; ?></label></div>

        <input type="submit" class="button" value="<?php echo $lang_button_continue; ?>" title="<?php echo $lang_button_continue; ?>">
    </form>

</div>

<?php echo $footer; ?>