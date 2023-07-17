<?php echo $header; ?>

<div id="edit_question_page">

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

    <form action="index.php?route=edit/question&amp;id=<?php echo $id; ?>" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_question; ?></label>
            <input type="text" required name="question" class="large_plus" value="<?php echo $question; ?>" maxlength="250">
            <?php if ($error_question) { ?>
                <span class="error"><?php echo $error_question; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_answer; ?></label>
            <input type="text" required name="answer" class="medium" value="<?php echo $answer; ?>" maxlength="250">
            <?php if ($error_answer) { ?>
                <span class="error"><?php echo $error_answer; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_language; ?></label>
            <select name="language" class="medium">
            <?php foreach ($languages as $key => $value) { ?>
                <option value="<?php echo $value; ?>" <?php if ($value == $language) { echo 'selected'; } ?>><?php echo $key; ?></option>
            <?php } ?>
            </select>
            <?php if ($error_language) { ?>
                <span class="error"><?php echo $error_language; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_date; ?></label>
            <div><?php echo $date_added; ?></div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons">
            <input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>">

            <input type="button" class="button" name="delete" data-id="<?php echo $id; ?>" data-url="manage/questions" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>">
        </div>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
    </form>

    <div id="delete_dialog" title="<?php echo $lang_dialog_delete_title; ?>" class="hide">
        <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_delete_content; ?>
    </div>

</div>

<?php echo $footer; ?>