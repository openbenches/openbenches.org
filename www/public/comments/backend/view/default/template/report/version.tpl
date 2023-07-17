<?php echo $header; ?>

<div id="report_version_page">

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
                <label><?php echo $lang_filter_version; ?></label>
                <input type="text" name="filter_version" value="<?php echo $filter_version; ?>">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_type; ?></label>
                <select name="filter_type">
                    <option value=""><?php echo $lang_select_select; ?></option>
                    <option value="installation" <?php if ($filter_type == 'installation') { echo 'selected'; } ?>><?php echo $lang_select_installation; ?></option>
                    <option value="upgrade" <?php if ($filter_type == 'upgrade') { echo 'selected'; } ?>><?php echo $lang_select_upgrade; ?></option>
                </select>
            </div>

            <div class="column">
                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <div class="table_container">
        <table class="table">
            <thead>
                <tr>
                    <th><a href="<?php echo $sort_version; ?>" <?php if ($sort == 'v.version') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_version; ?></a></th>
                    <th><a href="<?php echo $sort_type; ?>" <?php if ($sort == 'v.type') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_type; ?></a></th>
                    <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'v.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($versions) { ?>
                    <?php foreach ($versions as $version) { ?>
                        <tr>
                            <td data-th="<?php echo $lang_column_version; ?>:"><?php echo $version['version']; ?></td>
                            <td data-th="<?php echo $lang_column_type; ?>:"><?php echo $version['type']; ?></td>
                            <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $version['date_added']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="no_results" colspan="3"><?php echo $lang_text_no_results; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="pagination_stats"><?php echo $pagination_stats; ?></div>

    <div class="pagination_links"><?php echo $pagination_links; ?></div>

</div>

<?php echo $footer; ?>