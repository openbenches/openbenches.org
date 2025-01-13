<?php echo $header; ?>

<div id="settings_layout_form_page">

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

    <form action="index.php?route=settings/layout_form" class="controls" method="post">
        <div class="general_element">
            <div>
                <h2><?php echo $lang_subheading_general; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_form" value="1" <?php if ($enabled_form) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_enabled_form; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_hide_form; ?></label>
                    <input type="checkbox" name="hide_form" value="1" <?php if ($hide_form) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_hide_form; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_js_disabled; ?></label>
                    <input type="checkbox" name="display_javascript_disabled" value="1" <?php if ($display_javascript_disabled) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_js_disabled; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required_symbol; ?></label>
                    <input type="checkbox" name="display_required_symbol" value="1" <?php if ($display_required_symbol) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required_symbol; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required_text; ?></label>
                    <input type="checkbox" name="display_required_text" value="1" <?php if ($display_required_text) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required_text; ?>">[?]</a>
                </div>

                <p><?php echo $lang_text_fields; ?></p>

                <div class="sortable">
                    <ul id="sortable">
                        <?php foreach ($fields as $key => $value) { ?>
                            <li data-id="<?php echo $key; ?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $value; ?></li>
                        <?php } ?>
                    </ul>
                    <?php if ($error_order_fields) { ?>
                        <span class="error"><?php echo $error_order_fields; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="elements">
            <div class="<?php echo ($enabled_bb_code ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_bb_code; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_bb_code" value="1" <?php if ($enabled_bb_code) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_bb_code; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['bold']; ?>" title="<?php echo $lang_title_bold; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_bold" value="1" <?php if ($enabled_bb_code_bold) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['italic']; ?>" title="<?php echo $lang_title_italic; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_italic" value="1" <?php if ($enabled_bb_code_italic) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['underline']; ?>" title="<?php echo $lang_title_underline; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_underline" value="1" <?php if ($enabled_bb_code_underline) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['strike']; ?>" title="<?php echo $lang_title_strike; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_strike" value="1" <?php if ($enabled_bb_code_strike) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['superscript']; ?>" title="<?php echo $lang_title_superscript; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_superscript" value="1" <?php if ($enabled_bb_code_superscript) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['subscript']; ?>" title="<?php echo $lang_title_subscript; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_subscript" value="1" <?php if ($enabled_bb_code_subscript) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['code']; ?>" title="<?php echo $lang_title_code; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_code" value="1" <?php if ($enabled_bb_code_code) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['php']; ?>" title="<?php echo $lang_title_php; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_php" value="1" <?php if ($enabled_bb_code_php) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['quote']; ?>" title="<?php echo $lang_title_quote; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_quote" value="1" <?php if ($enabled_bb_code_quote) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['line']; ?>" title="<?php echo $lang_title_line; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_line" value="1" <?php if ($enabled_bb_code_line) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['bullet']; ?>" title="<?php echo $lang_title_bullet; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_bullet" value="1" <?php if ($enabled_bb_code_bullet) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['numeric']; ?>" title="<?php echo $lang_title_numeric; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_numeric" value="1" <?php if ($enabled_bb_code_numeric) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['link']; ?>" title="<?php echo $lang_title_link; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_link" value="1" <?php if ($enabled_bb_code_link) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['email']; ?>" title="<?php echo $lang_title_email; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_email" value="1" <?php if ($enabled_bb_code_email) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['image']; ?>" title="<?php echo $lang_title_image; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_image" value="1" <?php if ($enabled_bb_code_image) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $bb_code['youtube']; ?>" title="<?php echo $lang_title_youtube; ?>"></label>
                    <input type="checkbox" name="enabled_bb_code_youtube" value="1" <?php if ($enabled_bb_code_youtube) { echo 'checked'; } ?>>
                </div>
            </div>

            <div class="<?php echo ($enabled_smilies ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_smilies; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_smilies" value="1" <?php if ($enabled_smilies) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_smilies; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['smile']; ?>" title="<?php echo $lang_title_smile; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_smile" value="1" <?php if ($enabled_smilies_smile) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['sad']; ?>" title="<?php echo $lang_title_sad; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_sad" value="1" <?php if ($enabled_smilies_sad) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['huh']; ?>" title="<?php echo $lang_title_huh; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_huh" value="1" <?php if ($enabled_smilies_huh) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['laugh']; ?>" title="<?php echo $lang_title_laugh; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_laugh" value="1" <?php if ($enabled_smilies_laugh) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['mad']; ?>" title="<?php echo $lang_title_mad; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_mad" value="1" <?php if ($enabled_smilies_mad) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['tongue']; ?>" title="<?php echo $lang_title_tongue; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_tongue" value="1" <?php if ($enabled_smilies_tongue) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['cry']; ?>" title="<?php echo $lang_title_cry; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_cry" value="1" <?php if ($enabled_smilies_cry) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['grin']; ?>" title="<?php echo $lang_title_grin; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_grin" value="1" <?php if ($enabled_smilies_grin) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['wink']; ?>" title="<?php echo $lang_title_wink; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_wink" value="1" <?php if ($enabled_smilies_wink) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['scared']; ?>" title="<?php echo $lang_title_scared; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_scared" value="1" <?php if ($enabled_smilies_scared) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['cool']; ?>" title="<?php echo $lang_title_cool; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_cool" value="1" <?php if ($enabled_smilies_cool) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['sleep']; ?>" title="<?php echo $lang_title_sleep; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_sleep" value="1" <?php if ($enabled_smilies_sleep) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['blush']; ?>" title="<?php echo $lang_title_blush; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_blush" value="1" <?php if ($enabled_smilies_blush) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['confused']; ?>" title="<?php echo $lang_title_confused; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_confused" value="1" <?php if ($enabled_smilies_confused) { echo 'checked'; } ?>>
                </div>

                <div class="fieldset">
                    <label><img src="<?php echo $smilies['shocked']; ?>" title="<?php echo $lang_title_shocked; ?>"></label>
                    <input type="checkbox" name="enabled_smilies_shocked" value="1" <?php if ($enabled_smilies_shocked) { echo 'checked'; } ?>>
                </div>
            </div>

            <div class="element_enabled">
                <h2><?php echo $lang_subheading_comment; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <textarea name="default_comment"><?php echo $default_comment; ?></textarea>
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_comment) { ?>
                        <span class="error"><?php echo $error_default_comment; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="comment_maximum_characters" class="small_plus" value="<?php echo $comment_maximum_characters; ?>" maxlength="5">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_comment_maximum_characters) { ?>
                        <span class="error"><?php echo $error_comment_maximum_characters; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_counter; ?></label>
                    <input type="checkbox" name="enabled_counter" value="1" <?php if ($enabled_counter) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_counter; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_headline ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_headline; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_headline" value="1" <?php if ($enabled_headline) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_headline; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_headline" value="1" <?php if ($required_headline) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="text" name="default_headline" class="medium" value="<?php echo $default_headline; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_headline) { ?>
                        <span class="error"><?php echo $error_default_headline; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="headline_maximum_characters" class="small" value="<?php echo $headline_maximum_characters; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_headline_maximum_characters) { ?>
                        <span class="error"><?php echo $error_headline_maximum_characters; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_upload ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_upload; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_upload" value="1" <?php if ($enabled_upload) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_upload; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_max_size; ?></label>
                    <input type="text" required name="maximum_upload_size" class="small" value="<?php echo $maximum_upload_size; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_mb; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_max_size; ?>">[?]</a>
                    <?php if ($error_maximum_upload_size) { ?>
                        <span class="error"><?php echo $error_maximum_upload_size; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_max_amount; ?></label>
                    <input type="text" required name="maximum_upload_amount" class="small" value="<?php echo $maximum_upload_amount; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_max_amount; ?>">[?]</a>
                    <?php if ($error_maximum_upload_amount) { ?>
                        <span class="error"><?php echo $error_maximum_upload_amount; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_max_total; ?></label>
                    <input type="text" required name="maximum_upload_total" class="small" value="<?php echo $maximum_upload_total; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_mb; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_max_total; ?>">[?]</a>
                    <?php if ($error_maximum_upload_total) { ?>
                        <span class="error"><?php echo $error_maximum_upload_total; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_rating ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_rating; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_rating" value="1" <?php if ($enabled_rating) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_rating; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_rating" value="1" <?php if ($required_rating) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <select name="default_rating">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <option value="1" <?php if ($default_rating == '1') { echo 'selected'; } ?>><?php echo $lang_select_rating_1; ?></option>
                        <option value="2" <?php if ($default_rating == '2') { echo 'selected'; } ?>><?php echo $lang_select_rating_2; ?></option>
                        <option value="3" <?php if ($default_rating == '3') { echo 'selected'; } ?>><?php echo $lang_select_rating_3; ?></option>
                        <option value="4" <?php if ($default_rating == '4') { echo 'selected'; } ?>><?php echo $lang_select_rating_4; ?></option>
                        <option value="5" <?php if ($default_rating == '5') { echo 'selected'; } ?>><?php echo $lang_select_rating_5; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_rating) { ?>
                        <span class="error"><?php echo $error_default_rating; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_repeat; ?></label>
                    <select name="repeat_rating">
                        <option value="normal" <?php if ($repeat_rating == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="hide" <?php if ($repeat_rating == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_repeat; ?>">[?]</a>
                    <?php if ($error_repeat_rating) { ?>
                        <span class="error"><?php echo $error_repeat_rating; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="element_enabled">
                <h2><?php echo $lang_subheading_name; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="text" name="default_name" class="medium" value="<?php echo $default_name; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_name) { ?>
                        <span class="error"><?php echo $error_default_name; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="maximum_name" class="small" value="<?php echo $maximum_name; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_maximum_name) { ?>
                        <span class="error"><?php echo $error_maximum_name; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_name_cookie_action">
                        <option value="normal" <?php if ($filled_name_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_name_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_name_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_name_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_name_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_name_login_action">
                        <option value="normal" <?php if ($filled_name_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_name_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_name_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_name_login_action) { ?>
                        <span class="error"><?php echo $error_filled_name_login_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_email ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_email; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_email" value="1" <?php if ($enabled_email) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_email; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_email" value="1" <?php if ($required_email) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="text" name="default_email" class="medium" value="<?php echo $default_email; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_email) { ?>
                        <span class="error"><?php echo $error_default_email; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="maximum_email" class="small" value="<?php echo $maximum_email; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_maximum_email) { ?>
                        <span class="error"><?php echo $error_maximum_email; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_email_cookie_action">
                        <option value="normal" <?php if ($filled_email_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_email_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_email_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_email_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_email_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_email_login_action">
                        <option value="normal" <?php if ($filled_email_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_email_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_email_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_email_login_action) { ?>
                        <span class="error"><?php echo $error_filled_email_login_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_website ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_website; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_website" value="1" <?php if ($enabled_website) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_website; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_website" value="1" <?php if ($required_website) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="text" name="default_website" class="medium" value="<?php echo $default_website; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_website) { ?>
                        <span class="error"><?php echo $error_default_website; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="maximum_website" class="small" value="<?php echo $maximum_website; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_maximum_website) { ?>
                        <span class="error"><?php echo $error_maximum_website; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_website_cookie_action">
                        <option value="normal" <?php if ($filled_website_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_website_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_website_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_website_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_website_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_website_login_action">
                        <option value="normal" <?php if ($filled_website_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_website_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_website_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_website_login_action) { ?>
                        <span class="error"><?php echo $error_filled_website_login_action; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_town || $enabled_state || $enabled_country ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_town; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_town" value="1" <?php if ($enabled_town) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_town; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_town" value="1" <?php if ($required_town) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="text" name="default_town" class="medium" value="<?php echo $default_town; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_town) { ?>
                        <span class="error"><?php echo $error_default_town; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_maximum; ?></label>
                    <input type="text" required name="maximum_town" class="small" value="<?php echo $maximum_town; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_characters; ?></span>
                    <a class="hint" data-hint="<?php echo $lang_hint_maximum; ?>">[?]</a>
                    <?php if ($error_maximum_town) { ?>
                        <span class="error"><?php echo $error_maximum_town; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_town_cookie_action">
                        <option value="normal" <?php if ($filled_town_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_town_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_town_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_town_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_town_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_town_login_action">
                        <option value="normal" <?php if ($filled_town_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_town_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_town_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_town_login_action) { ?>
                        <span class="error"><?php echo $error_filled_town_login_action; ?></span>
                    <?php } ?>
                </div>

                <h2><?php echo $lang_subheading_state; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_state" value="1" <?php if ($enabled_state) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_state; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_state" value="1" <?php if ($required_state) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <select name="default_state">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <option value="" disabled>---</option>
                        <?php foreach ($states as $state) { ?>
                            <option value="<?php echo $state['id']; ?>" <?php if ($default_state && $state['id'] == $default_state) { echo 'selected'; } ?>><?php echo $state['name']; ?></option>
                        <?php } ?>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_default_state; ?>">[?]</a>
                    <?php if ($error_default_state) { ?>
                        <span class="error"><?php echo $error_default_state; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_state_cookie_action">
                        <option value="normal" <?php if ($filled_state_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_state_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_state_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_state_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_state_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_state_login_action">
                        <option value="normal" <?php if ($filled_state_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_state_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_state_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_state_login_action) { ?>
                        <span class="error"><?php echo $error_filled_state_login_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_states; ?>"><?php echo $lang_link_edit; ?></a>
                </div>

                <h2><?php echo $lang_subheading_country; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_country" value="1" <?php if ($enabled_country) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_country; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_required; ?></label>
                    <input type="checkbox" name="required_country" value="1" <?php if ($required_country) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_required; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <select name="default_country">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country['id']; ?>" <?php if ($default_country && $country['id'] == $default_country) { echo 'selected'; } ?> <?php if ($country['name'] == '---') { echo 'disabled'; } ?>><?php echo $country['name']; ?></option>
                        <?php } ?>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                    <?php if ($error_default_country) { ?>
                        <span class="error"><?php echo $error_default_country; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_cookie; ?></label>
                    <select name="filled_country_cookie_action">
                        <option value="normal" <?php if ($filled_country_cookie_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_country_cookie_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_country_cookie_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_cookie; ?>">[?]</a>
                    <?php if ($error_filled_country_cookie_action) { ?>
                        <span class="error"><?php echo $error_filled_country_cookie_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_filled_login; ?></label>
                    <select name="filled_country_login_action">
                        <option value="normal" <?php if ($filled_country_login_action == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                        <option value="disable" <?php if ($filled_country_login_action == 'disable') { echo 'selected'; } ?>><?php echo $lang_select_disable; ?></option>
                        <option value="hide" <?php if ($filled_country_login_action == 'hide') { echo 'selected'; } ?>><?php echo $lang_select_hide; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_filled_login; ?>">[?]</a>
                    <?php if ($error_filled_country_login_action) { ?>
                        <span class="error"><?php echo $error_filled_country_login_action; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_countries; ?>"><?php echo $lang_link_edit; ?></a>
                </div>
            </div>

            <div class="<?php echo ($enabled_question ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_question; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_question" value="1" <?php if ($enabled_question) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_question; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_list; ?></label>
                    <a href="<?php echo $link_questions; ?>"><?php echo $lang_link_edit; ?></a>
                </div>
            </div>

            <div class="<?php echo ($enabled_captcha ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_captcha; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_captcha" value="1" <?php if ($enabled_captcha) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_captcha; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_type; ?></label>
                    <select name="captcha_type">
                        <option value="recaptcha" <?php if ($captcha_type == 'recaptcha') { echo 'selected'; } ?>>ReCaptcha</option>
                        <option value="image" <?php if ($captcha_type == 'image') { echo 'selected'; } ?>><?php echo $lang_select_image; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_captcha_type; ?>">[?]</a>
                    <?php if ($error_captcha_type) { ?>
                        <span class="error"><?php echo $error_captcha_type; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset recaptcha_section">
                    <label><?php echo $lang_entry_public_key; ?></label>
                    <input type="text" name="recaptcha_public_key" class="large" value="<?php echo $recaptcha_public_key; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_recaptcha_key; ?>">[?]</a>
                    <?php if ($error_recaptcha_public_key) { ?>
                        <span class="error"><?php echo $error_recaptcha_public_key; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset recaptcha_section">
                    <label><?php echo $lang_entry_private_key; ?></label>
                    <input type="text" name="recaptcha_private_key" class="large" value="<?php echo $recaptcha_private_key; ?>" maxlength="250">
                    <a class="hint" data-hint="<?php echo $lang_hint_recaptcha_key; ?>">[?]</a>
                    <?php if ($error_recaptcha_private_key) { ?>
                        <span class="error"><?php echo $error_recaptcha_private_key; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset recaptcha_section">
                    <label><?php echo $lang_entry_theme; ?></label>
                    <select name="recaptcha_theme">
                        <option value="dark" <?php if ($recaptcha_theme == 'dark') { echo 'selected'; } ?>>Dark</option>
                        <option value="light" <?php if ($recaptcha_theme == 'light') { echo 'selected'; } ?>>Light</option>
                    </select>
                    <?php if ($error_recaptcha_theme) { ?>
                        <span class="error"><?php echo $error_recaptcha_theme; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset recaptcha_section">
                    <label><?php echo $lang_entry_size; ?></label>
                    <select name="recaptcha_size">
                        <option value="compact" <?php if ($recaptcha_size == 'compact') { echo 'selected'; } ?>><?php echo $lang_select_compact; ?></option>
                        <option value="normal" <?php if ($recaptcha_size == 'normal') { echo 'selected'; } ?>><?php echo $lang_select_normal; ?></option>
                    </select>
                    <?php if ($error_recaptcha_size) { ?>
                        <span class="error"><?php echo $error_recaptcha_size; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_width; ?></label>
                    <input type="text" required name="captcha_width" class="small" value="<?php echo $captcha_width; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_pixels; ?></span>
                    <?php if ($error_captcha_width) { ?>
                        <span class="error"><?php echo $error_captcha_width; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_height; ?></label>
                    <input type="text" required name="captcha_height" class="small" value="<?php echo $captcha_height; ?>" maxlength="3">
                    <span class="note"><?php echo $lang_note_pixels; ?></span>
                    <?php if ($error_captcha_height) { ?>
                        <span class="error"><?php echo $error_captcha_height; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_length; ?></label>
                    <input type="text" required name="captcha_length" class="small" value="<?php echo $captcha_length; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_length; ?>">[?]</a>
                    <?php if ($error_captcha_length) { ?>
                        <span class="error"><?php echo $error_captcha_length; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_lines; ?></label>
                    <input type="text" required name="captcha_lines" class="small" value="<?php echo $captcha_lines; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_lines; ?>">[?]</a>
                    <?php if ($error_captcha_lines) { ?>
                        <span class="error"><?php echo $error_captcha_lines; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_circles; ?></label>
                    <input type="text" required name="captcha_circles" class="small" value="<?php echo $captcha_circles; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_circles; ?>">[?]</a>
                    <?php if ($error_captcha_circles) { ?>
                        <span class="error"><?php echo $error_captcha_circles; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_squares; ?></label>
                    <input type="text" required name="captcha_squares" class="small" value="<?php echo $captcha_squares; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_squares; ?>">[?]</a>
                    <?php if ($error_captcha_squares) { ?>
                        <span class="error"><?php echo $error_captcha_squares; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_dots; ?></label>
                    <input type="text" required name="captcha_dots" class="small" value="<?php echo $captcha_dots; ?>" maxlength="2">
                    <a class="hint" data-hint="<?php echo $lang_hint_dots; ?>">[?]</a>
                    <?php if ($error_captcha_dots) { ?>
                        <span class="error"><?php echo $error_captcha_dots; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_text_color; ?></label>
                    <input type="color" name="captcha_text_color" value="<?php echo $captcha_text_color; ?>">
                    <?php if ($error_captcha_text_color) { ?>
                        <span class="error"><?php echo $error_captcha_text_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_back_color; ?></label>
                    <input type="color" name="captcha_back_color" value="<?php echo $captcha_back_color; ?>">
                    <?php if ($error_captcha_back_color) { ?>
                        <span class="error"><?php echo $error_captcha_back_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_line_color; ?></label>
                    <input type="color" name="captcha_line_color" value="<?php echo $captcha_line_color; ?>">
                    <?php if ($error_captcha_line_color) { ?>
                        <span class="error"><?php echo $error_captcha_line_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_circle_color; ?></label>
                    <input type="color" name="captcha_circle_color" value="<?php echo $captcha_circle_color; ?>">
                    <?php if ($error_captcha_circle_color) { ?>
                        <span class="error"><?php echo $error_captcha_circle_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_square_color; ?></label>
                    <input type="color" name="captcha_square_color" value="<?php echo $captcha_square_color; ?>">
                    <?php if ($error_captcha_square_color) { ?>
                        <span class="error"><?php echo $error_captcha_square_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset captcha_section">
                    <label><?php echo $lang_entry_dots_color; ?></label>
                    <input type="color" name="captcha_dots_color" value="<?php echo $captcha_dots_color; ?>">
                    <?php if ($error_captcha_dots_color) { ?>
                        <span class="error"><?php echo $error_captcha_dots_color; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="<?php echo ($enabled_notify ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_notify; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_notify" value="1" <?php if ($enabled_notify) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_notify; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="checkbox" name="default_notify" value="1" <?php if ($default_notify) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_cookie ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_cookie; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_cookie" value="1" <?php if ($enabled_cookie) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_cookie; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_default; ?></label>
                    <input type="checkbox" name="default_cookie" value="1" <?php if ($default_cookie) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_default; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_privacy ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_privacy; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_privacy" value="1" <?php if ($enabled_privacy) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_privacy; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_terms ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_terms; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_terms" value="1" <?php if ($enabled_terms) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_terms; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_preview ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_preview; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_preview" value="1" <?php if ($enabled_preview) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_preview; ?>">[?]</a>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_agree; ?></label>
                    <input type="checkbox" name="agree_to_preview" value="1" <?php if ($agree_to_preview) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_agree; ?>">[?]</a>
                </div>
            </div>

            <div class="<?php echo ($enabled_powered_by ? 'element_enabled' : 'element_disabled') ?>">
                <h2><?php echo $lang_subheading_powered; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_enabled; ?></label>
                    <input type="checkbox" name="enabled_powered_by" value="1" <?php if ($enabled_powered_by) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_powered; ?>">[?]</a>
                    <?php if ($error_enabled_powered_by) { ?>
                        <span class="error"><?php echo $error_enabled_powered_by; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_type; ?></label>
                    <select name="powered_by_type">
                        <option value="text" <?php if ($powered_by_type == 'text') { echo 'selected'; } ?>><?php echo $lang_select_text; ?></option>
                        <option value="image" <?php if ($powered_by_type == 'image') { echo 'selected'; } ?>><?php echo $lang_select_image; ?></option>
                    </select>
                    <a class="hint" data-hint="<?php echo $lang_hint_powered_type; ?>">[?]</a>
                    <?php if ($error_powered_by_type) { ?>
                        <span class="error"><?php echo $error_powered_by_type; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_new_window; ?></label>
                    <input type="checkbox" name="powered_by_new_window" value="1" <?php if ($powered_by_new_window) { echo 'checked'; } ?>>
                    <a class="hint" data-hint="<?php echo $lang_hint_new_window; ?>">[?]</a>
                </div>
            </div>
        </div>

        <input type="hidden" data-js="default_state" value="<?php echo $default_state; ?>">

        <input type="hidden" name="order_fields" value="<?php echo $order_fields; ?>">

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