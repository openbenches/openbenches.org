<?php echo $header; ?>

<div id="settings_email_editor_page">

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

    <div class="selection">
        <select>
            <?php foreach ($types as $selection => $url) { ?>
                <option value="index.php?route=settings/email_editor&amp;type=<?php echo $url; ?>" <?php if ($url == $type) { echo 'selected'; } ?>><?php echo $selection; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="description"><?php echo $lang_description; ?></div>

    <div class="clear"></div>

    <div id="tabs">
        <ul>
            <?php foreach ($languages as $key => $value) { ?>
                <li><a href="#tab-<?php echo $value; ?>"><?php echo $key; ?></a></li>
            <?php } ?>
        </ul>

        <form action="index.php?route=settings/email_editor&amp;type=<?php echo $type; ?>" class="controls" method="post">
            <?php foreach ($languages as $key => $value) { ?>
                <div id="tab-<?php echo $value; ?>">
                    <div class="fieldset">
                        <label><?php echo $lang_entry_subject; ?></label>
                        <input type="text" name="field[<?php echo $value; ?>][subject]" class="large" value="<?php echo isset($field[$value]) ? $field[$value]['subject'] : ''; ?>" maxlength="250">
                        <?php if (isset($error_subject[$value])) { ?>
                            <span class="error"><?php echo $error_subject[$value]; ?></span>
                        <?php } ?>
                    </div>

                    <div class="section_keywords">
                        <div><?php echo $lang_entry_keywords; ?></div>
                        <div class="keywords"><?php echo $keywords; ?></div>
                    </div>

                    <div class="section_text">
                        <div><?php echo $lang_entry_text; ?></div>
                        <textarea name="field[<?php echo $value; ?>][text]"><?php echo isset($field[$value]) ? $field[$value]['text'] : ''; ?></textarea>
                        <?php if (isset($error_text[$value])) { ?>
                            <span class="error"><?php echo $error_text[$value]; ?></span>
                        <?php } ?>
                    </div>

                    <div class="section_html">
                        <div><?php echo $lang_entry_html; ?></div>
                        <textarea name="field[<?php echo $value; ?>][html]"><?php echo isset($field[$value]) ? $field[$value]['html'] : ''; ?></textarea>
                        <?php if (isset($error_html[$value])) { ?>
                            <span class="error"><?php echo $error_html[$value]; ?></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

            <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
        </form>
    </div>

</div>

<?php echo $footer; ?>