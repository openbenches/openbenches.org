<?php echo $header; ?>

<div id="data_list_page">

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

    <div class="selection">
        <select>
            <?php foreach ($types as $selection => $url) { ?>
                <option value="index.php?route=data/list&amp;type=<?php echo $url; ?>" <?php if ($url == $type) { echo 'selected'; } ?>><?php echo $selection; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="description"><?php echo $lang_description; ?></div>

    <div class="clear"></div>

    <div class="guide hide">
        <?php echo $lang_text_wildcard; ?>

        <ul>
            <?php if ($group == 'words') { ?>
                <li>New <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>New* <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>castle <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>*castle <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>cast <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>*cast* <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
            <?php } ?>

            <?php if ($group == 'emails') { ?>
                <li>test <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>tester <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>somesite <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesite. <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesites <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>some <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesite.com <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>test@somesite <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>test@somesite.com <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>test@somesite.net <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
            <?php } ?>

            <?php if ($group == 'websites') { ?>
                <li>somesite <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesite. <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesites <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
                <li>some <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesite.com <span class="found">(<?php echo $lang_text_found; ?>)</span></li>
                <li>somesite.net <span class="not_found">(<?php echo $lang_text_not_found; ?>)</span></li>
            <?php } ?>
        </ul>

        <?php echo $lang_text_case; ?>

        <?php echo $lang_text_lines; ?>
    </div>

    <form action="index.php?route=data/list&amp;type=<?php echo $type; ?>" class="controls" method="post">
        <textarea name="text"><?php echo $text; ?></textarea>
        <?php if ($error_text) { ?>
            <span class="error"><?php echo $error_text; ?></span>
        <?php } ?>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <div class="modified"><?php echo $lang_text_modified_by; ?></div>

        <div class="buttons"><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></div>

        <div class="links"><a id="back"><?php echo $lang_link_back; ?></a></div>
    </form>

</div>

<?php echo $footer; ?>