<!DOCTYPE html>
<html>
<head>
<title>Commentics: <?php echo $lang_title; ?></title>
<meta name="robots" content="noindex">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="<?php echo $jquery; ?>"></script>
<script src="<?php echo $jquery_ui; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $jquery_theme; ?>">

<?php if ($full_header) { ?>
    <link rel="stylesheet" type="text/css" href="../3rdparty/flexnav/css/flexnav.css">

    <script src="../3rdparty/flexnav/js/jquery.flexnav.js"></script>

    <?php if ($route == 'main/dashboard' && $chart_enabled) { ?>
        <script src="../3rdparty/chart/chart.min.js"></script>
    <?php } ?>

    <?php if ($wysiwyg_enabled && $route == 'edit/comment') { ?>
        <script src="../3rdparty/summernote/summernote-lite.js"></script>
        <link rel="stylesheet" type="text/css" href="../3rdparty/summernote/summernote-lite.css">
    <?php } ?>

    <?php if ($route == 'edit/comment' || $route == 'extension/themes' || $route == 'tool/text_finder') { ?>
        <link rel="stylesheet" type="text/css" href="../3rdparty/colorbox/colorbox.css">
        <script src="../3rdparty/colorbox/jquery.colorbox-min.js"></script>
    <?php } ?>

    <script src="../3rdparty/hint_script/tooltip.js"></script>

    <script src="<?php echo $common; ?>"></script>
<?php } ?>

    <link rel="stylesheet" type="text/css" href="<?php echo $stylesheet; ?>">
</head>
<body>

