<div id="cmtx_form_container" class="cmtx_form_container cmtx_clear">
    <h3 class="cmtx_form_heading">{{ lang_heading_form }}</h3>

    <div id="cmtx_preview"></div>

    @if display_form
        @if maintenance_mode_admin
            <div class="cmtx_maintenance_mode_admin">{{ lang_text_maintenance_admin }}</div>
        @endif

        <form id="cmtx_form" class="cmtx_form">
            @if display_javascript_disabled
                <noscript>
                    <div class="cmtx_javascript_disabled">{{ lang_text_javascript_disabled }}</div>
                </noscript>
            @endif

            @if display_required_text and display_required_symbol
                <div class="cmtx_required_text">{{ lang_text_required }}</div>
            @endif

            <div class="cmtx_rows">
                @foreach fields as key and field
                    @template field/{{ field.template }}
                @endforeach

                @if recaptcha
                    <div class="cmtx_row cmtx_recaptcha_row cmtx_clear {{ cmtx_wait_for_user }}">
                        <div class="cmtx_col_12">
                            <div class="cmtx_container cmtx_recaptcha_container">
                                <div id="g-recaptcha" class="g-recaptcha" data-sitekey="{{ recaptcha_public_key }}" data-theme="{{ recaptcha_theme }}" data-size="{{ recaptcha_size }}"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if captcha
                    <div class="cmtx_row cmtx_captcha_row cmtx_clear {{ cmtx_wait_for_user }}">
                        <div class="cmtx_col_12">
                            <div class="cmtx_container cmtx_captcha_container">
                                <div>
                                    <img id="cmtx_captcha_image" src="{{ captcha_url }}" alt="{{ lang_alt_captcha }}">

                                    <span id="cmtx_captcha_refresh" class="cmtx_captcha_refresh fa fa-refresh" title="{{ lang_title_refresh }}"></span>
                                </div>

                                <div><input type="text" name="cmtx_captcha" id="cmtx_captcha" class="cmtx_field cmtx_captcha_field {{ answer_symbol }}" placeholder="{{ lang_placeholder_captcha }}" title="{{ lang_title_captcha }}" maxlength="{{ maximum_captcha }}"></div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="cmtx_checkbox_container {{ cmtx_wait_for_user }}">
                    @if enabled_notify and ( enabled_email or email_is_filled )
                        <div class="cmtx_row cmtx_notify_row cmtx_clear">
                            <div class="cmtx_col_12">
                                <div class="cmtx_container cmtx_notify_container">
                                    <input type="checkbox" id="cmtx_notify" name="cmtx_notify" value="1" {{ notify_checked }}> <label for="cmtx_notify">{{ lang_entry_notify }}</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if enabled_cookie
                        <div class="cmtx_row cmtx_cookie_row cmtx_clear">
                            <div class="cmtx_col_12">
                                <div class="cmtx_container cmtx_cookie_container">
                                    <input type="checkbox" id="cmtx_cookie" name="cmtx_cookie" value="1" {{ cookie_checked }}> <label for="cmtx_cookie">{{ lang_entry_cookie }}</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if enabled_privacy
                        <div class="cmtx_row cmtx_privacy_row cmtx_clear">
                            <div class="cmtx_col_12">
                                <div class="cmtx_container cmtx_privacy_container">
                                    <input type="checkbox" id="cmtx_privacy" name="cmtx_privacy" value="1"> <label for="cmtx_privacy">{{ lang_entry_privacy }}</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if enabled_terms
                        <div class="cmtx_row cmtx_terms_row cmtx_clear">
                            <div class="cmtx_col_12">
                                <div class="cmtx_container cmtx_terms_container">
                                    <input type="checkbox" id="cmtx_terms" name="cmtx_terms" value="1"> <label for="cmtx_terms">{{ lang_entry_terms }}</label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="cmtx_row cmtx_button_row cmtx_clear">
                    <div class="cmtx_col_2">
                        <div class="cmtx_container cmtx_submit_button_container">
                            <input type="button" id="cmtx_submit_button" class="cmtx_button cmtx_button_primary {{ cmtx_admin_button }}" data-cmtx-type="submit" value="{{ lang_button_submit }}" title="{{ lang_button_submit }}">
                        </div>
                    </div>

                    <div class="cmtx_col_2">
                        @if enabled_preview
                            <div class="cmtx_container cmtx_preview_button_container">
                                <input type="button" id="cmtx_preview_button" class="cmtx_button cmtx_button_secondary {{ cmtx_admin_button }}" data-cmtx-type="preview" value="{{ lang_button_preview }}" title="{{ lang_button_preview }}">
                            </div>
                        @endif
                    </div>

                    <div class="cmtx_col_8"></div>
                </div>

                @if enabled_powered_by
                    <div class="cmtx_row cmtx_powered_by_row cmtx_clear">
                        <div class="cmtx_col_12">
                            <div class="cmtx_container cmtx_powered_by_container">
                                <div class="cmtx_powered_by">{{ powered_by }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" name="cmtx_reply_to" value="">

            <input type="hidden" id="cmtx_hidden_data" value="{{ hidden_data }}">

            <input type="hidden" name="cmtx_iframe" value="{{ iframe }}">

            <input type="hidden" name="cmtx_subscribe" value="">

            <input type="hidden" name="cmtx_time" value="{{ time }}">

            <input type="text" name="cmtx_honeypot" class="cmtx_honeypot" value="" autocomplete="off">
        </form>

        @if enabled_bb_code_bullet
            <div id="cmtx_bullet_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_bullet_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_bullet_content }}</div>
                    <div><span>{{ lang_modal_bullet_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_bullet_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_bullet_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_bullet_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_bullet_item }}</span> <input type="text"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_bullet_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_bb_code_numeric
            <div id="cmtx_numeric_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_numeric_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_numeric_content }}</div>
                    <div><span>{{ lang_modal_numeric_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_numeric_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_numeric_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_numeric_item }}</span> <input type="text"></div>
                    <div><span>{{ lang_modal_numeric_item }}</span> <input type="text"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_numeric_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_bb_code_link
            <div id="cmtx_link_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_link_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_link_content_1 }}</div>
                    <div><input type="url" placeholder="http://"></div>
                    <div>{{ lang_modal_link_content_2 }}</div>
                    <div><input type="text"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_link_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_bb_code_email
            <div id="cmtx_email_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_email_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_email_content_1 }}</div>
                    <div><input type="email"></div>
                    <div>{{ lang_modal_email_content_2 }}</div>
                    <div><input type="text"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_email_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_bb_code_image
            <div id="cmtx_image_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_image_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_image_content }}</div>
                    <div><input type="url" placeholder="http://"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_image_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_bb_code_youtube
            <div id="cmtx_youtube_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_youtube_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    <div>{{ lang_modal_youtube_content }}</div>
                    <div><input type="url" placeholder="http://"></div>
                </div>
                <footer>
                    <input type="button" id="cmtx_youtube_modal_insert" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_insert }}">
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_cancel }}">
                </footer>
            </div>
        @endif

        @if enabled_upload
            <div id="cmtx_upload_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_upload_heading }}</div>
                </header>
                <div class="cmtx_modal_body"></div>
                <footer>
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_close }}">
                </footer>
            </div>
        @endif

        @if enabled_privacy or quick_reply or show_edit
            <div id="cmtx_privacy_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_privacy_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    {{ lang_modal_privacy_content }}
                </div>
                <footer>
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_close }}">
                </footer>
            </div>
        @endif

        @if enabled_terms or quick_reply or show_edit
            <div id="cmtx_terms_modal" class="cmtx_modal_box" role="dialog">
                <header>
                    <a href="#" class="cmtx_modal_close">x</a>
                    <div>{{ lang_modal_terms_heading }}</div>
                </header>
                <div class="cmtx_modal_body">
                    {{ lang_modal_terms_content }}
                </div>
                <footer>
                    <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_close }}">
                </footer>
            </div>
        @endif

        {# These settings are passed to common.js #}
        <div id="cmtx_js_settings_form" class="cmtx_hide" hidden>{{ cmtx_js_settings_form }}</div>
    @else
        <div class="cmtx_form_disabled">{{ lang_error_form_disabled }}</div>
    @endif
</div>