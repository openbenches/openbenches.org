<?php echo $header; ?>

<div id="add_ban_page">

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

    <form action="index.php?route=add/ban" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_ip_address; ?></label>
            <input type="text" required name="ip_address" class="medium" value="<?php echo $ip_address; ?>" maxlength="250">
            <?php if ($error_ip_address) { ?>
                <span class="error"><?php echo $error_ip_address; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_reason; ?></label>
            <input type="text" required name="reason" class="medium_plus" value="<?php echo $reason; ?>" maxlength="250">
            <?php if ($error_reason) { ?>
                <span class="error"><?php echo $error_reason; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_add; ?>" title="<?php echo $lang_button_add; ?>"></div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>