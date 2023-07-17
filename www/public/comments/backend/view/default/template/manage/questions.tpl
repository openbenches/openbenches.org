<?php echo $header; ?>

<div id="manage_questions_page">

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
                <label><?php echo $lang_filter_question; ?></label>
                <input type="text" name="filter_question" value="<?php echo $filter_question; ?>">

                <label><?php echo $lang_filter_date; ?></label>
                <input type="text" class="datepicker" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_answer; ?></label>
                <input type="text" name="filter_answer" value="<?php echo $filter_answer; ?>">
            </div>

            <div class="column">
                <label><?php echo $lang_filter_language; ?></label>
                <input type="text" name="filter_language" value="<?php echo $filter_language; ?>">

                <input type="button" id="filter" class="button" value="<?php echo $lang_button_filter; ?>" title="<?php echo $lang_button_filter; ?>">
            </div>
        </div>
    </div>

    <form action="index.php?route=manage/questions" class="controls" method="post">
        <div class="table_container">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th><a href="<?php echo $sort_question; ?>" <?php if ($sort == 'q.question') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_question; ?></a></th>
                        <th><a href="<?php echo $sort_answer; ?>" <?php if ($sort == 'q.answer') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_answer; ?></a></th>
                        <th><a href="<?php echo $sort_language; ?>" <?php if ($sort == 'q.language') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_language; ?></a></th>
                        <th><a href="<?php echo $sort_date; ?>" <?php if ($sort == 'q.date_added') { echo 'class="' . $order . '"'; } ?>><?php echo $lang_column_date; ?></a></th>
                        <th><?php echo $lang_column_action; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($questions) { ?>
                        <?php foreach ($questions as $question) { ?>
                            <tr>
                                <td class="selector"><input type="checkbox" name="bulk[]" value="<?php echo $question['id']; ?>"></td>
                                <td data-th="<?php echo $lang_column_question; ?>:"><?php echo $question['question']; ?></td>
                                <td data-th="<?php echo $lang_column_answer; ?>:"><?php echo $question['answer']; ?></td>
                                <td data-th="<?php echo $lang_column_language; ?>:"><?php echo $question['language']; ?></td>
                                <td data-th="<?php echo $lang_column_date; ?>:"><?php echo $question['date_added']; ?></td>
                                <td class="actions">
                                    <a href="<?php echo $question['action']; ?>"><img src="<?php echo $button_edit; ?>" class="button_edit" title="<?php echo $lang_button_edit; ?>"></a>
                                    <a data-id="<?php echo $question['id']; ?>" class="single_delete"><img src="<?php echo $button_delete; ?>" class="button_delete" title="<?php echo $lang_button_delete; ?>"></a>
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