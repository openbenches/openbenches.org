<div id="cmtx_perm_{{ comment.id }}" class="cmtx_comment_box cmtx_clear" data-cmtx-comment-id="{{ comment.id }}" itemscope itemtype="https://schema.org/Comment">
    <div class="cmtx_content_area reply_indent_{{ reply_depth }}">
        @if avatar_type
            <div class="cmtx_avatar_area">
                <div>
                    <img src="{{ comment.avatar }}" class="cmtx_avatar" alt="Avatar">

                    @if show_level and comment.level
                        <div class="cmtx_level">{{ comment.level }}</div>
                    @endif
                </div>

                @if show_bio
                    <div class="cmtx_bio" role="dialog">
                        @if comment.is_admin
                            <div class="cmtx_bio_name cmtx_name_admin">{{ comment.name }}</div>
                        @else
                            <div class="cmtx_bio_name">{{ comment.name }}</div>
                        @endif

                        <img src="{{ comment.avatar_bio }}" class="cmtx_avatar_bio" alt="Avatar">

                        <div class="cmtx_bio_info">
                            <div class="cmtx_bio_info_comments"><label>{{ lang_text_bio_info_posts }}</label> <span>{{ comment.bio_info_posts }}</span></div>

                            @if show_like
                                <div class="cmtx_bio_info_likes"><label>{{ lang_text_bio_info_likes }}</label> <span>{{ comment.bio_info_likes }}</span></div>
                            @endif

                            @if show_dislike
                                <div class="cmtx_bio_info_dislikes"><label>{{ lang_text_bio_info_dislikes }}</label> <span>{{ comment.bio_info_dislikes }}</span></div>
                            @endif

                            <div class="cmtx_bio_info_since"><label>{{ lang_text_bio_info_since }}</label> <span>{{ comment.bio_info_since }}</span></div>
                        </div>

                        @if show_badge_top_poster and comment.top_poster
                            <div class="cmtx_badge">
                                <div class="fa fa-star cmtx_badge_star_left" aria-hidden="true"></div>
                                <div>{{ lang_text_badge_top_poster }}</div>
                                <div class="fa fa-star cmtx_badge_star_right" aria-hidden="true"></div>
                            </div>
                        @endif

                        @if show_badge_most_likes and show_like and comment.most_likes
                            <div class="cmtx_badge">
                                <div class="fa fa-star cmtx_badge_star_left" aria-hidden="true"></div>
                                <div>{{ lang_text_badge_most_likes }}</div>
                                <div class="fa fa-star cmtx_badge_star_right" aria-hidden="true"></div>
                            </div>
                        @endif

                        @if show_badge_first_poster and comment.first_poster
                            <div class="cmtx_badge">
                                <div class="fa fa-star cmtx_badge_star_left" aria-hidden="true"></div>
                                <div>{{ lang_text_badge_first_poster }}</div>
                                <div class="fa fa-star cmtx_badge_star_right" aria-hidden="true"></div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <div class="cmtx_main_area">
            @if comment.is_sticky
                <div class="cmtx_sticky" title="{{ lang_title_sticky }}"><div class="cmtx_sticky_icon"></div></div>
            @endif

            <div class="cmtx_user_and_rating_area">
                @if show_rating and comment.rating
                    <div class="cmtx_rating_area">
                        @foreach ratings as rating
                            @if rating less than comment.rating
                                <span class="cmtx_star cmtx_star_full"></span>
                            @else
                                <span class="cmtx_star cmtx_star_empty"></span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="cmtx_user_area" itemprop="creator" itemscope itemtype="https://schema.org/Person">
                    @if comment.is_admin
                    <span class="cmtx_name cmtx_name_admin">
                    @else
                    <span class="cmtx_name">
                    @endif
                        <span class="cmtx_name_text">
                            @if show_website and comment.website
                                <a href="{{ comment.website }}" itemprop="url" {{ website_new_window }} {{ website_no_follow }}>
                            @endif
                            <span itemprop="name">{{ comment.name }}</span>
                            @if show_website and comment.website
                                </a>
                            @endif
                            @if avatar_type
                                <meta itemprop="image" content="{{ comment.avatar }}">
                            @endif
                        </span>
                    </span>

                    @if comment.location
                        <span class="cmtx_geo" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                            ({{ comment.location }})
                        </span>
                    @endif

                    @if show_says
                        <span class="cmtx_says">
                            {{ lang_text_says }}
                        </span>
                    @endif
                </div>
            </div>

            @if show_headline and comment.headline
                <div class="cmtx_headline_area">
                    <span class="fa fa-quote-left"></span><span class="cmtx_headline_text">{{ comment.headline }}</span><span class="fa fa-quote-right"></span>
                </div>
            @endif

            <div class="cmtx_comment_area" itemprop="text">
                {{ comment.comment }}
            </div>

            @if comment.reply
                <div class="cmtx_reply_area">
                    <span class="cmtx_admin_reply">{{ lang_text_admin }}:</span> {{ comment.reply }}
                </div>
            @endif

            @if comment.extra_fields
                <div class="cmtx_extra_fields_area">
                    @foreach comment.extra_fields as key and value
                        <div><span>{{ key }}:</span> {{ value }}</div>
                    @endforeach
                </div>
            @endif

            @if comment.uploads
                <div class="cmtx_upload_area">
                    @foreach comment.uploads as upload
                        <a target="_blank"><img src="{{ upload.image }}" class="cmtx_upload" alt="Upload"></a>
                    @endforeach
                </div>
            @endif

            <div class="cmtx_date_and_action_area">
                @if show_date
                    <div class="cmtx_date_area">
                        @if date_auto
                            <time class="cmtx_date timeago" datetime="{{ comment.date_added }}" title="{{ comment.date_added_title }}">{{ comment.date_added_title }}</time>
                        @else
                            <time class="cmtx_date">{{ comment.date_added }}</time>
                        @endif
                        <meta itemprop="dateCreated" content="{{ comment.date_added }}">

                        @if comment.number_edits
                            <span class="cmtx_edited">{{ lang_text_edited }}</span>
                        @endif
                    </div>
                @endif

                <div class="cmtx_action_area" role="toolbar">
                    @if is_preview
                        <span class="cmtx_preview_text">{{ lang_text_preview_only }}</span>
                    @else
                        @if show_like
                            <div class="cmtx_like_area">
                                <a href="#" class="cmtx_vote_link cmtx_like_link" title="{{ lang_title_like }}">
                                    <span class="cmtx_icon cmtx_like_icon" aria-hidden="true"></span>
                                    <span class="cmtx_vote_count cmtx_like_count" itemprop="upvoteCount">{{ comment.likes }}</span>
                                </a>
                            </div>
                        @endif

                        @if show_dislike
                            <div class="cmtx_dislike_area">
                                <a href="#" class="cmtx_vote_link cmtx_dislike_link" title="{{ lang_title_dislike }}">
                                    <span class="cmtx_icon cmtx_dislike_icon" aria-hidden="true"></span>
                                    <span class="cmtx_vote_count cmtx_dislike_count" itemprop="downvoteCount">{{ comment.dislikes }}</span>
                                </a>
                            </div>
                        @endif

                        @if show_share
                            <div class="cmtx_share_area" title="{{ lang_title_share }}">
                                <a href="#" class="cmtx_share_link" data-cmtx-sharelink="{{ comment.permalink }}">
                                    <span class="cmtx_icon cmtx_share_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @endif

                        @if show_flag
                            <div class="cmtx_flag_area">
                                <a href="#" class="cmtx_flag_link" title="{{ lang_title_report }}">
                                    <span class="cmtx_icon cmtx_flag_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @endif

                        @if show_edit and comment.session_id equals session_id and comment.ip_address equals ip_address and comment.original_comment
                            <div class="cmtx_edit_area">
                                <a href="#" class="cmtx_edit_link" title="{{ lang_title_edit }}">
                                    <span class="cmtx_icon cmtx_edit_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @endif

                        @if show_delete and comment.session_id equals session_id and comment.ip_address equals ip_address
                            <div class="cmtx_delete_area">
                                <a href="#" class="cmtx_delete_link" title="{{ lang_title_delete }}">
                                    <span class="cmtx_icon cmtx_delete_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @endif

                        @if show_permalink
                            <div class="cmtx_permalink_area">
                                <a href="#" class="cmtx_permalink_link" title="{{ lang_title_permalink }}" data-cmtx-permalink="{{ comment.permalink }}">
                                    <span class="cmtx_icon cmtx_permalink_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                            <meta itemprop="url" content="{{ comment.permalink }}">
                        @endif

                        @if show_reply and no comment.is_locked and reply_depth less than reply_max_depth
                            <div class="cmtx_reply_area">
                                <a href="#" class="cmtx_reply_link" title="{{ lang_title_reply }}">
                                    <span class="cmtx_icon cmtx_reply_icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                @if comment.reply_id and no reply_depth and hide_replies
                    <div class="cmtx_view_replies_area">
                        <a href="#" class="cmtx_view_replies_link" title="{{ lang_title_view_replies }}"></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>