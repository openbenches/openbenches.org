<?php echo $header; ?>

<div id="add_country_page">

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

    <form action="index.php?route=add/country" class="controls" method="post">
        <?php foreach ($languages as $key => $value) { ?>
            <div class="fieldset">
                <label><?php echo $lang_entry_name; ?></label>
                <input type="text" required name="name[<?php echo $value; ?>]" class="large" value="<?php echo isset($name[$value]) ? $name[$value] : ''; ?>" maxlength="250">
                <span class="note">(<?php echo $key; ?>)</span>
                <?php if (isset($error_name[$value])) { ?>
                    <span class="error"><?php echo $error_name[$value]; ?></span>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="fieldset">
            <label><?php echo $lang_entry_code; ?></label>
            <input type="text" required name="code" class="small_plus" value="<?php echo $code; ?>" maxlength="3">
            <a class="hint" data-hint="<?php echo $lang_hint_code; ?>">[?]</a>
            <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_top; ?></label>
            <select name="top">
                <option value="0" <?php if ($top == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                <option value="1" <?php if ($top == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_top; ?>">[?]</a>
            <?php if ($error_top) { ?>
                <span class="error"><?php echo $error_top; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_enabled; ?></label>
            <select name="enabled">
                <option value="0" <?php if ($enabled == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                <option value="1" <?php if ($enabled == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
            </select>
            <?php if ($error_enabled) { ?>
                <span class="error"><?php echo $error_enabled; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_add; ?>" title="<?php echo $lang_button_add; ?>"></div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>