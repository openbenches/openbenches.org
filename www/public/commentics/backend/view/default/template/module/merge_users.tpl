<?php echo $header; ?>

<div id="module_merge_users_page">

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

    <form action="index.php?route=module/merge_users" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_from; ?></label>
            <select name="user_id_from">
                <option value=""><?php echo $lang_select_select; ?></option>
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $user_id_from) { echo 'selected'; } ?>><?php echo $user['info']; ?></option>
                <?php } ?>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_from; ?>">[?]</a>
            <?php if ($error_user_id_from) { ?>
                <span class="error"><?php echo $error_user_id_from; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_to; ?></label>
            <select name="user_id_to">
                <option value=""><?php echo $lang_select_select; ?></option>
                <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $user_id_to) { echo 'selected'; } ?>><?php echo $user['info']; ?></option>
                <?php } ?>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_to; ?>">[?]</a>
            <?php if ($error_user_id_to) { ?>
                <span class="error"><?php echo $error_user_id_to; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_merge; ?>" title="<?php echo $lang_button_merge; ?>"></div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>