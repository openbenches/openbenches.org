<div id="cmtx_comments_container" class="cmtx_comments_container cmtx_clear">
    <h3 class="cmtx_comments_heading">{{ lang_heading_comments }}</h3>

    @if comments
        @if rich_snippets_enabled
            <div itemscope itemtype="https://schema.org/{{ rich_snippets_type }}">
        @endif

        @if row_one or row_two
            <div class="cmtx_comments_top_row" role="toolbar">
                @if row_one
                    <div class="cmtx_comments_row_one cmtx_clear">
                        <div class="cmtx_row_left {{ cmtx_empty_position_1 }}">{{ comments_position_1 }}</div>
                        <div class="cmtx_row_middle {{ cmtx_empty_position_2 }}">{{ comments_position_2 }}</div>
                        <div class="cmtx_row_right {{ cmtx_empty_position_3 }}">{{ comments_position_3 }}</div>
                    </div>
                @endif

                @if row_two
                    <div class="cmtx_comments_row_two cmtx_clear">
                        <div class="cmtx_row_left {{ cmtx_empty_position_4 }}">{{ comments_position_4 }}</div>
                        <div class="cmtx_row_middle {{ cmtx_empty_position_5 }}">{{ comments_position_5 }}</div>
                        <div class="cmtx_row_right {{ cmtx_empty_position_6 }}">{{ comments_position_6 }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="cmtx_comment_boxes" role="feed">
            @foreach comments as comment
                @set reply_depth = 0

                {# Count the number of replies #}
                @start count at 0

                <section class="cmtx_comment_section">
                    {# This is the parent comment #}
                    @template main/comment

                    {# If the comment has any replies they are put into a div #}
                    @if comment.reply_id
                        <div class="cmtx_replies_group {{ hide_replies }}">
                    @endif

                    {# For each reply #}
                    @foreach comment.reply_id as comment
                        @set reply_depth = 1
                        @increase count
                        @template main/comment

                        @foreach comment.reply_id as comment
                            @set reply_depth = 2
                            @increase count
                            @template main/comment

                            @foreach comment.reply_id as comment
                                @set reply_depth = 3
                                @increase count
                                @template main/comment

                                @foreach comment.reply_id as comment
                                    @set reply_depth = 4
                                    @increase count
                                    @template main/comment

                                    @foreach comment.reply_id as comment
                                        @set reply_depth = 5
                                        @increase count
                                        @template main/comment
                                    @endforeach

                                @endforeach

                            @endforeach

                        @endforeach

                    @endforeach

                    {# If the comment had any replies close the div #}
                    @if count more than 0
                        </div>
                    @endif

                    <span class="cmtx_reply_counter cmtx_hide" hidden>{{ count }}</span>
                </section>
            @endforeach
        </div>

        @if show_pagination and pagination_type equals 'button' and total more than pagination_amount
            <div class="cmtx_more_button_row">
                <input type="button" id="cmtx_more_button" class="cmtx_button cmtx_button_primary" value="{{ lang_button_more }}" title="{{ lang_title_more_comments }}">
            </div>
        @endif

        @if is_permalink or is_search
            <div class="cmtx_return">{{ lang_text_return }}</div>
        @endif

        @if row_three or row_four
            <div class="cmtx_comments_bottom_row" role="toolbar">
                @if row_three
                    <div class="cmtx_comments_row_three cmtx_clear">
                        <div class="cmtx_row_left {{ cmtx_empty_position_7 }}">{{ comments_position_7 }}</div>
                        <div class="cmtx_row_middle {{ cmtx_empty_position_8 }}">{{ comments_position_8 }}</div>
                        <div class="cmtx_row_right {{ cmtx_empty_position_9 }}">{{ comments_position_9 }}</div>
                    </div>
                @endif

                @if row_four
                    <div class="cmtx_comments_row_four cmtx_clear">
                        <div class="cmtx_row_left {{ cmtx_empty_position_10 }}">{{ comments_position_10 }}</div>
                        <div class="cmtx_row_middle {{ cmtx_empty_position_11 }}">{{ comments_position_11 }}</div>
                        <div class="cmtx_row_right {{ cmtx_empty_position_12 }}">{{ comments_position_12 }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="cmtx_loading_icon"></div>

        <div class="cmtx_action_message cmtx_action_message_success"></div>
        <div class="cmtx_action_message cmtx_action_message_error"></div>

        @if show_share
            <div class="cmtx_share_box" data-cmtx-reference="{{ page_reference }}" role="dialog">
                @if show_share_digg
                    <a href="#" {{ share_new_window }} title="{{ lang_title_digg }}"><span class="cmtx_share cmtx_share_digg"></span></a>
                @endif

                @if show_share_facebook
                    <a href="#" {{ share_new_window }} title="{{ lang_title_facebook }}"><span class="cmtx_share cmtx_share_facebook"></span></a>
                @endif

                @if show_share_linkedin
                    <a href="#" {{ share_new_window }} title="{{ lang_title_linkedin }}"><span class="cmtx_share cmtx_share_linkedin"></span></a>
                @endif

                @if show_share_reddit
                    <a href="#" {{ share_new_window }} title="{{ lang_title_reddit }}"><span class="cmtx_share cmtx_share_reddit"></span></a>
                @endif

                @if show_share_twitter
                    <a href="#" {{ share_new_window }} title="{{ lang_title_twitter }}"><span class="cmtx_share cmtx_share_twitter"></span></a>
                @endif

                @if show_share_weibo
                    <a href="#" {{ share_new_window }} title="{{ lang_title_weibo }}"><span class="cmtx_share cmtx_share_weibo"></span></a>
                @endif
            </div>
        @endif

        @if show_flag
            <div id="cmtx_flag_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_flag_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div><span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> {{ lang_modal_flag_content }}</div>
                </div>
                <footer>
                    <input type="button" id="cmtx_flag_modal_yes" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_yes }}">
                    <input type="button" id="cmtx_flag_modal_no" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_no }}">
                </footer>
            </div>
        @endif

        @if show_delete
            <div id="cmtx_delete_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_delete_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div><span class="cmtx_icon cmtx_alert_icon" aria-hidden="true"></span> {{ lang_modal_delete_content }}</div>
                </div>
                <footer>
                    <input type="button" id="cmtx_delete_modal_yes" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_yes }}">
                    <input type="button" id="cmtx_delete_modal_no" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_no }}">
                </footer>
            </div>
        @endif

        @if show_permalink
            <div class="cmtx_permalink_box" role="dialog">
                <div>{{ lang_text_permalink }}</div>

                <input type="text" name="cmtx_permalink" id="cmtx_permalink" class="cmtx_permalink" value="" readonly>

                <div><a href="#">{{ lang_link_close }}</a></div>
            </div>
        @endif

        <input type="hidden" name="cmtx_next_page" id="cmtx_next_page" value="2">

        <div id="cmtx_loading_helper" data-cmtx-load="1" data-cmtx-total-comments="{{ total }}"></div>

        @if rich_snippets_enabled
            </div>
        @endif

        {# These settings are passed to common.js #}
        <div id="cmtx_js_settings_comments" class="cmtx_hide" hidden>{{ cmtx_js_settings_comments }}</div>
    @elseif is_permalink
        <div class="cmtx_no_permalink">{{ lang_text_no_permalink }}</div>
    @elseif is_search
        <div class="cmtx_no_results">{{ lang_text_no_results }}</div>
    @else
        <div class="cmtx_no_comments">{{ lang_text_no_comments }}</div>
    @endif
</div>