<?php echo $header; ?>

<div id="report_phpinfo_page">

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

    <?php
    ob_start();

    phpinfo();

    preg_match('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);

    echo "<div class='phpinfodisplay'><style type='text/css'>\n",
    join("\n",
    array_map(
        function ($i) {
            return ".phpinfodisplay " . preg_replace("/,/", ",.phpinfodisplay ", $i);
        },
        preg_split('/\n/', $matches[1])
    )
    ),
    "</style>\n",
    $matches[2],
    "\n</div>\n";
    ?>

</div>

<?php echo $footer; ?>