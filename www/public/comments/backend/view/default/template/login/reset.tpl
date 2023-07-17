<?php echo $header; ?>

<div id="reset_page">

    <div class="box">
        <form action="index.php?route=login/reset" method="post">
            <fieldset>
                <legend><?php echo $lang_text_reset; ?></legend>
                <label><?php echo $lang_entry_email; ?></label> <input type="email" required autofocus name="email">
                <br>
                <input type='submit' class='button' value='<?php echo $lang_button_reset; ?>' title='<?php echo $lang_button_reset; ?>'>
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
            <a href="index.php?route=login/login" title="<?php echo $lang_link_login; ?>"><?php echo $lang_link_login; ?></a>
        </div>
    </div>

</div>

<?php echo $footer; ?>