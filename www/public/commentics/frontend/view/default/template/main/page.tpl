<div id="cmtx_container" class="cmtx_container {{ cmtx_dir }}">
    {{ header }}

    @if maintenance_mode
        <h3>{{ lang_heading_maintenance }}</h3>

        <div class="cmtx_maintenance_mode">{{ maintenance_message }}</div>
    @else
        @if order_parts equals 'form,comments'
            <div class="cmtx_form_section">{{ form }}</div>
        @else
            <div class="cmtx_comments_section">{{ comments }}</div>
        @endif

        @if display_parsing
            <div class="cmtx_parsing_box cmtx_clear">
                <div>{{ lang_text_generated_in }} {{ generated_time }} {{ lang_text_seconds }}</div>
                <div><b>PHP</b>: {{ php_time }}s | <b>SQL</b>: {{ query_time }}s ({{ query_count }} {{ lang_text_queries }})</div>
            </div>
        @endif

        <div class="cmtx_divider_section"></div>

        @if order_parts equals 'form,comments'
            <div class="cmtx_comments_section">{{ comments }}</div>
        @else
            <div class="cmtx_form_section">{{ form }}</div>
        @endif
    @endif

    @if auto_detect
        <div id="cmtx_autodetect_modal" class="cmtx_modal_box" role="dialog">
            <header>
                <div>{{ lang_modal_autodetect_heading }}</div>
            </header>
            <div class="cmtx_modal_body">
                {{ lang_modal_autodetect_content }}
            </div>
        </div>
    @endif

    @if admin_detect
        <div id="cmtx_admindetect_modal" class="cmtx_modal_box" role="dialog">
            <header>
                <a href="#" class="cmtx_modal_close">x</a>
                <div>{{ lang_modal_admindetect_heading }}</div>
            </header>
            <div class="cmtx_modal_body">
                {{ lang_modal_admindetect_content }}
            </div>
            <footer>
                <input type="button" id="cmtx_admindetect_modal_stop" class="cmtx_button cmtx_button_primary" value="{{ lang_modal_stop }}">
                <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_close }}">
            </footer>
        </div>
    @endif

    <div id="cmtx_lightbox_modal" class="cmtx_lightbox_modal cmtx_modal_box" role="dialog">
        <header>
            <a href="#" class="cmtx_modal_close">x</a>
        </header>
        <div class="cmtx_modal_body"></div>
        <footer>
            <input type="button" class="cmtx_button cmtx_button_secondary" value="{{ lang_modal_close }}">
        </footer>
    </div>

    @if css_editor_enabled
    <style>
    #cmtx_container {
        background-color: {{ css_editor_general_background_color }};
        color: {{ css_editor_general_foreground_color }};
        @if css_editor_general_font_family
            font-family: "{{ css_editor_general_font_family }}";
        @endif
        @if css_editor_general_font_size
            font-size: {{ css_editor_general_font_size }}px;
        @endif
    }

    #cmtx_container h3 {
        background-color: {{ css_editor_heading_background_color }};
        color: {{ css_editor_heading_foreground_color }};
        @if css_editor_heading_font_family
            font-family: "{{ css_editor_heading_font_family }}";
        @endif
        @if css_editor_heading_font_size
            font-size: {{ css_editor_heading_font_size }}px;
        @endif
    }

    #cmtx_container a {
        color: {{ css_editor_link_foreground_color }};
    }

    #cmtx_container .cmtx_checkbox_container a, #cmtx_container .cmtx_powered_by a, #cmtx_container .cmtx_comment_area a {
        background-color: {{ css_editor_link_background_color }};
        @if css_editor_link_font_family
            font-family: "{{ css_editor_link_font_family }}";
        @endif
        @if css_editor_link_font_size
            font-size: {{ css_editor_link_font_size }}px;
        @endif
    }

    #cmtx_container .cmtx_button_primary {
        background-color: {{ css_editor_primary_button_background_color }};
        color: {{ css_editor_primary_button_foreground_color }};
        @if css_editor_primary_button_font_family
            font-family: "{{ css_editor_primary_button_font_family }}";
        @endif
        @if css_editor_primary_button_font_size
            font-size: {{ css_editor_primary_button_font_size }}px;
        @endif
    }

    @if css_editor_primary_button_background_color equals '#3f6f95'
        #cmtx_container .cmtx_button_primary:hover {
            background-color: #305471;
        }
    @else
        #cmtx_container .cmtx_button_primary:hover {
            background-color: {{ css_editor_primary_button_background_color }};
        }
    @endif

    #cmtx_container .cmtx_button_secondary {
        background-color: {{ css_editor_secondary_button_background_color }};
        color: {{ css_editor_secondary_button_foreground_color }};
        @if css_editor_secondary_button_font_family
            font-family: "{{ css_editor_secondary_button_font_family }}";
        @endif
        @if css_editor_secondary_button_font_size
            font-size: {{ css_editor_secondary_button_font_size }}px;
        @endif
    }

    @if css_editor_secondary_button_background_color equals '#e7e7e7'
        #cmtx_container .cmtx_button_secondary:hover {
            background-color: #dadada;
        }
    @else
        #cmtx_container .cmtx_button_secondary:hover {
            background-color: {{ css_editor_secondary_button_background_color }};
        }
    @endif
    </style>
    @endif

    {# These are passed to autodetect.js via the template #}
    <div id="cmtx_js_settings_page" class="cmtx_hide" hidden>{{ cmtx_js_settings_page }}</div>

    {{ footer }}
</div>