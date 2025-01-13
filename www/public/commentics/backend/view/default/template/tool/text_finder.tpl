<?php echo $header; ?>

<div id="tool_text_finder_page">

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

    <form action="index.php?route=tool/text_finder" class="controls" method="post">
        <div class="fieldset">
            <label><?php echo $lang_entry_location; ?></label>
            <select name="location">
                <option value="backend" <?php if ($location == 'backend') { echo 'selected'; } ?>><?php echo $lang_select_backend; ?></option>
                <option value="frontend" <?php if ($location == 'frontend') { echo 'selected'; } ?>><?php echo $lang_select_frontend; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_location; ?>">[?]</a>
            <?php if ($error_location) { ?>
                <span class="error"><?php echo $error_location; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_case; ?></label>
            <select name="case">
                <option value="sensitive" <?php if ($case == 'sensitive') { echo 'selected'; } ?>><?php echo $lang_select_sensitive; ?></option>
                <option value="insensitive" <?php if ($case == 'insensitive') { echo 'selected'; } ?>><?php echo $lang_select_insensitive; ?></option>
            </select>
            <a class="hint" data-hint="<?php echo $lang_hint_case; ?>">[?]</a>
            <?php if ($error_case) { ?>
                <span class="error"><?php echo $error_case; ?></span>
            <?php } ?>
        </div>

        <div class="fieldset">
            <label><?php echo $lang_entry_text; ?></label>
            <input type="text" required name="text" class="large" value="<?php echo $text; ?>" maxlength="250">
            <a class="hint" data-hint="<?php echo $lang_hint_text; ?>">[?]</a>
            <?php if ($error_text) { ?>
                <span class="error"><?php echo $error_text; ?></span>
            <?php } ?>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <?php if ($search) { ?>
            <div class="results">
                <h3><?php echo $lang_subheading; ?></h3>

                <?php if ($results) { ?>
                    <?php foreach ($results as $result) { ?>
                        <?php echo $result; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p><?php echo $lang_text_no_results; ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_search; ?>" title="<?php echo $lang_button_search; ?>"></div>
    </form>

</div>

<?php echo $footer; ?>