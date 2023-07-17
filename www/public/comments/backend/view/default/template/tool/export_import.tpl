<?php echo $header; ?>

<div id="tool_export_import_page">

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

    <form action="index.php?route=tool/export_import" class="controls" method="post" enctype="multipart/form-data">
        <div id="tabs">
            <ul>
                <li><a href="#tab-export"><?php echo $lang_tab_export; ?></a></li>
                <li><a href="#tab-import"><?php echo $lang_tab_import; ?></a></li>
            </ul>

            <div id="tab-export">
                <div class="fieldset">
                    <label><?php echo $lang_entry_type; ?></label>
                    <select name="type">
                        <option value="countries" <?php if ($type == 'countries') { echo 'selected'; } ?>><?php echo $lang_select_countries; ?></option>
                        <option value="emails" <?php if ($type == 'emails') { echo 'selected'; } ?>><?php echo $lang_select_emails; ?></option>
                        <option value="questions" <?php if ($type == 'questions') { echo 'selected'; } ?>><?php echo $lang_select_questions; ?></option>
                    </select>
                    <?php if ($error_type) { ?>
                        <span class="error"><?php echo $error_type; ?></span>
                    <?php } ?>
                </div>

                <p><input type="submit" class="button" name="export" value="<?php echo $lang_button_export; ?>" title="<?php echo $lang_button_export; ?>"></p>
            </div>

            <div id="tab-import">
                <div class="fieldset">
                    <label><?php echo $lang_entry_upload; ?></label>
                    <input type="file" name="file" accept=".csv">
                </div>

                <p><input type="submit" class="button" name="import" value="<?php echo $lang_button_import; ?>" title="<?php echo $lang_button_import; ?>"></p>
            </div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">
    </form>

</div>

<?php echo $footer; ?>