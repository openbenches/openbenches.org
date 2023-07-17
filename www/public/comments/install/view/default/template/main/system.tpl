<?php echo $header; ?>

<div id="system_page">

    <?php if (!$check['continue']) { ?>
        <div class="error"><?php echo $lang_error_failure; ?></div>
    <?php } ?>

    <p><?php echo $lang_text_system_check; ?></p>

    <div class="row">
        <label class="item"><?php echo $lang_item_php_version; ?></label>
        <?php if ($check['php_version']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_php_version; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_mysql_version; ?></label>
        <?php if ($check['mysql_version']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_mysql_version; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_php_session; ?></label>
        <?php if ($check['php_session']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_php_session; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_ctype_loaded; ?></label>
        <?php if ($check['ctype_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_ctype_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_filter_loaded; ?></label>
        <?php if ($check['filter_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_filter_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_json_loaded; ?></label>
        <?php if ($check['json_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="red"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_json_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_curl_loaded; ?></label>
        <?php if ($check['curl_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_curl_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_mbstring_loaded; ?></label>
        <?php if ($check['mbstring_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_mbstring_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_gd_loaded; ?></label>
        <?php if ($check['gd_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_gd_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_dom_loaded; ?></label>
        <?php if ($check['dom_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_dom_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_libxml_loaded; ?></label>
        <?php if ($check['libxml_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_libxml_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_openssl_loaded; ?></label>
        <?php if ($check['openssl_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_openssl_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_zip_loaded; ?></label>
        <?php if ($check['zip_loaded']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_zip_loaded; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_fopen_enabled; ?></label>
        <?php if ($check['fopen_enabled']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_fopen_enabled; ?></span>
        <?php } ?>
    </div>

    <div class="row">
        <label class="item"><?php echo $lang_item_freetype_enabled; ?></label>
        <?php if ($check['freetype_enabled']) { ?>
            <span class="green"><?php echo $lang_text_pass; ?></span>
        <?php } else { ?>
            <span class="amber"><?php echo $lang_text_fail; ?></span> <span class="note"><?php echo $lang_note_freetype_enabled; ?></span>
        <?php } ?>
    </div>

    <?php if ($check['continue']) { ?>
        <form action="index.php?route=system" method="post">
            <input type="submit" class="button" value="<?php echo $lang_button_continue; ?>" title="<?php echo $lang_button_continue; ?>">
        </form>
    <?php } ?>

</div>

<?php echo $footer; ?>