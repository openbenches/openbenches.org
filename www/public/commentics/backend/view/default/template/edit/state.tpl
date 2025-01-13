<?php echo $header; ?>

<div id="edit_state_page">

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

    <form action="index.php?route=edit/state&amp;id=<?php echo $id; ?>" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_name; ?></label>
            <input type="text" required name="name" class="large" value="<?php echo $name; ?>" maxlength="250">
            <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_country; ?></label>
            <select name="country_code">
                <option value=""><?php echo $lang_select_select; ?></option>
                <?php foreach ($countries as $country) { ?>
                    <option value="<?php echo $country['code']; ?>" <?php if ($country_code && $country['code'] == $country_code) { echo 'selected'; } ?>><?php echo $country['name']; ?></option>
                <?php } ?>
            </select>
            <?php if ($error_country_code) { ?>
                <span class="error"><?php echo $error_country_code; ?></span>
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

        <div class="fieldset">
            <label><?php echo $lang_entry_date; ?></label>
            <div><?php echo $date_added; ?></div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons">
            <input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>">

            <input type="button" class="button" name="delete" data-id="<?php echo $id; ?>" data-url="manage/states" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>">
        </div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

    <div id="delete_dialog" title="<?php echo $lang_dialog_delete_title; ?>" class="hide">
        <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_delete_content; ?>
    </div>

</div>

<?php echo $footer; ?>