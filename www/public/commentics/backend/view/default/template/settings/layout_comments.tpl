<?php echo $header; ?>

<div id="settings_layout_comments_page">

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

    <form action="index.php?route=settings/layout_comments" class="controls" method="post">
        <div class="general_element">
            <div>
                <h2><?php echo $lang_subheading_general; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_display_count; ?></label>
                    <input type="checkbox" name="show_comment_count" value="1" <?php if ($show_comment_count) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_display_count; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_count_replies; ?></label>
                    <input type="checkbox" name="count_replies" value="1" <?php if ($count_replies) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_count_replies; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default_order; ?></label>
                    <select name="comments_order">
                        <option value="1" <?php if ($comments_order == '1') { echo 'selected'; } ?>><?php echo $lang_select_newest; ?></option>
                        <option value="2" <?php if ($comments_order == '2') { echo 'selected'; } ?>><?php echo $lang_select_oldest; ?></option>
                        <option value="3" <?php if ($comments_order == '3') { echo 'selected'; } ?>><?php echo $lang_select_likes; ?></option>
                        <option value="4" <?php if ($comments_order == '4') { echo 'selected'; } ?>><?php echo $lang_select_dislikes; ?></option>
                        <option value="5" <?php if ($comments_order == '5') { echo 'selected'; } ?>><?php echo $lang_select_positive; ?></option>
                        <option value="6" <?php if ($comments_order == '6') { echo 'selected'; } ?>><?php echo $lang_select_critical; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_default_order; ?>">[?]</a>
                    <?php if ($error_comments_order) { ?>
                        <span class="error"><?php echo $error_comments_order; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_positions; ?></label>
                    <div class="position_block">
                        <div class="row row_1">
                            <div class="left">
                                <select name="comments_position_1">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_1 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_1) { ?>
                                    <span class="error"><?php echo $error_comments_position_1; ?></span>
                                <?php } ?>
                            </div>

                            <div class="center">
                                <select name="comments_position_2">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_2 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_2) { ?>
                                    <span class="error"><?php echo $error_comments_position_2; ?></span>
                                <?php } ?>
                            </div>

                            <div class="right">
                                <select name="comments_position_3">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_3 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_3) { ?>
                                    <span class="error"><?php echo $error_comments_position_3; ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row row_2">
                            <div class="left">
                                <select name="comments_position_4">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_4 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_4) { ?>
                                    <span class="error"><?php echo $error_comments_position_4; ?></span>
                                <?php } ?>
                            </div>

                            <div class="center">
                                <select name="comments_position_5">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_5 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_5) { ?>
                                    <span class="error"><?php echo $error_comments_position_5; ?></span>
                                <?php } ?>
                            </div>

                            <div class="right">
                                <select name="comments_position_6">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_6 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_6) { ?>
                                    <span class="error"><?php echo $error_comments_position_6; ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="comments">
                            <?php echo $lang_text_comments; ?>
                        </div>

                        <div class="row row_3">
                            <div class="left">
                                <select name="comments_position_7">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_7 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_7) { ?>
                                    <span class="error"><?php echo $error_comments_position_7; ?></span>
                                <?php } ?>
                            </div>

                            <div class="center">
                                <select name="comments_position_8">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_8 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_8) { ?>
                                    <span class="error"><?php echo $error_comments_position_8; ?></span>
                                <?php } ?>
                            </div>

                            <div class="right">
                                <select name="comments_position_9">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_9 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_9) { ?>
                                    <span class="error"><?php echo $error_comments_position_9; ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row row_4">
                            <div class="left">
                                <select name="comments_position_10">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_10 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_10) { ?>
                                    <span class="error"><?php echo $error_comments_position_10; ?></span>
                                <?php } ?>
                            </div>

                            <div class="center">
                                <select name="comments_position_11">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_11 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_11) { ?>
                                    <span class="error"><?php echo $error_comments_position_11; ?></span>
                                <?php } ?>
                            </div>

                            <div class="right">
                                <select name="comments_position_12">
                                    <?php foreach ($elements as $element) { ?>
                                        <option value="<?php echo $element['value']; ?>" <?php if (!$element['enabled']) { echo 'disabled'; } else if ($comments_position_12 == $element['value']) { echo 'selected'; } ?>><?php echo $element['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php if ($error_comments_position_12) { ?>
                                    <span class="error"><?php echo $error_comments_position_12; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="element_heading"><?php echo $lang_subheading_outside; ?> <span class="element_heading_extra"><?php echo $lang_subheading_positions; ?></span></div>

        <div class="elements">
            <div class="<?php echo ($show_average_rating ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_average_rating; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_average_rating" value="1" <?php if ($show_average_rating) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_average_rating; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_guest; ?></label>
                    <input type="checkbox" name="average_rating_guest" value="1" <?php if ($average_rating_guest) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_guest; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_custom ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_custom; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_custom" value="1" <?php if ($show_custom) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_custom; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_content; ?></label>
                    <input type="text" name="custom_content" class="medium" value="<?php echo $custom_content; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_custom_content; ?>">[?]</a>
                    <?php if ($error_custom_content) { ?>
                        <span class="error"><?php echo $error_custom_content; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($show_notify ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_notify; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_notify" value="1" <?php if ($show_notify) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_notify; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_online ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_online; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_online" value="1" <?php if ($show_online) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_online; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_refresh; ?></label>
                    <input type="checkbox" name="online_refresh_enabled" value="1" <?php if ($online_refresh_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_refresh; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_interval; ?></label>
                    <input type="text" required name="online_refresh_interval" class="small" value="<?php echo $online_refresh_interval; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_seconds; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_interval; ?>">[?]</a>
                    <?php if ($error_online_refresh_interval) { ?>
                        <span class="error"><?php echo $error_online_refresh_interval; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($show_pagination || $show_page_number ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_pagination; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_pagination" value="1" <?php if ($show_pagination) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_pagination; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_type; ?></label>
                    <select name="pagination_type">
                        <option value="multiple" <?php if ($pagination_type == 'multiple') { echo 'selected'; } ?>><?php echo $lang_select_multiple; ?></option>
                        <option value="button" <?php if ($pagination_type == 'button') { echo 'selected'; } ?>><?php echo $lang_select_button; ?></option>
                        <option value="infinite" <?php if ($pagination_type == 'infinite') { echo 'selected'; } ?>><?php echo $lang_select_infinite; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_type; ?>">[?]</a>
                    <?php if ($error_pagination_type) { ?>
                        <span class="error"><?php echo $error_pagination_type; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_amount; ?></label>
                    <input type="text" required name="pagination_amount" class="small" value="<?php echo $pagination_amount; ?>" maxlength="3">
                    <a class="hint" data-hint="<?php echo $lang_hint_amount; ?>">[?]</a>
                    <?php if ($error_pagination_amount) { ?>
                        <span class="error"><?php echo $error_pagination_amount; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset range_section">
                    <label><?php echo $lang_entry_range; ?></label>
                    <input type="text" required name="pagination_range" class="small" value="<?php echo $pagination_range; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_range; ?>">[?]</a>
                    <?php if ($error_pagination_range) { ?>
                        <span class="error"><?php echo $error_pagination_range; ?></span>
                    <?php } ?>
                </div>

                <h2><?php echo $lang_subheading_page_number; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_page_number" value="1" <?php if ($show_page_number) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_page_number; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_format; ?></label>
                    <select name="page_number_format">
                        <option value="Page X" <?php if ($page_number_format == 'Page X') { echo 'selected'; } ?>><?php echo $lang_select_page_x; ?></option>
                        <option value="Page X of Y" <?php if ($page_number_format == 'Page X of Y') { echo 'selected'; } ?>><?php echo $lang_select_page_x_of_y; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_format; ?>">[?]</a>
                    <?php if ($error_page_number_format) { ?>
                        <span class="error"><?php echo $error_page_number_format; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($show_rss ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_rss; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_rss" value="1" <?php if ($show_rss) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_rss; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="rss_new_window" value="1" <?php if ($rss_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_limit_items; ?></label>
                    <input type="checkbox" name="rss_limit_enabled" value="1" <?php if ($rss_limit_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_limit_items; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_limit_amount; ?></label>
                    <input type="text" required name="rss_limit_amount" class="small" value="<?php echo $rss_limit_amount; ?>" maxlength="3">
                    <a class="hint" data-hint="<?php echo $lang_hint_limit_amount; ?>">[?]</a>
                    <?php if ($error_rss_limit_amount) { ?>
                        <span class="error"><?php echo $error_rss_limit_amount; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($show_search ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_search; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_search" value="1" <?php if ($show_search) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_search; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_social ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_social; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_social" value="1" <?php if ($show_social) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_social; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="social_new_window" value="1" <?php if ($social_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['digg']; ?>" title="<?php echo $lang_title_digg; ?>"></label>
                    <input type="checkbox" name="show_social_digg" value="1" <?php if ($show_social_digg) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['facebook']; ?>" title="<?php echo $lang_title_facebook; ?>"></label>
                    <input type="checkbox" name="show_social_facebook" value="1" <?php if ($show_social_facebook) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['linkedin']; ?>" title="<?php echo $lang_title_linkedin; ?>"></label>
                    <input type="checkbox" name="show_social_linkedin" value="1" <?php if ($show_social_linkedin) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['reddit']; ?>" title="<?php echo $lang_title_reddit; ?>"></label>
                    <input type="checkbox" name="show_social_reddit" value="1" <?php if ($show_social_reddit) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['twitter']; ?>" title="<?php echo $lang_title_twitter; ?>"></label>
                    <input type="checkbox" name="show_social_twitter" value="1" <?php if ($show_social_twitter) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $socials['weibo']; ?>" title="<?php echo $lang_title_weibo; ?>"></label>
                    <input type="checkbox" name="show_social_weibo" value="1" <?php if ($show_social_weibo) { echo 'checked'; } ?>>
                </div>
            </div>

            <div class="<?php echo ($show_sort_by ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_sort_by; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_sort_by" value="1" <?php if ($show_sort_by) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_1; ?></label>
                    <input type="checkbox" name="show_sort_by_1" value="1" <?php if ($show_sort_by_1) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_1; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_2; ?></label>
                    <input type="checkbox" name="show_sort_by_2" value="1" <?php if ($show_sort_by_2) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_2; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_3; ?></label>
                    <input type="checkbox" name="show_sort_by_3" value="1" <?php if ($show_sort_by_3) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_3; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_4; ?></label>
                    <input type="checkbox" name="show_sort_by_4" value="1" <?php if ($show_sort_by_4) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_4; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_5; ?></label>
                    <input type="checkbox" name="show_sort_by_5" value="1" <?php if ($show_sort_by_5) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_5; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_sort_by_6; ?></label>
                    <input type="checkbox" name="show_sort_by_6" value="1" <?php if ($show_sort_by_6) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_sort_by_6; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_topic ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_topic; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_topic" value="1" <?php if ($show_topic) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_topic; ?>">[?]</a>
                </div>
            </div>
        </div>

        <div class="element_heading"><?php echo $lang_subheading_inside; ?></div>

        <div class="elements">
            <div class="<?php echo ($avatar_type != '' ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_avatar; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_type; ?></label>
                    <select name="avatar_type">
                        <option value="" <?php if ($avatar_type == '') { echo 'selected'; } ?>><?php echo $lang_select_none; ?></option>
                        <option value="gravatar" <?php if ($avatar_type == 'gravatar') { echo 'selected'; } ?>><?php echo $lang_select_gravatar; ?></option>
                        <option value="login" <?php if ($avatar_type == 'login') { echo 'selected'; } ?>><?php echo $lang_select_login; ?></option>
                        <option value="selection" <?php if ($avatar_type == 'selection') { echo 'selected'; } ?>><?php echo $lang_select_selection; ?></option>
                        <option value="upload" <?php if ($avatar_type == 'upload') { echo 'selected'; } ?>><?php echo $lang_select_upload; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_type; ?>">[?]</a>
                    <?php if ($error_avatar_type) { ?>
                        <span class="error"><?php echo $error_avatar_type; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_gravatar_section">
                    <label><?php echo $lang_entry_default; ?></label>
                    <select name="gravatar_default">
                        <option value="" <?php if ($gravatar_default == 'default') { echo 'selected'; } ?>><?php echo $lang_select_default; ?></option>
                        <option value="custom" <?php if ($gravatar_default == 'custom') { echo 'selected'; } ?>><?php echo $lang_select_custom; ?></option>
                        <option value="mm" <?php if ($gravatar_default == 'mm') { echo 'selected'; } ?>><?php echo $lang_select_mm; ?></option>
                        <option value="identicon" <?php if ($gravatar_default == 'identicon') { echo 'selected'; } ?>><?php echo $lang_select_identicon; ?></option>
                        <option value="monsterid" <?php if ($gravatar_default == 'monsterid') { echo 'selected'; } ?>><?php echo $lang_select_monsterid; ?></option>
                        <option value="wavatar" <?php if ($gravatar_default == 'wavatar') { echo 'selected'; } ?>><?php echo $lang_select_wavatar; ?></option>
                        <option value="retro" <?php if ($gravatar_default == 'retro') { echo 'selected'; } ?>><?php echo $lang_select_retro; ?></option>
                        <option value="robohash" <?php if ($gravatar_default == 'robohash') { echo 'selected'; } ?>><?php echo $lang_select_robohash; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_gravatar_default; ?>">[?]</a>
                    <?php if ($error_gravatar_default) { ?>
                        <span class="error"><?php echo $error_gravatar_default; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_gravatar_section gravatar_custom_section">
                    <label><?php echo $lang_entry_custom; ?></label>
                    <input type="text" name="gravatar_custom" class="large" value="<?php echo $gravatar_custom; ?>" placeholder="http://" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_gravatar_custom; ?>">[?]</a>
                    <?php if ($error_gravatar_custom) { ?>
                        <span class="error"><?php echo $error_gravatar_custom; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_gravatar_section">
                    <label><?php echo $lang_entry_size; ?></label>
                    <input type="text" required name="gravatar_size" class="small" value="<?php echo $gravatar_size; ?>" maxlength="4">
                    <span class="note"><?php echo $lang_note_pixels; ?></span>
                    <?php if ($error_gravatar_size) { ?>
                        <span class="error"><?php echo $error_gravatar_size; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_gravatar_section">
                    <label><?php echo $lang_entry_audience; ?></label>
                    <select name="gravatar_audience">
                        <option value="g" <?php if ($gravatar_audience == 'g') { echo 'selected'; } ?>>G</option>
                        <option value="pg" <?php if ($gravatar_audience == 'pg') { echo 'selected'; } ?>>PG</option>
                        <option value="r" <?php if ($gravatar_audience == 'r') { echo 'selected'; } ?>>R</option>
                        <option value="x" <?php if ($gravatar_audience == 'x') { echo 'selected'; } ?>>X</option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_gravatar_audience; ?>">[?]</a>
                    <?php if ($error_gravatar_audience) { ?>
                        <span class="error"><?php echo $error_gravatar_audience; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_selection_section">
                    <label><?php echo $lang_entry_attribution; ?></label>
                    <input type="text" name="avatar_selection_attribution" class="medium" value="<?php echo $avatar_selection_attribution; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_selection_attribution; ?>">[?]</a>
                    <?php if ($error_avatar_selection_attribution) { ?>
                        <span class="error"><?php echo $error_avatar_selection_attribution; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_upload_section">
                    <label><?php echo $lang_entry_min_posts; ?></label>
                    <input type="text" required name="avatar_upload_min_posts" class="small" value="<?php echo $avatar_upload_min_posts; ?>" maxlength="3">
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_upload_min_posts; ?>">[?]</a>
                    <?php if ($error_avatar_upload_min_posts) { ?>
                        <span class="error"><?php echo $error_avatar_upload_min_posts; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_upload_section">
                    <label><?php echo $lang_entry_min_days; ?></label>
                    <input type="text" required name="avatar_upload_min_days" class="small" value="<?php echo $avatar_upload_min_days; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_days; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_upload_min_days; ?>">[?]</a>
                    <?php if ($error_avatar_upload_min_days) { ?>
                        <span class="error"><?php echo $error_avatar_upload_min_days; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_upload_section">
                    <label><?php echo $lang_entry_max_size; ?></label>
                    <input type="text" required name="avatar_upload_max_size" class="small" value="<?php echo $avatar_upload_max_size; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_mb; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_upload_max_size; ?>">[?]</a>
                    <?php if ($error_avatar_upload_max_size) { ?>
                        <span class="error"><?php echo $error_avatar_upload_max_size; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset avatar_upload_section">
                    <label><?php echo $lang_entry_approve; ?></label>
                    <input type="checkbox" name="avatar_upload_approve" value="1" <?php if ($avatar_upload_approve) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_upload_approve; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_user_link; ?></label>
                    <input type="checkbox" name="avatar_user_link" value="1" <?php if ($avatar_user_link) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_user_link; ?>">[?]</a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_link_days; ?></label>
                    <input type="text" required name="avatar_link_days" class="small" value="<?php echo $avatar_link_days; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_days; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_avatar_link_days; ?>">[?]</a>
                    <?php if ($error_avatar_link_days) { ?>
                        <span class="error"><?php echo $error_avatar_link_days; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_show_level; ?></label>
                    <input type="checkbox" name="show_level" value="1" <?php if ($show_level) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_show_level; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_level_5; ?></label>
                    <input type="text" required name="level_5" class="small" value="<?php echo $level_5; ?>" maxlength="5">
                    <a class="hint" data-hint="<?php echo $lang_hint_level_5; ?>">[?]</a>
                    <?php if ($error_level_5) { ?>
                        <span class="error"><?php echo $error_level_5; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_level_4; ?></label>
                    <input type="text" required name="level_4" class="small" value="<?php echo $level_4; ?>" maxlength="5">
                    <?php if ($error_level_4) { ?>
                        <span class="error"><?php echo $error_level_4; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_level_3; ?></label>
                    <input type="text" required name="level_3" class="small" value="<?php echo $level_3; ?>" maxlength="5">
                    <?php if ($error_level_3) { ?>
                        <span class="error"><?php echo $error_level_3; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_level_2; ?></label>
                    <input type="text" required name="level_2" class="small" value="<?php echo $level_2; ?>" maxlength="5">
                    <?php if ($error_level_2) { ?>
                        <span class="error"><?php echo $error_level_2; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_level_1; ?></label>
                    <input type="text" required name="level_1" class="small" value="<?php echo $level_1; ?>" maxlength="5">
                    <?php if ($error_level_1) { ?>
                        <span class="error"><?php echo $error_level_1; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_level_0; ?></label>
                    <input type="text" required name="level_0" class="small" value="<?php echo $level_0; ?>" maxlength="5">
                    <?php if ($error_level_0) { ?>
                        <span class="error"><?php echo $error_level_0; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_show_bio; ?></label>
                    <input type="checkbox" name="show_bio" value="1" <?php if ($show_bio) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_show_bio; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_top_poster; ?></label>
                    <input type="checkbox" name="show_badge_top_poster" value="1" <?php if ($show_badge_top_poster) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_top_poster; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_most_likes; ?></label>
                    <input type="checkbox" name="show_badge_most_likes" value="1" <?php if ($show_badge_most_likes) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_most_likes; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_first_poster; ?></label>
                    <input type="checkbox" name="show_badge_first_poster" value="1" <?php if ($show_badge_first_poster) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_first_poster; ?>">[?]</a>
                </div>
            </div>

            <div class="element_enabled">
                <h2><?php echo $lang_subheading_name; ?></h2>
                <div class="fieldset">
                    <label><?php echo $lang_entry_show_says; ?></label>
                    <input type="checkbox" name="show_says" value="1" <?php if ($show_says) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_show_says; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_website ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_website; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_website" value="1" <?php if ($show_website) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_website; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="website_new_window" value="1" <?php if ($website_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_no_follow; ?></label>
                    <input type="checkbox" name="website_no_follow" value="1" <?php if ($website_no_follow) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_no_follow; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_town || $show_state || $show_country ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_town; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_town" value="1" <?php if ($show_town) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_town; ?>">[?]</a>
                </div>

                <h2><?php echo $lang_subheading_state; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_state" value="1" <?php if ($show_state) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_state; ?>">[?]</a>
                </div>

                <h2><?php echo $lang_subheading_country; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_country" value="1" <?php if ($show_country) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_country; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_headline ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_headline; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_headline" value="1" <?php if ($show_headline) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_headline; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_rating ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_rating; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_rating" value="1" <?php if ($show_rating) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_rating; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_date ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_date; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_date" value="1" <?php if ($show_date) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_date; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_auto; ?></label>
                    <input type="checkbox" name="date_auto" value="1" <?php if ($date_auto) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_date_auto; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_like || $show_dislike ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_like; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_like" value="1" <?php if ($show_like) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_like; ?>">[?]</a>
                </div>

                <h2><?php echo $lang_subheading_dislike; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_dislike" value="1" <?php if ($show_dislike) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_dislike; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_share ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_share; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_share" value="1" <?php if ($show_share) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_share; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="share_new_window" value="1" <?php if ($share_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['digg']; ?>" title="<?php echo $lang_title_digg; ?>"></label>
                    <input type="checkbox" name="show_share_digg" value="1" <?php if ($show_share_digg) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['facebook']; ?>" title="<?php echo $lang_title_facebook; ?>"></label>
                    <input type="checkbox" name="show_share_facebook" value="1" <?php if ($show_share_facebook) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['linkedin']; ?>" title="<?php echo $lang_title_linkedin; ?>"></label>
                    <input type="checkbox" name="show_share_linkedin" value="1" <?php if ($show_share_linkedin) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['reddit']; ?>" title="<?php echo $lang_title_reddit; ?>"></label>
                    <input type="checkbox" name="show_share_reddit" value="1" <?php if ($show_share_reddit) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['twitter']; ?>" title="<?php echo $lang_title_twitter; ?>"></label>
                    <input type="checkbox" name="show_share_twitter" value="1" <?php if ($show_share_twitter) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $shares['weibo']; ?>" title="<?php echo $lang_title_weibo; ?>"></label>
                    <input type="checkbox" name="show_share_weibo" value="1" <?php if ($show_share_weibo) { echo 'checked'; } ?>>
                </div>
            </div>

            <div class="<?php echo ($show_flag ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_flag; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_flag" value="1" <?php if ($show_flag) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_flag; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_max_per_user; ?></label>
                    <input type="text" required name="flag_max_per_user" class="small" value="<?php echo $flag_max_per_user; ?>" maxlength="4">
                    <a class="hint" data-hint="<?php echo $lang_hint_flag_max_per_user; ?>">[?]</a>
                    <?php if ($error_flag_max_per_user) { ?>
                        <span class="error"><?php echo $error_flag_max_per_user; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_min_per_comment; ?></label>
                    <input type="text" required name="flag_min_per_comment" class="small" value="<?php echo $flag_min_per_comment; ?>" maxlength="4">
                    <a class="hint" data-hint="<?php echo $lang_hint_flag_min_per_comment; ?>">[?]</a>
                    <?php if ($error_flag_min_per_comment) { ?>
                        <span class="error"><?php echo $error_flag_min_per_comment; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_disapprove; ?></label>
                    <input type="checkbox" name="flag_disapprove" value="1" <?php if ($flag_disapprove) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_flag_disapprove; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_edit ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_edit; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_edit" value="1" <?php if ($show_edit) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_edit; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_max_edits; ?></label>
                    <input type="text" required name="max_edits" class="small" value="<?php echo $max_edits; ?>" maxlength="4">
                    <a class="hint" data-hint="<?php echo $lang_hint_max_edits; ?>">[?]</a>
                    <?php if ($error_max_edits) { ?>
                        <span class="error"><?php echo $error_max_edits; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($show_delete ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_delete; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_delete" value="1" <?php if ($show_delete) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_delete; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_permalink ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_permalink; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_permalink" value="1" <?php if ($show_permalink) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_permalink; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($show_reply ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_reply; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="show_reply" value="1" <?php if ($show_reply) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_reply; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_quick_reply; ?></label>
                    <input type="checkbox" name="quick_reply" value="1" <?php if ($quick_reply) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_quick_reply; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_hide; ?></label>
                    <input type="checkbox" name="hide_replies" value="1" <?php if ($hide_replies) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_hide_replies; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_depth; ?></label>
                    <input type="text" required name="reply_depth" class="small" value="<?php echo $reply_depth; ?>" maxlength="3">
                    <a class="hint" data-hint="<?php echo $lang_hint_reply_depth; ?>">[?]</a>
                    <?php if ($error_reply_depth) { ?>
                        <span class="error"><?php echo $error_reply_depth; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>
    </form>

    <?php if ($layout_detect && $layout_settings) { ?>
        <div id="layout_settings_dialog" title="<?php echo $lang_dialog_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_content; ?>

            <ul>
                <?php foreach ($layout_settings as $layout_setting) { ?>
                    <li><?php echo $layout_setting; ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

</div>

<?php echo $footer; ?>