<?php echo $header; ?>

<?php if ($page == 'list') { ?>
    <div id="module_extra_fields_page">

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

        <form action="index.php?route=module/extra_fields" class="controls" method="post">
            <div class="table_container">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th><a href="<?php echo $sort_name; ?>" <?php if ($sort == 'f.name') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_name; ?></a></th>
                            <th><a href="<?php echo $sort_type; ?>" <?php if ($sort == 'f.type') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_type; ?></a></th>
                            <th><a href="<?php echo $sort_required; ?>" <?php if ($sort == 'f.is_required') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_required; ?></a></th>
                            <th><a href="<?php echo $sort_enabled; ?>" <?php if ($sort == 'f.is_enabled') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_enabled; ?></a></th>
                            <th><?php echo $lang_column_action; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($fields) { ?>
                            <?php foreach ($fields as $field) { ?>
                                <tr>
                                    <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $field['id']; ?>"></td>
                                    <td data-th="<?php echo $lang_column_name; ?>:"><?php echo $field['name']; ?></td>
                                    <td data-th="<?php echo $lang_column_type; ?>:"><?php echo $field['type']; ?></td>
                                    <td data-th="<?php echo $lang_column_required; ?>:"><?php echo $field['required']; ?></td>
                                    <td data-th="<?php echo $lang_column_enabled; ?>:"><?php echo $field['enabled']; ?></td>
                                    <td class="actions">
                                        <a href="<?php echo $field['action']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                        <a data-id="<?php echo $field['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="no_results" colspan="6"><?php echo $lang_text_no_results; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

            <p><input type="submit" name="bulk_delete" class="button" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>"></p>

            <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
        </form>

        <div class="pagination_stats"><?php echo $pagination_stats; ?></div>

        <div class="pagination_links"><?php echo $pagination_links; ?></div>

        <div id="single_delete_dialog" title="<?php echo $lang_dialog_single_delete_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_single_delete_content; ?>
        </div>

        <div id="bulk_delete_dialog" title="<?php echo $lang_dialog_bulk_delete_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_bulk_delete_content; ?>
        </div>

    </div>
<?php } ?>

<?php if (in_array($page, array('add','edit'))) { ?>
    <div id="module_extra_fields_form_page">

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

        <form action="<?php echo $action; ?>" class="controls" method="post">
            <div class="fieldset">
                <label><?php echo $lang_entry_name; ?></label>
                <input type="text" name="name" class="large" value="<?php echo $name; ?>" maxlength="250">
                <a class="hint" data-hint="<?php echo $lang_hint_name; ?>">[?]</a>
                <?php if ($error_name) { ?>
                    <span class="error"><?php echo $error_name; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset">
                <label><?php echo $lang_entry_type; ?></label>
                <select name="type" class="large">
                    <option value="select" <?php if ($type == 'select') { echo 'selected'; } ?>><?php echo $lang_text_select; ?></option>
                    <option value="text" <?php if ($type == 'text') { echo 'selected'; } ?>><?php echo $lang_text_text; ?></option>
                    <option value="textarea" <?php if ($type == 'textarea') { echo 'selected'; } ?>><?php echo $lang_text_textarea; ?></option>
                </select>
                <a class="hint" data-hint="<?php echo $lang_hint_type; ?>">[?]</a>
                <?php if ($error_type) { ?>
                    <span class="error"><?php echo $error_type; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset">
                <label><?php echo $lang_entry_required; ?></label>
                <input type="checkbox" name="is_required" value="1" <?php if ($is_required) { echo 'checked'; } ?>>
                <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
            </div>

            <div class="fieldset select_section">
                <label><?php echo $lang_entry_values; ?></label>
                <input type="text" name="values" class="large" value="<?php echo $values; ?>" maxlength="9999">
                <a class="hint" data-hint="<?php echo $lang_hint_values; ?>">[?]</a>
                <?php if ($error_values) { ?>
                    <span class="error"><?php echo $error_values; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset text_section">
                <label><?php echo $lang_entry_default; ?></label>
                <input type="text" name="default" class="large" value="<?php echo $default; ?>" maxlength="9999">
                <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                <?php if ($error_default) { ?>
                    <span class="error"><?php echo $error_default; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset text_section">
                <label><?php echo $lang_entry_minimum; ?></label>
                <input type="text" name="minimum" class="small_plus" value="<?php echo $minimum; ?>" maxlength="4">
                <a class="hint" data-hint="<?php echo $lang_hint_minimum; ?>">[?]</a>
                <?php if ($error_minimum) { ?>
                    <span class="error"><?php echo $error_minimum; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset text_section">
                <label><?php echo $lang_entry_maximum; ?></label>
                <input type="text" name="maximum" class="small_plus" value="<?php echo $maximum; ?>" maxlength="4">
                <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                <?php if ($error_maximum) { ?>
                    <span class="error"><?php echo $error_maximum; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset text_section">
                <label><?php echo $lang_entry_validation; ?></label>
                <input type="text" name="validation" class="large" value="<?php echo $validation; ?>" maxlength="9999">
                <a class="hint" data-hint="<?php echo $lang_hint_validation; ?>">[?]</a>
                <?php if ($error_validation) { ?>
                    <span class="error"><?php echo $error_validation; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset">
                <label><?php echo $lang_entry_display; ?></label>
                <input type="checkbox" name="display" value="1" <?php if ($display) { echo 'checked'; } ?>>
                <a class="hint" data-hint="<?php echo $lang_hint_display; ?>">[?]</a>
            </div>

            <div class="fieldset">
                <label><?php echo $lang_entry_sort; ?></label>
                <input type="text" name="sort" class="small_plus" value="<?php echo $sort; ?>" maxlength="4">
                <a class="hint" data-hint="<?php echo $lang_hint_sort; ?>">[?]</a>
                <?php if ($error_sort) { ?>
                    <span class="error"><?php echo $error_sort; ?></span>
                <?php } ?>
            </div>

            <div class="fieldset">
                <label><?php echo $lang_entry_enabled; ?></label>
                <input type="checkbox" name="is_enabled" value="1" <?php if ($is_enabled) { echo 'checked'; } ?>>
                <a class="hint" data-hint="<?php echo $lang_hint_enabled; ?>">[?]</a>
            </div>

            <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

            <div class="buttons">
                <?php if ($page == 'add') { ?>
                    <input type="submit" class="button" value="<?php echo $lang_button_add; ?>" title="<?php echo $lang_button_add; ?>">
                <?php } else { ?>
                    <input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>">

                    <input type="button" class="button" name="delete" data-id="<?php echo $id; ?>" data-url="module/extra_fields" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>">
                <?php } ?>
            </div>

            <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>
        </form>

        <div id="delete_dialog" title="<?php echo $lang_dialog_delete_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_delete_content; ?>
        </div>

    </div>
<?php } ?>

<?php echo $footer; ?>