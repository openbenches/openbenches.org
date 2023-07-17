<?php echo $header; ?>

<div id="manage_pages_page">

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
                <label><?php echo $lang_filter_identifier; ?></label>
                <input type="text" name="filter_identifier" value="<?php echo $filter_identifier; ?>">

                <label><?php echo $lang_filter_moderate; ?></label>
                <select name="filter_moderate">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="default" <?php if ($filter_moderate == 'default') { echo 'selected'; } ?>><?php echo $lang_text_default; ?></option>
                    <option value="never" <?php if ($filter_moderate == 'never') { echo 'selected'; } ?>><?php echo $lang_text_never; ?></option>
                    <option value="always" <?php if ($filter_moderate == 'always') { echo 'selected'; } ?>><?php echo $lang_text_always; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_reference; ?></label>
                <input type="text" name="filter_reference" value="<?php echo $filter_reference; ?>">

                <label><?php echo $lang_filter_form_enabled; ?></label>
                <select name="filter_is_form_enabled">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="1" <?php if ($filter_is_form_enabled == '1') { echo 'selected'; } ?>><?php echo $lang_text_yes; ?></option>
                    <option value="0" <?php if ($filter_is_form_enabled == '0') { echo 'selected'; } ?>><?php echo $lang_text_no; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_url; ?></label>
                <input type="text" name="filter_url" value="<?php echo $filter_url; ?>">

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/pages" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th><a href="<?php echo $sort_identifier; ?>" <?php if ($sort == 'p.identifier') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_identifier; ?></a></th>
                        <th><a href="<?php echo $sort_reference; ?>" <?php if ($sort == 'p.reference') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_reference; ?></a></th>
                        <th><a href="<?php echo $sort_url; ?>" <?php if ($sort == 'p.url') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_url; ?></a></th>
                        <th><a href="<?php echo $sort_comments; ?>" <?php if ($sort == 'comments') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_comments; ?></a></th>
                        <th><a href="<?php echo $sort_subscriptions; ?>" <?php if ($sort == 'subscriptions') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_subscriptions; ?></a></th>
                        <th><a href="<?php echo $sort_moderate; ?>" <?php if ($sort == 'p.moderate') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_moderate; ?></a></th>
                        <th><a href="<?php echo $sort_is_form_enabled; ?>" <?php if ($sort == 'p.is_form_enabled') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_form_enabled; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'p.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pages) { ?>
                        <?php foreach ($pages as $page) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $page['id']; ?>"></td>
                                <td data-th="<?php echo $lang_column_identifier; ?>:"><?php echo $page['identifier']; ?></td>
                                <td data-th="<?php echo $lang_column_reference; ?>:"><?php echo $page['reference']; ?></td>
                                <td data-th="<?php echo $lang_column_url; ?>:"><a href="<?php echo $page['url']; ?>" target="_blank"><?php echo $page['url']; ?></a></td>
                                <td data-th="<?php echo $lang_column_comments; ?>:"><a href="<?php echo $page['comments_url']; ?>"><?php echo $page['comments']; ?></a></td>
                                <td data-th="<?php echo $lang_column_subscriptions; ?>:"><a href="<?php echo $page['subscriptions_url']; ?>"><?php echo $page['subscriptions']; ?></a></td>
                                <td data-th="<?php echo $lang_column_moderate; ?>:"><?php echo $page['moderate']; ?></td>
                                <td data-th="<?php echo $lang_column_form_enabled; ?>:"><?php echo $page['is_form_enabled']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $page['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $page['action_view']; ?>" target="_blank"><img src="<?php echo $button_view; ?>" class="button_view" title="<?php echo $lang_button_view; ?>"></a>
                                    <a href="<?php echo $page['action_edit']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $page['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
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