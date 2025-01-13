<?php echo $header; ?>

<div id="manage_subscriptions_page">

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

                <label><?php echo $lang_filter_confirmed; ?></label>
                <select name="filter_confirmed">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="1" <?php if ($filter_confirmed == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
                    <option value="0" <?php if ($filter_confirmed == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>">

                <label><?php echo $lang_filter_ip_address; ?></label>
                <input type="text" name="filter_ip_address" value="<?php echo $filter_ip_address; ?>">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_page; ?></label>
                <input type="text" name="filter_page" value="<?php echo $filter_page; ?>">

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/subscriptions" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th><a href="<?php echo $sort_name; ?>" <?php if ($sort == 'u.name') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_name; ?></a></th>
                        <th><a href="<?php echo $sort_email; ?>" <?php if ($sort == 'u.email') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_email; ?></a></th>
                        <th><a href="<?php echo $sort_page; ?>" <?php if ($sort == 'p.reference') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_page; ?></a></th>
                        <th><a href="<?php echo $sort_confirmed; ?>" <?php if ($sort == 's.is_confirmed') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_confirmed; ?></a></th>
                        <th><a href="<?php echo $sort_ip_address; ?>" <?php if ($sort == 's.ip_address') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_ip_address; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 's.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($subscriptions) { ?>
                        <?php foreach ($subscriptions as $subscription) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $subscription['id']; ?>"></td>
                                <td data-th="<?php echo $lang_column_name; ?>:"><a href="<?php echo $subscription['name_url']; ?>"><?php echo $subscription['name']; ?></a></td>
                                <td data-th="<?php echo $lang_column_email; ?>:"><?php echo $subscription['email']; ?></td>
                                <td data-th="<?php echo $lang_column_page; ?>:"><a href="<?php echo $subscription['page_url']; ?>"><?php echo $subscription['page']; ?></a></td>
                                <td data-th="<?php echo $lang_column_confirmed; ?>:"><?php echo $subscription['confirmed']; ?></td>
                                <td data-th="<?php echo $lang_column_ip_address; ?>:"><?php echo $subscription['ip_address']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $subscription['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $subscription['action_view']; ?>" target="_blank"><img src="<?php echo $button_view; ?>" class="button_view" title="<?php echo $lang_button_view; ?>"></a>
                                    <a href="<?php echo $subscription['action_edit']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $subscription['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
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

        <div class="buttons">
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