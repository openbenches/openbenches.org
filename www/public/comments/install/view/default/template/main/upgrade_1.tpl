<?php echo $header; ?>

<div id="upgrade_1_page">

    <?php if ($error) { ?>

        <div class="error"><?php echo $error; ?></div>

    <?php } else { ?>

        <p><?php echo $lang_text_upgrade; ?></p>

        <hr>

        <p><label class="version"><?php echo $lang_text_installed; ?></label> <?php echo $installed_version; ?></p>

        <p><label class="version"><?php echo $lang_text_latest; ?></label> <?php echo $latest_version; ?></p>

        <hr>

        <form action="index.php?route=upgrade_2" method="post">
            <input type="submit" class="button" value="<?php echo $lang_button_upgrade; ?>" title="<?php echo $lang_button_upgrade; ?>">
        </form>

    <?php } ?>

</div>

<?php echo $footer; ?>