<?php echo $header; ?>

<div id="manage_users_page">

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
                <label><?php echo $lang_filter_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>">

                <label><?php echo $lang_filter_moderate; ?></label>
                <select name="filter_moderate">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="default" <?php if ($filter_moderate == 'default') { echo 'selected'; } ?>><?php echo $lang_text_default; ?></option>
                    <option value="never" <?php if ($filter_moderate == 'never') { echo 'selected'; } ?>><?php echo $lang_text_never; ?></option>
                    <option value="always" <?php if ($filter_moderate == 'always') { echo 'selected'; } ?>><?php echo $lang_text_always; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>">

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_avatar_approved; ?></label>
                <select name="filter_avatar_approved">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="1" <?php if ($filter_avatar_approved == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
                    <option value="0" <?php if ($filter_avatar_approved == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                </select>

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/users" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <?php if ($avatar_type) { ?>
                            <th><?php echo $lang_column_avatar; ?></th>
                        <?php } ?>
                        <th><a href="<?php echo $sort_name; ?>" <?php if ($sort == 'u.name') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_name; ?></a></th>
                        <th><a href="<?php echo $sort_email; ?>" <?php if ($sort == 'u.email') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_email; ?></a></th>
                        <th><a href="<?php echo $sort_comments; ?>" <?php if ($sort == 'comments') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_comments; ?></a></th>
                        <th><a href="<?php echo $sort_subscriptions; ?>" <?php if ($sort == 'subscriptions') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_subscriptions; ?></a></th>
                        <?php if ($avatar_type == 'upload') { ?>
                            <th><a href="<?php echo $sort_avatar_approved; ?>" <?php if ($sort == 'avatar_approved') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_avatar_approved; ?></a></th>
                        <?php } ?>
                        <th><a href="<?php echo $sort_moderate; ?>" <?php if ($sort == 'u.moderate') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_moderate; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'u.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users) { ?>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $user['id']; ?>"></td>
                                <?php if ($avatar_type) { ?>
                                    <td data-th="<?php echo $lang_column_avatar; ?>:"><a href="<?php echo $user['avatar']; ?>" target="_blank"><img src="<?php echo $user['avatar']; ?>" class="avatar"></a></td>
                                <?php } ?>
                                <td data-th="<?php echo $lang_column_name; ?>:"><?php echo $user['name']; ?></td>
                                <td data-th="<?php echo $lang_column_email; ?>:"><?php echo $user['email']; ?></td>
                                <td data-th="<?php echo $lang_column_comments; ?>:"><a href="<?php echo $user['comments_url']; ?>"><?php echo $user['comments']; ?></a></td>
                                <td data-th="<?php echo $lang_column_subscriptions; ?>:"><a href="<?php echo $user['subscriptions_url']; ?>"><?php echo $user['subscriptions']; ?></a></td>
                                <?php if ($avatar_type == 'upload') { ?>
                                    <td data-th="<?php echo $lang_column_avatar_approved; ?>:"><?php echo $user['avatar_approved']; ?></td>
                                <?php } ?>
                                <td data-th="<?php echo $lang_column_moderate; ?>:"><?php echo $user['moderate']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $user['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $user['action_view']; ?>" target="_blank"><img src="<?php echo $button_view; ?>" class="button_view" title="<?php echo $lang_button_view; ?>"></a>
                                    <a href="<?php echo $user['action_edit']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $user['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="no_results" colspan="10"><?php echo $lang_text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons">
            <?php if ($avatar_type == 'upload') { ?>
                <input type="submit" name="bulk_approve_avatar" class="button" value="<?php echo $lang_button_approve_avatar; ?>" title="<?php echo $lang_button_approve_avatar; ?>">

                <input type="submit" name="bulk_disapprove_avatar" class="button" value="<?php echo $lang_button_disapprove_avatar; ?>" title="<?php echo $lang_button_disapprove_avatar; ?>">
            <?php } ?>

            <input type="submit" name="bulk_delete" class="button" value="<?php echo $lang_button_delete; ?>" title="<?php echo $lang_button_delete; ?>">
        </div>
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