<?php if ($full_header) { ?>
    <header>
        <div class="header_top">
            <?php if ($error_header) { ?>
                <div class="error_header"><?php echo $error_header; ?></div>
            <?php } ?>

            <a href="index.php?route=main/dashboard"><img src="<?php echo $logo; ?>" class="logo" title="Commentics" alt="Commentics"/></a>
        </div>

        <div class="wrapper">
            <div class="menu-button"><?php echo $lang_menu; ?></div>
            <nav>
                <ul data-breakpoint="1100" class="flexnav">
                    <li><a href="index.php?route=main/dashboard"><span class="fa fa-tachometer"></span><?php echo $lang_menu_dashboard; ?></a></li>

                    <?php if (!$has_restriction || ($has_restriction && in_array('manage', $viewable_pages))) { ?>

                        <li><a><span class="fa fa-commenting"></span><?php echo $lang_menu_manage; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('manage/admins', $viewable_pages))) { ?> <li><a href="index.php?route=manage/admins"><?php echo $lang_menu_manage_admins; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('manage/comments', $viewable_pages))) { ?> <li><a href="index.php?route=manage/comments"><?php echo $lang_menu_manage_comments; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('manage/pages', $viewable_pages))) { ?> <li><a href="index.php?route=manage/pages"><?php echo $lang_menu_manage_pages; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('manage/sites', $viewable_pages))) { ?> <li><a href="index.php?route=manage/sites"><?php echo $lang_menu_manage_sites; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('manage/users', $viewable_pages))) { ?> <li><a href="index.php?route=manage/users"><?php echo $lang_menu_manage_users; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('extensions', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-plus-circle"></span><?php echo $lang_menu_extensions; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('extension/installer', $viewable_pages))) { ?> <li><a href="index.php?route=extension/installer"><?php echo $lang_menu_extension_installer; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('extension/languages', $viewable_pages))) { ?> <li><a href="index.php?route=extension/languages"><?php echo $lang_menu_extension_languages; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('extension/modules', $viewable_pages))) { ?> <li><a href="index.php?route=extension/modules"><?php echo $lang_menu_extension_modules; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('extension/themes', $viewable_pages))) { ?> <li><a href="index.php?route=extension/themes"><?php echo $lang_menu_extension_themes; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('settings', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-cog"></span><?php echo $lang_menu_settings; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/administrator', $viewable_pages))) { ?> <li><a href="index.php?route=settings/administrator"><?php echo $lang_menu_settings_administrator; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/approval', $viewable_pages))) { ?> <li><a href="index.php?route=settings/approval"><?php echo $lang_menu_settings_approval; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/cache', $viewable_pages))) { ?> <li><a href="index.php?route=settings/cache"><?php echo $lang_menu_settings_cache; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/email', $viewable_pages))) { ?>
                                    <li><a><?php echo $lang_menu_settings_email; ?></a>

                                        <ul>
                                            <?php if (!$has_restriction || ($has_restriction && in_array('settings/email_editor', $viewable_pages))) { ?> <li><a href="index.php?route=settings/email_editor&amp;type=ban" class="indent"><?php echo $lang_menu_settings_email_editor; ?></a></li> <?php } ?>
                                            <?php if (!$has_restriction || ($has_restriction && in_array('settings/email_setup', $viewable_pages))) { ?> <li><a href="index.php?route=settings/email_setup" class="indent"><?php echo $lang_menu_settings_email_setup; ?></a></li> <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/error_reporting', $viewable_pages))) { ?> <li><a href="index.php?route=settings/error_reporting"><?php echo $lang_menu_settings_error_reporting; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/flooding', $viewable_pages))) { ?> <li><a href="index.php?route=settings/flooding"><?php echo $lang_menu_settings_flooding; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/layout', $viewable_pages))) { ?>
                                    <li><a><?php echo $lang_menu_settings_layout; ?></a>

                                        <ul>
                                            <?php if (!$has_restriction || ($has_restriction && in_array('settings/layout_comments', $viewable_pages))) { ?> <li><a href="index.php?route=settings/layout_comments" class="indent"><?php echo $lang_menu_settings_layout_comments; ?></a></li> <?php } ?>
                                            <?php if (!$has_restriction || ($has_restriction && in_array('settings/layout_form', $viewable_pages))) { ?> <li><a href="index.php?route=settings/layout_form" class="indent"><?php echo $lang_menu_settings_layout_form; ?></a></li> <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/licence', $viewable_pages))) { ?> <li><a href="index.php?route=settings/licence"><?php echo $lang_menu_settings_licence; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/maintenance', $viewable_pages))) { ?> <li><a href="index.php?route=settings/maintenance"><?php echo $lang_menu_settings_maintenance; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/processor', $viewable_pages))) { ?> <li><a href="index.php?route=settings/processor"><?php echo $lang_menu_settings_processor; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/security', $viewable_pages))) { ?> <li><a href="index.php?route=settings/security"><?php echo $lang_menu_settings_security; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/system', $viewable_pages))) { ?> <li><a href="index.php?route=settings/system"><?php echo $lang_menu_settings_system; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('settings/viewers', $viewable_pages))) { ?> <li><a href="index.php?route=settings/viewers"><?php echo $lang_menu_settings_viewers; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('tasks', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-list-ul"></span><?php echo $lang_menu_tasks; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('task/delete_bans', $viewable_pages))) { ?> <li><a href="index.php?route=task/delete_bans"><?php echo $lang_menu_task_delete_bans; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('task/delete_comments', $viewable_pages))) { ?> <li><a href="index.php?route=task/delete_comments"><?php echo $lang_menu_task_delete_comments; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('task/delete_reporters', $viewable_pages))) { ?> <li><a href="index.php?route=task/delete_reporters"><?php echo $lang_menu_task_delete_reporters; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('task/delete_subscriptions', $viewable_pages))) { ?> <li><a href="index.php?route=task/delete_subscriptions"><?php echo $lang_menu_task_delete_subscriptions; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('task/delete_voters', $viewable_pages))) { ?> <li><a href="index.php?route=task/delete_voters"><?php echo $lang_menu_task_delete_voters; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('reports', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-files-o"></span><?php echo $lang_menu_reports; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('report/access', $viewable_pages))) { ?> <li><a href="index.php?route=report/access"><?php echo $lang_menu_report_access; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('report/permissions', $viewable_pages))) { ?> <li><a href="index.php?route=report/permissions"><?php echo $lang_menu_report_permissions; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('report/phpinfo', $viewable_pages))) { ?> <li><a href="index.php?route=report/phpinfo"><?php echo $lang_menu_report_phpinfo; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('report/version', $viewable_pages))) { ?> <li><a href="index.php?route=report/version"><?php echo $lang_menu_report_version; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('report/viewers', $viewable_pages))) { ?> <li><a href="index.php?route=report/viewers"><?php echo $lang_menu_report_viewers; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('tools', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-wrench"></span><?php echo $lang_menu_tools; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('tool/clear_cache', $viewable_pages))) { ?> <li><a href="index.php?route=tool/clear_cache"><?php echo $lang_menu_tool_clear_cache; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('tool/database_backup', $viewable_pages))) { ?> <li><a href="index.php?route=tool/database_backup"><?php echo $lang_menu_tool_database_backup; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('tool/export_import', $viewable_pages))) { ?> <li><a href="index.php?route=tool/export_import"><?php echo $lang_menu_tool_export_import; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('tool/optimize_tables', $viewable_pages))) { ?> <li><a href="index.php?route=tool/optimize_tables"><?php echo $lang_menu_tool_optimize_tables; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('tool/text_finder', $viewable_pages))) { ?> <li><a href="index.php?route=tool/text_finder"><?php echo $lang_menu_tool_text_finder; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (!$has_restriction || ($has_restriction && in_array('help', $viewable_pages))) { ?>
                        <li><a><span class="fa fa-question-circle"></span><?php echo $lang_menu_help; ?></a>

                            <ul>
                                <?php if (!$has_restriction || ($has_restriction && in_array('help/faq', $viewable_pages))) { ?> <li><a href="https://commentics.com/faq" target="_blank"><?php echo $lang_menu_help_faq; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('help/forum', $viewable_pages))) { ?> <li><a href="https://commentics.com/forum/" target="_blank"><?php echo $lang_menu_help_forum; ?></a></li> <?php } ?>
                                <?php if (!$has_restriction || ($has_restriction && in_array('help/private', $viewable_pages))) { ?> <li><a href="https://commentics.com/clients" target="_blank"><?php echo $lang_menu_help_private; ?></a></li> <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <li><a href="index.php?route=login/logout"><span class="fa fa-sign-out"></span><?php echo $lang_menu_log_out; ?></a></li>
                </ul>
            </nav>
        </div>

        <div class="clear"></div>
    </header>

    <?php if ($error_view) { ?>
        <div class="wrapper">
            <div class="error"><?php echo $error_view; ?></div>
        </div>

        <?php die(); ?>
    <?php } ?>

<?php } ?>

<main class="wrapper">