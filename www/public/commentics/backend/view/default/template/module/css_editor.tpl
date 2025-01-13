<?php echo $header; ?>

<div id="module_css_editor_page">

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

    <form action="index.php?route=module/css_editor" class="controls" method="post">
        <div class="elements">
            <div>
                <h2><?php echo $lang_subheading_general; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_background_color; ?></label>
                    <input type="color" name="css_editor_general_background_color" value="<?php echo $css_editor_general_background_color; ?>">
                    <?php if ($error_css_editor_general_background_color) { ?>
                        <span class="error"><?php echo $error_css_editor_general_background_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_foreground_color; ?></label>
                    <input type="color" name="css_editor_general_foreground_color" value="<?php echo $css_editor_general_foreground_color; ?>">
                    <?php if ($error_css_editor_general_foreground_color) { ?>
                        <span class="error"><?php echo $error_css_editor_general_foreground_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_family; ?></label>
                    <select name="css_editor_general_font_family">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_families as $font_family) { ?>
                            <option value="<?php echo $font_family; ?>" <?php if ($css_editor_general_font_family == $font_family) { echo 'selected'; } ?>><?php echo $font_family; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_general_font_family) { ?>
                        <span class="error"><?php echo $error_css_editor_general_font_family; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_size; ?></label>
                    <select name="css_editor_general_font_size">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_sizes as $font_size) { ?>
                            <option value="<?php echo $font_size; ?>" <?php if ($css_editor_general_font_size == $font_size) { echo 'selected'; } ?>><?php echo $font_size; ?> pixels</option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_general_font_size) { ?>
                        <span class="error"><?php echo $error_css_editor_general_font_size; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2><?php echo $lang_subheading_heading; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_background_color; ?></label>
                    <input type="color" name="css_editor_heading_background_color" value="<?php echo $css_editor_heading_background_color; ?>">
                    <?php if ($error_css_editor_heading_background_color) { ?>
                        <span class="error"><?php echo $error_css_editor_heading_background_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_foreground_color; ?></label>
                    <input type="color" name="css_editor_heading_foreground_color" value="<?php echo $css_editor_heading_foreground_color; ?>">
                    <?php if ($error_css_editor_heading_foreground_color) { ?>
                        <span class="error"><?php echo $error_css_editor_heading_foreground_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_family; ?></label>
                    <select name="css_editor_heading_font_family">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_families as $font_family) { ?>
                            <option value="<?php echo $font_family; ?>" <?php if ($css_editor_heading_font_family == $font_family) { echo 'selected'; } ?>><?php echo $font_family; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_heading_font_family) { ?>
                        <span class="error"><?php echo $error_css_editor_heading_font_family; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_size; ?></label>
                    <select name="css_editor_heading_font_size">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_sizes as $font_size) { ?>
                            <option value="<?php echo $font_size; ?>" <?php if ($css_editor_heading_font_size == $font_size) { echo 'selected'; } ?>><?php echo $font_size; ?> pixels</option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_heading_font_size) { ?>
                        <span class="error"><?php echo $error_css_editor_heading_font_size; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2><?php echo $lang_subheading_link; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_background_color; ?></label>
                    <input type="color" name="css_editor_link_background_color" value="<?php echo $css_editor_link_background_color; ?>">
                    <?php if ($error_css_editor_link_background_color) { ?>
                        <span class="error"><?php echo $error_css_editor_link_background_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_foreground_color; ?></label>
                    <input type="color" name="css_editor_link_foreground_color" value="<?php echo $css_editor_link_foreground_color; ?>">
                    <?php if ($error_css_editor_link_foreground_color) { ?>
                        <span class="error"><?php echo $error_css_editor_link_foreground_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_family; ?></label>
                    <select name="css_editor_link_font_family">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_families as $font_family) { ?>
                            <option value="<?php echo $font_family; ?>" <?php if ($css_editor_link_font_family == $font_family) { echo 'selected'; } ?>><?php echo $font_family; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_link_font_family) { ?>
                        <span class="error"><?php echo $error_css_editor_link_font_family; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_size; ?></label>
                    <select name="css_editor_link_font_size">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_sizes as $font_size) { ?>
                            <option value="<?php echo $font_size; ?>" <?php if ($css_editor_link_font_size == $font_size) { echo 'selected'; } ?>><?php echo $font_size; ?> pixels</option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_link_font_size) { ?>
                        <span class="error"><?php echo $error_css_editor_link_font_size; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2><?php echo $lang_subheading_primary; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_background_color; ?></label>
                    <input type="color" name="css_editor_primary_button_background_color" value="<?php echo $css_editor_primary_button_background_color; ?>">
                    <?php if ($error_css_editor_primary_button_background_color) { ?>
                        <span class="error"><?php echo $error_css_editor_primary_button_background_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_foreground_color; ?></label>
                    <input type="color" name="css_editor_primary_button_foreground_color" value="<?php echo $css_editor_primary_button_foreground_color; ?>">
                    <?php if ($error_css_editor_primary_button_foreground_color) { ?>
                        <span class="error"><?php echo $error_css_editor_primary_button_foreground_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_family; ?></label>
                    <select name="css_editor_primary_button_font_family">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_families as $font_family) { ?>
                            <option value="<?php echo $font_family; ?>" <?php if ($css_editor_primary_button_font_family == $font_family) { echo 'selected'; } ?>><?php echo $font_family; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_primary_button_font_family) { ?>
                        <span class="error"><?php echo $error_css_editor_primary_button_font_family; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_size; ?></label>
                    <select name="css_editor_primary_button_font_size">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_sizes as $font_size) { ?>
                            <option value="<?php echo $font_size; ?>" <?php if ($css_editor_primary_button_font_size == $font_size) { echo 'selected'; } ?>><?php echo $font_size; ?> pixels</option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_primary_button_font_size) { ?>
                        <span class="error"><?php echo $error_css_editor_primary_button_font_size; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2><?php echo $lang_subheading_secondary; ?></h2>

                <div class="fieldset">
                    <label><?php echo $lang_entry_background_color; ?></label>
                    <input type="color" name="css_editor_secondary_button_background_color" value="<?php echo $css_editor_secondary_button_background_color; ?>">
                    <?php if ($error_css_editor_secondary_button_background_color) { ?>
                        <span class="error"><?php echo $error_css_editor_secondary_button_background_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_foreground_color; ?></label>
                    <input type="color" name="css_editor_secondary_button_foreground_color" value="<?php echo $css_editor_secondary_button_foreground_color; ?>">
                    <?php if ($error_css_editor_secondary_button_foreground_color) { ?>
                        <span class="error"><?php echo $error_css_editor_secondary_button_foreground_color; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_family; ?></label>
                    <select name="css_editor_secondary_button_font_family">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_families as $font_family) { ?>
                            <option value="<?php echo $font_family; ?>" <?php if ($css_editor_secondary_button_font_family == $font_family) { echo 'selected'; } ?>><?php echo $font_family; ?></option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_secondary_button_font_family) { ?>
                        <span class="error"><?php echo $error_css_editor_secondary_button_font_family; ?></span>
                    <?php } ?>
                </div>

                <div class="fieldset">
                    <label><?php echo $lang_entry_font_size; ?></label>
                    <select name="css_editor_secondary_button_font_size">
                        <option value=""><?php echo $lang_select_select; ?></option>
                        <?php foreach ($font_sizes as $font_size) { ?>
                            <option value="<?php echo $font_size; ?>" <?php if ($css_editor_secondary_button_font_size == $font_size) { echo 'selected'; } ?>><?php echo $font_size; ?> pixels</option>
                        <?php } ?>
                    </select>
                    <?php if ($error_css_editor_secondary_button_font_size) { ?>
                        <span class="error"><?php echo $error_css_editor_secondary_button_font_size; ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div><?php echo $lang_entry_editor; ?></div>

        <textarea name="css"><?php echo $css; ?></textarea>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></p>

        <div class="links"><a href="<?php echo $link_back; ?>"><?php echo $lang_link_back; ?></a></div>        
    </form>

</div>

<?php echo $footer; ?>