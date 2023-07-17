<?php echo $header; ?>

<div id="start_page">

    <p><?php echo $lang_text_welcome; ?></p>

    <hr>

    <p><?php echo $lang_text_installing; ?></p>

    <ul>
        <li><?php echo $lang_text_create_database; ?></li>
    </ul>

    <hr>

    <p><?php echo $lang_text_upgrading; ?></p>

    <ul>
        <li><?php echo $lang_text_back_up_database; ?></li>
        <li><?php echo $lang_text_back_up_files; ?></li>
    </ul>

    <hr>

    <p><?php echo $lang_text_click_start; ?></p>

    <form action="index.php?route=start" method="post">
        <input type="submit" class="button" value="<?php echo $lang_button_start; ?>" title="<?php echo $lang_button_start; ?>">
    </form>

</div>

<?php echo $footer; ?>