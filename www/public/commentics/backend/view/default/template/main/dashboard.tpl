<?php echo $header; ?>

<div id="main_dashboard_page">

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

    <div class="left">
        <div class="block version_block">
            <div class="title"><span class="fa fa-wrench"></span> <?php echo $lang_title_version_check; ?></div>
            <div class="content">
                <?php if ($version_check['type'] == 'positive') { ?>
                    <span class="positive"><?php echo $version_check['text']; ?></span>
                <?php } ?>
                <?php if ($version_check['type'] == 'negative') { ?>
                    <span class="negative"><?php echo $version_check['text']; ?></span>

                    <?php if ($version_check['link_href']) { ?>
                        (<a href="<?php echo $version_check['link_href']; ?>" target="<?php echo $version_check['link_target']; ?>"><?php echo $version_check['link_text']; ?></a>)
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div class="block login_block">
            <div class="title"><span class="fa fa-lock"></span> <?php echo $lang_title_last_login; ?></div>
            <div class="content">
                <?php echo $lang_text_last_login; ?>
            </div>
        </div>

        <div class="block stats_block">
            <div class="title"><span class="fa fa-info"></span> <?php echo $lang_title_statistics; ?></div>
            <div class="content">
                <?php echo $lang_text_stats_action; ?><br>
                <?php echo $lang_text_stats_today; ?><br>
                <?php echo $lang_text_stats_total; ?>
            </div>
        </div>

        <div class="block tips_block dashboard_extra">
            <div class="title"><span class="fa fa-lightbulb-o"></span> <?php echo $lang_title_tip_of_the_day; ?></div>
            <div class="content">
                <?php echo $tip_of_the_day; ?>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="block news_block">
            <div class="title"><span class="fa fa-bullhorn"></span> <?php echo $lang_title_news; ?></div>
            <div class="content">
                <?php echo $news; ?>
            </div>
        </div>

        <div class="block links_block">
            <div class="title"><span class="fa fa-link"></span> <?php echo $lang_title_quick_links; ?></div>
            <div class="content">
                <?php if ($quick_links) { ?>
                    <?php foreach ($quick_links as $key => $value) { ?>
                        <div class="quick_link"><?php echo $key + 1; ?>. <a href="index.php?route=<?php echo $value['page']; ?>"><?php echo $value['text']; ?></a></div>
                    <?php } ?>
                <?php } else { ?>
                    <?php echo $lang_text_no_links; ?>
                <?php } ?>
            </div>
        </div>

        <div class="block licence_block">
            <div class="title"><span class="fa fa-id-card"></span> <?php echo $lang_title_licence; ?></div>
            <div class="content">
                <?php if ($licence_result == 'valid') { ?>
                    <?php echo $licence; ?>
                <?php } else if ($licence_result == 'none') { ?>
                    <span class="negative"><?php echo $lang_text_no_licence; ?></span> (<a href="https://commentics.com/pricing" target="_blank"><?php echo $lang_text_purchase; ?></a>)
                <?php } else if ($licence_result == 'unable') { ?>
                    <span class="negative"><?php echo $lang_text_unable; ?></span>
                <?php } else if ($licence_result == 'invalid') { ?>
                    <span class="negative"><?php echo $lang_text_licence_invalid; ?></span> (<a href="https://commentics.com/pricing" target="_blank"><?php echo $lang_text_purchase; ?></a>)
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <?php if ($sponsors) { ?>
        <div class="block sponsors_block">
            <div class="title"><span class="fa fa-thumbs-o-up"></span> <?php echo $lang_title_sponsors; ?></div>
            <div class="content">
                <ul class="sponsors">
                <?php foreach ($sponsors as $key => $sponsor) { ?>
                    <li class="sponsor"><a href="<?php echo $sponsor['href']; ?>" target="_blank" title="<?php echo $sponsor['name']; ?>"><img src="<?php echo $sponsor['image']; ?>" alt="<?php echo $sponsor['name']; ?>"></a></li>
                <?php } ?>
                </ul>
            </div>
        </div>
    <?php } ?>

    <?php if ($show_chart) { ?>
        <div class="chart_block">
            <div class="title"><span class="fa fa-bar-chart"></span> <?php echo $lang_title_chart; ?></div>

            <input type="hidden" data-js="chart_comments_jan" value="<?php echo $chart_comments['jan']; ?>">
            <input type="hidden" data-js="chart_comments_feb" value="<?php echo $chart_comments['feb']; ?>">
            <input type="hidden" data-js="chart_comments_mar" value="<?php echo $chart_comments['mar']; ?>">
            <input type="hidden" data-js="chart_comments_apr" value="<?php echo $chart_comments['apr']; ?>">
            <input type="hidden" data-js="chart_comments_may" value="<?php echo $chart_comments['may']; ?>">
            <input type="hidden" data-js="chart_comments_jun" value="<?php echo $chart_comments['jun']; ?>">
            <input type="hidden" data-js="chart_comments_jul" value="<?php echo $chart_comments['jul']; ?>">
            <input type="hidden" data-js="chart_comments_aug" value="<?php echo $chart_comments['aug']; ?>">
            <input type="hidden" data-js="chart_comments_sep" value="<?php echo $chart_comments['sep']; ?>">
            <input type="hidden" data-js="chart_comments_oct" value="<?php echo $chart_comments['oct']; ?>">
            <input type="hidden" data-js="chart_comments_nov" value="<?php echo $chart_comments['nov']; ?>">
            <input type="hidden" data-js="chart_comments_dec" value="<?php echo $chart_comments['dec']; ?>">

            <input type="hidden" data-js="chart_subscriptions_jan" value="<?php echo $chart_subscriptions['jan']; ?>">
            <input type="hidden" data-js="chart_subscriptions_feb" value="<?php echo $chart_subscriptions['feb']; ?>">
            <input type="hidden" data-js="chart_subscriptions_mar" value="<?php echo $chart_subscriptions['mar']; ?>">
            <input type="hidden" data-js="chart_subscriptions_apr" value="<?php echo $chart_subscriptions['apr']; ?>">
            <input type="hidden" data-js="chart_subscriptions_may" value="<?php echo $chart_subscriptions['may']; ?>">
            <input type="hidden" data-js="chart_subscriptions_jun" value="<?php echo $chart_subscriptions['jun']; ?>">
            <input type="hidden" data-js="chart_subscriptions_jul" value="<?php echo $chart_subscriptions['jul']; ?>">
            <input type="hidden" data-js="chart_subscriptions_aug" value="<?php echo $chart_subscriptions['aug']; ?>">
            <input type="hidden" data-js="chart_subscriptions_sep" value="<?php echo $chart_subscriptions['sep']; ?>">
            <input type="hidden" data-js="chart_subscriptions_oct" value="<?php echo $chart_subscriptions['oct']; ?>">
            <input type="hidden" data-js="chart_subscriptions_nov" value="<?php echo $chart_subscriptions['nov']; ?>">
            <input type="hidden" data-js="chart_subscriptions_dec" value="<?php echo $chart_subscriptions['dec']; ?>">

            <canvas id="chart" class="chart"></canvas>
        </div>
    <?php } ?>

    <form action="index.php?route=main/dashboard" class="controls" method="post">
        <div class="title"><span class="fa fa-pencil"></span> <?php echo $lang_title_administrator_notes; ?></div>

        <textarea name="notes"><?php echo $notes; ?></textarea>
        <?php if ($error_notes) { ?>
            <span class="error"><?php echo $error_notes; ?></span>
        <?php } ?>

        <input type="hidden" name="csrf_key" value="<?php echo $csrf_key; ?>">

        <p><input type="submit" class="button" value="<?php echo $lang_button_update; ?>" title="<?php echo $lang_button_update; ?>"></p>
    </form>

    <?php if ($version_detect && $version_issue) { ?>
        <div id="version_issue_dialog" title="<?php echo $lang_dialog_version_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_version_content; ?>
        </div>
    <?php } ?>

    <?php if ($system_detect && $system_settings) { ?>
        <div id="system_settings_dialog" title="<?php echo $lang_dialog_system_title; ?>" class="hide">
            <span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_system_content; ?>

            <ul>
                <?php foreach ($system_settings as $system_setting) { ?>
                    <li><?php echo $system_setting; ?></li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

</div>

<?php echo $footer; ?>