<!DOCTYPE html>
<html>
<head>
<title>Installer</title>
<meta name="robots" content="noindex">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo $stylesheet; ?>">
<script src="<?php echo $jquery; ?>"></script>
<script src="<?php echo $common; ?>"></script>
</head>
<body>
<header>
    <img src="<?php echo $logo; ?>" class="logo" title="Commentics" alt="Commentics">

    <div class="steps">
        <div class="step <?php if ($page == '1') { echo 'active'; } ?>"><?php echo $lang_heading_welcome; ?></div>
        <div class="step <?php if ($page == '2') { echo 'active'; } ?>"><?php echo $lang_heading_database; ?></div>
        <div class="step <?php if ($page == '3') { echo 'active'; } ?>"><?php echo $lang_heading_system; ?></div>
        <div class="step <?php if ($page == '4') { echo 'active'; } ?>"><?php echo $lang_heading_menu; ?></div>
        <div class="step <?php if ($page == '5') { echo 'active'; } ?>"><?php echo $lang_heading_action; ?></div>
        <div class="step <?php if ($page == '6') { echo 'active'; } ?>"><?php echo $lang_heading_done; ?></div>
    </div>
</header>
<main>