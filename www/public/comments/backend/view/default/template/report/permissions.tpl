<?php echo $header; ?>

<div id="report_permissions_page">

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

    <div class="table_container">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo $lang_column_file; ?></th>
                    <th><?php echo $lang_column_information; ?></th>
                    <th><?php echo $lang_column_result; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file) { ?>
                    <tr>
                        <td data-th="<?php echo $lang_column_file; ?>:"><?php echo $file['path']; ?></td>
                        <td data-th="<?php echo $lang_column_information; ?>:"><?php echo $file['information']; ?></td>
                        <?php if ($file['positive']) { ?>
                            <td class="positive" data-th="<?php echo $lang_column_result; ?>:"><?php echo $file['text']; ?></td>
                        <?php } else { ?>
                            <td class="negative" data-th="<?php echo $lang_column_result; ?>:"><?php echo $file['text']; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php echo $footer; ?>