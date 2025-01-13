<?php echo $header; ?>

<div id="login_page">

    <div class="box">
        <form action="index.php?route=login/login" method="post">
            <fieldset>
                <legend><?php echo $lang_text_login; ?></legend>
                <label><?php echo $lang_entry_username; ?></label> <input type="text" required autofocus name="username">
                <br>
                <label><?php echo $lang_entry_password; ?></label> <input type="password" required name="password">
                <br>
                <input type='submit' class='button' value='<?php echo $lang_button_login; ?>' title='<?php echo $lang_button_login; ?>'>
            </fieldset>
        </form>
        <?php if ($message) { ?>
            <div class="message">
                <?php if ($message['type'] == 'positive') { ?>
                    <span class="positive"><?php echo $message['text']; ?></span>
                <?php }    ?>
                <?php if ($message['type'] == 'negative') { ?>
                    <span class="negative"><?php echo $message['text']; ?></span>
                <?php }    ?>
            </div>
        <?php }    ?>
        <div class="link">
            <a href="index.php?route=login/reset" title="<?php echo $lang_link_reset; ?>"><?php echo $lang_link_reset; ?></a>
        </div>
    </div>

</div>

<?php echo $footer; ?>