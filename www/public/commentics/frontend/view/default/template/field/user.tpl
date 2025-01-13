<div class="cmtx_row cmtx_user_row cmtx_clear {{ user_row_visible }}">
    @if enabled_name
        <div class="cmtx_col_{{ user_column_size }} {{ name_spacing }}">
            <div class="cmtx_container cmtx_name_container">
                <input type="text" name="cmtx_name" id="cmtx_name" class="cmtx_field cmtx_text_field cmtx_name_field {{ name_symbol }}" value="{{ name }}" placeholder="{{ lang_placeholder_name }}" title="{{ lang_title_name }}" maxlength="{{ maximum_name }}" {{ name_readonly }}>
            </div>
        </div>
    @endif

    @if enabled_email
        <div class="cmtx_col_{{ user_column_size }}">
            <div class="cmtx_container cmtx_email_container">
                <input type="email" name="cmtx_email" id="cmtx_email" class="cmtx_field cmtx_text_field cmtx_email_field {{ email_symbol }}" value="{{ email }}" placeholder="{{ lang_placeholder_email }}" title="{{ lang_title_email }}" maxlength="{{ maximum_email }}" {{ email_readonly }}>
            </div>
        </div>
    @endif
</div>