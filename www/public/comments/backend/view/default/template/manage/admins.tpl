<?php echo $header; ?>

<div id="manage_admins_page">

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

    <div class="filter">
        <div class="row">
            <div class="column">
                <label><?php echo $lang_filter_username; ?></label>
                <input type="text" name="filter_username" value="<?php echo $filter_username; ?>">

                <label><?php echo $lang_filter_super; ?></label>
                <select name="filter_super">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="1" <?php if ($filter_super == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
                    <option value="0" <?php if ($filter_super == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>">

                <label><?php echo $lang_filter_last_login; ?></label>
                <input type="text" class="datepicker" name="filter_last_login" value="<?php echo $filter_last_login; ?>" placeholder="YYYY-MM-DD">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_enabled; ?></label>
                <select name="filter_enabled">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="1" <?php if ($filter_enabled == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
                    <option value="0" <?php if ($filter_enabled == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                </select>

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/admins" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th><a href="<?php echo $sort_username; ?>" <?php if ($sort == 'a.username') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_username; ?></a></th>
                        <th><a href="<?php echo $sort_email; ?>" <?php if ($sort == 'a.email') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_email; ?></a></th>
                        <th><a href="<?php echo $sort_enabled; ?>" <?php if ($sort == 'a.is_enabled') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_enabled; ?></a></th>
                        <th><a href="<?php echo $sort_super; ?>" <?php if ($sort == 'a.is_super') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_super; ?></a></th>
                        <th><a href="<?php echo $sort_last_login; ?>" <?php if ($sort == 'a.last_login') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_last_login; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'a.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($admins) { ?>
                        <?php foreach ($admins as $admin) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $admin['id']; ?>"></td>
                                <td data-th="<?php echo $lang_column_username; ?>:"><?php echo $admin['username']; ?></td>
                                <td data-th="<?php echo $lang_column_email; ?>:"><?php echo $admin['email']; ?></td>
                                <td data-th="<?php echo $lang_column_enabled; ?>:"><?php echo $admin['enabled']; ?></td>
                                <td data-th="<?php echo $lang_column_super; ?>:"><?php echo $admin['super']; ?></td>
                                <td data-th="<?php echo $lang_column_last_login; ?>:"><?php echo $admin['last_login']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $admin['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $admin['action']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $admin['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="no_results" colspan="8"><?php echo $lang_text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" name="bulk_delete" class="button" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>"></p>
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

<?php echo $footer; ?>