<?php echo $header; ?>

<div id="settings_processor_page">

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

    <form action="index.php?route=settings/processor" class="controls" method="post">
        <div id="tabs">
            <ul>
                <li><a href="#tab-name"><?php echo $lang_tab_name; ?></a></li>
                <li><a href="#tab-email"><?php echo $lang_tab_email; ?></a></li>
                <li><a href="#tab-town"><?php echo $lang_tab_town; ?></a></li>
                <li><a href="#tab-website"><?php echo $lang_tab_website; ?></a></li>
                <li><a href="#tab-comment"><?php echo $lang_tab_comment; ?></a></li>
                <li><a href="#tab-headline"><?php echo $lang_tab_headline; ?></a></li>
                <li><a href="#tab-notify"><?php echo $lang_tab_notify; ?></a></li>
                <li><a href="#tab-cookie"><?php echo $lang_tab_cookie; ?></a></li>
                <li><a href="#tab-other"><?php echo $lang_tab_other; ?></a></li>
            </ul>

            <div id="tab-name">
                <div class="fieldset">
                    <label><?php echo $lang_entry_one_name; ?></label>
                    <input type="checkbox" name="one_name_enabled" value="1" <?php if ($one_name_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_one_name; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_fix_name; ?></label>
                    <input type="checkbox" name="fix_name_enabled" value="1" <?php if ($fix_name_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_fix_name; ?>">[?]</a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_unique_name; ?></label>
                    <input type="checkbox" name="unique_name_enabled" value="1" <?php if ($unique_name_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_unique_name; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_detect_links; ?></label>
                    <input type="checkbox" name="detect_link_in_name_enabled" value="1" <?php if ($detect_link_in_name_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_detect_links; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="link_in_name_action">
                        <option value="error" <?php if ($link_in_name_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($link_in_name_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($link_in_name_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_link_in_name_action) { ?>
                        <span class="error"><?php echo $error_link_in_name_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_reserved_name; ?></label>
                    <input type="checkbox" name="reserved_names_enabled" value="1" <?php if ($reserved_names_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_reserved_names; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="reserved_names_action">
                        <option value="error" <?php if ($reserved_names_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($reserved_names_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($reserved_names_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_reserved_names_action) { ?>
                        <span class="error"><?php echo $error_reserved_names_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_dummy_name; ?></label>
                    <input type="checkbox" name="dummy_names_enabled" value="1" <?php if ($dummy_names_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_dummy_names; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="dummy_names_action">
                        <option value="error" <?php if ($dummy_names_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($dummy_names_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($dummy_names_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_dummy_names_action) { ?>
                        <span class="error"><?php echo $error_dummy_names_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_name; ?></label>
                    <input type="checkbox" name="banned_names_enabled" value="1" <?php if ($banned_names_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_names; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_names_action">
                        <option value="error" <?php if ($banned_names_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_names_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_names_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_names_action) { ?>
                        <span class="error"><?php echo $error_banned_names_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-email">
                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_unique_email; ?></label>
                    <input type="checkbox" name="unique_email_enabled" value="1" <?php if ($unique_email_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_unique_email; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_reserved_email; ?></label>
                    <input type="checkbox" name="reserved_emails_enabled" value="1" <?php if ($reserved_emails_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_reserved_emails; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="reserved_emails_action">
                        <option value="error" <?php if ($reserved_emails_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($reserved_emails_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($reserved_emails_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_reserved_emails_action) { ?>
                        <span class="error"><?php echo $error_reserved_emails_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_dummy_email; ?></label>
                    <input type="checkbox" name="dummy_emails_enabled" value="1" <?php if ($dummy_emails_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_dummy_emails; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="dummy_emails_action">
                        <option value="error" <?php if ($dummy_emails_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($dummy_emails_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($dummy_emails_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_dummy_emails_action) { ?>
                        <span class="error"><?php echo $error_dummy_emails_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_email; ?></label>
                    <input type="checkbox" name="banned_emails_enabled" value="1" <?php if ($banned_emails_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_emails; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_emails_action">
                        <option value="error" <?php if ($banned_emails_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_emails_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_emails_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_emails_action) { ?>
                        <span class="error"><?php echo $error_banned_emails_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-town">
                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_fix_town; ?></label>
                    <input type="checkbox" name="fix_town_enabled" value="1" <?php if ($fix_town_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_fix_town; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_detect_links; ?></label>
                    <input type="checkbox" name="detect_link_in_town_enabled" value="1" <?php if ($detect_link_in_town_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_detect_links; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="link_in_town_action">
                        <option value="error" <?php if ($link_in_town_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($link_in_town_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($link_in_town_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_link_in_town_action) { ?>
                        <span class="error"><?php echo $error_link_in_town_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_reserved_town; ?></label>
                    <input type="checkbox" name="reserved_towns_enabled" value="1" <?php if ($reserved_towns_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_reserved_towns; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="reserved_towns_action">
                        <option value="error" <?php if ($reserved_towns_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($reserved_towns_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($reserved_towns_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_reserved_towns_action) { ?>
                        <span class="error"><?php echo $error_reserved_towns_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_dummy_town; ?></label>
                    <input type="checkbox" name="dummy_towns_enabled" value="1" <?php if ($dummy_towns_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_dummy_towns; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="dummy_towns_action">
                        <option value="error" <?php if ($dummy_towns_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($dummy_towns_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($dummy_towns_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_dummy_towns_action) { ?>
                        <span class="error"><?php echo $error_dummy_towns_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_town; ?></label>
                    <input type="checkbox" name="banned_towns_enabled" value="1" <?php if ($banned_towns_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_towns; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_towns_action">
                        <option value="error" <?php if ($banned_towns_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_towns_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_towns_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_towns_action) { ?>
                        <span class="error"><?php echo $error_banned_towns_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-website">
                <div class="fieldset">
                    <label><?php echo $lang_entry_approve; ?></label>
                    <input type="checkbox" name="approve_websites" value="1" <?php if ($approve_websites) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_approve_websites; ?>">[?]</a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_ping; ?></label>
                    <input type="checkbox" name="validate_website_ping" value="1" <?php if ($validate_website_ping) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_validate_website_ping; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_reserved_website; ?></label>
                    <input type="checkbox" name="reserved_websites_enabled" value="1" <?php if ($reserved_websites_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_reserved_websites; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="reserved_websites_action">
                        <option value="error" <?php if ($reserved_websites_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($reserved_websites_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($reserved_websites_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_reserved_websites_action) { ?>
                        <span class="error"><?php echo $error_reserved_websites_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_dummy_website; ?></label>
                    <input type="checkbox" name="dummy_websites_enabled" value="1" <?php if ($dummy_websites_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_dummy_websites; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="dummy_websites_action">
                        <option value="error" <?php if ($dummy_websites_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($dummy_websites_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($dummy_websites_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_dummy_websites_action) { ?>
                        <span class="error"><?php echo $error_dummy_websites_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_website; ?></label>
                    <input type="checkbox" name="banned_websites_as_website_enabled" value="1" <?php if ($banned_websites_as_website_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_websites; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_websites_as_website_action">
                        <option value="error" <?php if ($banned_websites_as_website_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_websites_as_website_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_websites_as_website_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_websites_as_website_action) { ?>
                        <span class="error"><?php echo $error_banned_websites_as_website_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-comment">
                <div class="fieldset">
                    <label><?php echo $lang_entry_approve_images; ?></label>
                    <input type="checkbox" name="approve_images" value="1" <?php if ($approve_images) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_approve_images; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_approve_videos; ?></label>
                    <input type="checkbox" name="approve_videos" value="1" <?php if ($approve_videos) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_approve_videos; ?>">[?]</a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_approve_uploads; ?></label>
                    <input type="checkbox" name="approve_uploads" value="1" <?php if ($approve_uploads) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_approve_uploads; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_convert_links; ?></label>
                    <input type="checkbox" name="comment_convert_links" value="1" <?php if ($comment_convert_links) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_convert_links; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_convert_emails; ?></label>
                    <input type="checkbox" name="comment_convert_emails" value="1" <?php if ($comment_convert_emails) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_convert_emails; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="comment_links_new_window" value="1" <?php if ($comment_links_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_no_follow; ?></label>
                    <input type="checkbox" name="comment_links_nofollow" value="1" <?php if ($comment_links_nofollow) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_no_follow; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_minimum_characters; ?></label>
                    <input type="text" required name="comment_minimum_characters" class="small" value="<?php echo $comment_minimum_characters; ?>" maxlength="3">
                    <?php if ($error_comment_minimum_characters) { ?>
                        <span class="error"><?php echo $error_comment_minimum_characters; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_minimum_words; ?></label>
                    <input type="text" required name="comment_minimum_words" class="small" value="<?php echo $comment_minimum_words; ?>" maxlength="3">
                    <?php if ($error_comment_minimum_words) { ?>
                        <span class="error"><?php echo $error_comment_minimum_words; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum_characters; ?></label>
                    <input type="text" required name="comment_maximum_characters" class="small_plus" value="<?php echo $comment_maximum_characters; ?>" maxlength="5">
                    <?php if ($error_comment_maximum_characters) { ?>
                        <span class="error"><?php echo $error_comment_maximum_characters; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum_lines; ?></label>
                    <input type="text" required name="comment_maximum_lines" class="small" value="<?php echo $comment_maximum_lines; ?>" maxlength="5">
                    <?php if ($error_comment_maximum_lines) { ?>
                        <span class="error"><?php echo $error_comment_maximum_lines; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_maximum_smilies; ?></label>
                    <input type="text" required name="comment_maximum_smilies" class="small" value="<?php echo $comment_maximum_smilies; ?>" maxlength="3">
                    <?php if ($error_comment_maximum_smilies) { ?>
                        <span class="error"><?php echo $error_comment_maximum_smilies; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_long_word; ?></label>
                    <input type="text" required name="comment_long_word" class="small" value="<?php echo $comment_long_word; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <?php if ($error_comment_long_word) { ?>
                        <span class="error"><?php echo $error_comment_long_word; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_line_breaks; ?></label>
                    <input type="checkbox" name="comment_line_breaks" value="1" <?php if ($comment_line_breaks) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_line_breaks; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_detect_links; ?></label>
                    <input type="checkbox" name="detect_link_in_comment_enabled" value="1" <?php if ($detect_link_in_comment_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_detect_links; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="link_in_comment_action">
                        <option value="error" <?php if ($link_in_comment_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($link_in_comment_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($link_in_comment_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_link_in_comment_action) { ?>
                        <span class="error"><?php echo $error_link_in_comment_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_website; ?></label>
                    <input type="checkbox" name="banned_websites_as_comment_enabled" value="1" <?php if ($banned_websites_as_comment_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_websites; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_websites_as_comment_action">
                        <option value="error" <?php if ($banned_websites_as_comment_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_websites_as_comment_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_websites_as_comment_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_websites_as_comment_action) { ?>
                        <span class="error"><?php echo $error_banned_websites_as_comment_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-headline">
                <div class="fieldset">
                    <label><?php echo $lang_entry_minimum_characters; ?></label>
                    <input type="text" required name="headline_minimum_characters" class="small" value="<?php echo $headline_minimum_characters; ?>" maxlength="3">
                    <?php if ($error_headline_minimum_characters) { ?>
                        <span class="error"><?php echo $error_headline_minimum_characters; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_minimum_words; ?></label>
                    <input type="text" required name="headline_minimum_words" class="small" value="<?php echo $headline_minimum_words; ?>" maxlength="3">
                    <?php if ($error_headline_minimum_words) { ?>
                        <span class="error"><?php echo $error_headline_minimum_words; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_maximum_characters; ?></label>
                    <input type="text" required name="headline_maximum_characters" class="small" value="<?php echo $headline_maximum_characters; ?>" maxlength="3">
                    <?php if ($error_headline_maximum_characters) { ?>
                        <span class="error"><?php echo $error_headline_maximum_characters; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_detect_links; ?></label>
                    <input type="checkbox" name="detect_link_in_headline_enabled" value="1" <?php if ($detect_link_in_headline_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_detect_links; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="link_in_headline_action">
                        <option value="error" <?php if ($link_in_headline_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($link_in_headline_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($link_in_headline_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_link_in_headline_action) { ?>
                        <span class="error"><?php echo $error_link_in_headline_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_banned_website; ?></label>
                    <input type="checkbox" name="banned_websites_as_headline_enabled" value="1" <?php if ($banned_websites_as_headline_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_banned_websites; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="banned_websites_as_headline_action">
                        <option value="error" <?php if ($banned_websites_as_headline_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($banned_websites_as_headline_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($banned_websites_as_headline_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_banned_websites_as_headline_action) { ?>
                        <span class="error"><?php echo $error_banned_websites_as_headline_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-notify">
                <div class="fieldset">
                    <label><?php echo $lang_entry_default_type; ?></label>
                    <select name="notify_type">
                        <option value="all" <?php if ($notify_type == 'all') { echo 'selected'; } ?>><?php echo $lang_select_all; ?></option>
                        <option value="custom" <?php if ($notify_type == 'custom') { echo 'selected'; } ?>><?php echo $lang_select_custom; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_notify_type; ?>">[?]</a>
                    <?php if ($error_notify_type) { ?>
                        <span class="error"><?php echo $error_notify_type; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default_format; ?></label>
                    <select name="notify_format">
                        <option value="html" <?php if ($notify_format == 'html') { echo 'selected'; } ?>><?php echo $lang_select_html; ?></option>
                        <option value="text" <?php if ($notify_format == 'text') { echo 'selected'; } ?>><?php echo $lang_select_text; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_notify_format; ?>">[?]</a>
                    <?php if ($error_notify_format) { ?>
                        <span class="error"><?php echo $error_notify_format; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-cookie">
                <div class="fieldset">
                    <label><?php echo $lang_entry_form_cookie; ?></label>
                    <input type="checkbox" name="form_cookie" value="1" <?php if ($form_cookie) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_form_cookie; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_days; ?></label>
                    <input type="text" required name="form_cookie_days" class="small" value="<?php echo $form_cookie_days; ?>" maxlength="4">
                    <a class="hint" data-hint="<?php echo $lang_hint_cookie_days; ?>">[?]</a>
                    <?php if ($error_form_cookie_days) { ?>
                        <span class="error"><?php echo $error_form_cookie_days; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div id="tab-other">
                <div class="fieldset">
                    <label><?php echo $lang_entry_max_capitals; ?></label>
                    <input type="checkbox" name="check_capitals_enabled" value="1" <?php if ($check_capitals_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_check_capitals; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_percentage; ?></label>
                    <input type="text" required name="check_capitals_percentage" class="small" value="<?php echo $check_capitals_percentage; ?>" maxlength="3">
                    <span class="note">%</span>
                    <?php if ($error_check_capitals_percentage) { ?>
                        <span class="error"><?php echo $error_check_capitals_percentage; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="check_capitals_action">
                        <option value="error" <?php if ($check_capitals_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($check_capitals_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($check_capitals_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_check_capitals_action) { ?>
                        <span class="error"><?php echo $error_check_capitals_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_detect_repeats; ?></label>
                    <input type="checkbox" name="check_repeats_enabled" value="1" <?php if ($check_repeats_enabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_check_repeats; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_amount; ?></label>
                    <input type="text" required name="check_repeats_amount" class="small" value="<?php echo $check_repeats_amount; ?>" maxlength="3">
                    <a class="hint" data-hint="<?php echo $lang_hint_repeats_amount; ?>">[?]</a>
                    <?php if ($error_check_repeats_amount) { ?>
                        <span class="error"><?php echo $error_check_repeats_amount; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="check_repeats_action">
                        <option value="error" <?php if ($check_repeats_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($check_repeats_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($check_repeats_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_check_repeats_action) { ?>
                        <span class="error"><?php echo $error_check_repeats_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_spam_words; ?></label>
                    <input type="checkbox" name="spam_words_enabled" value="1" <?php if ($spam_words_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_spam_words; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="spam_words_action">
                        <option value="error" <?php if ($spam_words_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($spam_words_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($spam_words_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_spam_words_action) { ?>
                        <span class="error"><?php echo $error_spam_words_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_mild_swear_words; ?></label>
                    <input type="checkbox" name="mild_swear_words_enabled" value="1" <?php if ($mild_swear_words_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_mild_swear_words; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="mild_swear_words_action">
                        <option value="mask" <?php if ($mild_swear_words_action == 'mask') { echo 'selected'; } ?>><?php echo $lang_select_mask; ?></option>
                        <option value="mask_approve" <?php if ($mild_swear_words_action == 'mask_approve') { echo 'selected'; } ?>><?php echo $lang_select_mask_approve; ?></option>
                        <option value="error" <?php if ($mild_swear_words_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($mild_swear_words_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($mild_swear_words_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_mild_swear_words_action) { ?>
                        <span class="error"><?php echo $error_mild_swear_words_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_strong_swear_words; ?></label>
                    <input type="checkbox" name="strong_swear_words_enabled" value="1" <?php if ($strong_swear_words_enabled) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_strong_swear_words; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <div class="fieldset divide_after">
                    <label><?php echo $lang_entry_action; ?></label>
                    <select name="strong_swear_words_action">
                        <option value="mask" <?php if ($strong_swear_words_action == 'mask') { echo 'selected'; } ?>><?php echo $lang_select_mask; ?></option>
                        <option value="mask_approve" <?php if ($strong_swear_words_action == 'mask_approve') { echo 'selected'; } ?>><?php echo $lang_select_mask_approve; ?></option>
                        <option value="error" <?php if ($strong_swear_words_action == 'error') { echo 'selected'; } ?>><?php echo $lang_select_error; ?></option>
                        <option value="approve" <?php if ($strong_swear_words_action == 'approve') { echo 'selected'; } ?>><?php echo $lang_select_approve; ?></option>
                        <option value="ban" <?php if ($strong_swear_words_action == 'ban') { echo 'selected'; } ?>><?php echo $lang_select_ban; ?></option>
                    </select>
                    <?php if ($error_strong_swear_words_action) { ?>
                        <span class="error"><?php echo $error_strong_swear_words_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_mask; ?></label>
                    <input type="text" required name="swear_word_masking" class="medium" value="<?php echo $swear_word_masking; ?>" maxlength="10">
                    <a class="hint" data-hint="<?php echo $lang_hint_mask; ?>">[?]</a>
                    <?php if ($error_swear_word_masking) { ?>
                        <span class="error"><?php echo $error_swear_word_masking; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>

    </form>

</div>

<?php echo $footer; ?>