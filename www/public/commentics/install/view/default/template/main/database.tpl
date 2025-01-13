<?php echo $header; ?>

<div id="database_page">

    <?php if ($writable) { ?>

        <?php if ($error) { ?>
            <div class="error"><?php echo $lang_error_connection; ?> <?php echo $error; ?>.</div>
        <?php } ?>

        <p><?php echo $lang_text_enter_details; ?></p>

        <form action="index.php?route=database" method="post">

            <span class="field_heading"><?php echo $lang_entry_database; ?></span> <span class="required_symbol">*</span><br>
            <input type="text" required autofocus name="database" value="<?php echo $database; ?>"> <span class="note"><?php echo $lang_note_database; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_username; ?></span> <span class="required_symbol">*</span><br>
            <input type="text" required name="username" value="<?php echo $username; ?>"> <span class="note"><?php echo $lang_note_username; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_password; ?></span><br>
            <input type="password" name="password" value="<?php echo $password; ?>"> <span class="note"><?php echo $lang_note_password; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_hostname; ?></span> <span class="required_symbol">*</span><br>
            <input type="text" required name="hostname" value="<?php echo $hostname; ?>"> <span class="note"><?php echo $lang_note_hostname; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_port; ?></span><br>
            <input type="text" name="port" value="<?php echo $port; ?>"> <span class="note"><?php echo $lang_note_port; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_prefix; ?></span><br>
            <input type="text" name="prefix" value="<?php echo $prefix; ?>"> <span class="note"><?php echo $lang_note_prefix; ?></span><br>

            <span class="field_heading"><?php echo $lang_entry_driver; ?></span><br>
            <select name="driver">
                <option value="mysqli" <?php if ($driver == 'mysqli') { echo 'selected'; } ?>>MySQLi</option>
            </select>
            <span class="note"><?php echo $lang_note_driver; ?></span><br>

            <input type="submit" class="button" value="<?php echo $lang_button_continue; ?>" title="<?php echo $lang_button_continue; ?>">

        </form>

    <?php } else { ?>
        <div class="error"><?php echo $lang_error_permission; ?></div>
    <?php } ?>

</div>

<?php echo $footer; ?>