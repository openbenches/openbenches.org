<?php echo $header; ?>

<div id="manage_sites_page">

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
            </div>

            <div class="column">
                <label><?php echo $lang_filter_domain; ?></label>
                <input type="text" name="filter_domain" value="<?php echo $filter_domain; ?>">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/sites" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th><a href="<?php echo $sort_name; ?>" <?php if ($sort == 's.name') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_name; ?></a></th>
                        <th><a href="<?php echo $sort_domain; ?>" <?php if ($sort == 's.domain') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_domain; ?></a></th>
                        <th><a href="<?php echo $sort_url; ?>" <?php if ($sort == 's.url') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_url; ?></a></th>
                        <th><a href="<?php echo $sort_pages; ?>" <?php if ($sort == 'pages') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_pages; ?></a></th>
                        <th><a href="<?php echo $sort_comments; ?>" <?php if ($sort == 'comments') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_comments; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 's.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($sites) { ?>
                        <?php foreach ($sites as $site) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $site['id']; ?>"></td>
                                <td data-th="<?php echo $lang_column_name; ?>:"><?php echo $site['name']; ?></td>
                                <td data-th="<?php echo $lang_column_domain; ?>:"><?php echo $site['domain']; ?></td>
                                <td data-th="<?php echo $lang_column_url; ?>:"><a href="<?php echo $site['url']; ?>" target="_blank"><?php echo $site['url']; ?></a></td>
                                <td data-th="<?php echo $lang_column_pages; ?>:"><?php echo $site['pages']; ?></td>
                                <td data-th="<?php echo $lang_column_comments; ?>:"><?php echo $site['comments']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $site['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $site['action_view']; ?>" target="_blank"><img src="<?php echo $button_view; ?>" class="button_view" title="<?php echo $lang_button_view; ?>"></a>
                                    <a href="<?php echo $site['action_edit']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $site['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
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