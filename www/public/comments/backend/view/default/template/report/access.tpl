<?php echo $header; ?>

<div id="report_access_page">

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

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_ip_address; ?></label>
                <input type="text" name="filter_ip_address" value="<?php echo $filter_ip_address; ?>">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_page; ?></label>
                <input type="text" name="filter_page" value="<?php echo $filter_page; ?>">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <div class="table_container">
        <table class="table">
            <thead>
                <tr>
                    <th><a href="<?php echo $sort_username; ?>" <?php if ($sort == 'a.username') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_username; ?></a></th>
                    <th><a href="<?php echo $sort_ip_address; ?>" <?php if ($sort == 'a.ip_address') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_ip_address; ?></a></th>
                    <th><a href="<?php echo $sort_page; ?>" <?php if ($sort == 'a.page') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_page; ?></a></th>
                    <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'a.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($views) { ?>
                    <?php foreach ($views as $view) { ?>
                        <tr>
                            <td data-th="<?php echo $lang_column_username; ?>:"><?php echo $view['username']; ?></td>
                            <td data-th="<?php echo $lang_column_ip_address; ?>:"><?php echo $view['ip_address']; ?></td>
                            <td data-th="<?php echo $lang_column_page; ?>:"><?php echo $view['page']; ?></td>
                            <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $view['date_added']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="no_results" colspan="4"><?php echo $lang_text_no_results; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="pagination_stats"><?php echo $pagination_stats; ?></div>

    <div class="pagination_links"><?php echo $pagination_links; ?></div>

</div>

<?php echo $footer; ?>