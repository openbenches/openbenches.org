@if enabled_website
    <div class="cmtx_row cmtx_website_row cmtx_clear {{ cmtx_wait_for_user }}">
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_website_container">
                <input type="url" name="cmtx_website" id="cmtx_website" class="cmtx_field cmtx_text_field cmtx_website_field {{ website_symbol }}" value="{{ website }}" placeholder="{{ lang_placeholder_website }}" title="{{ lang_title_website }}" maxlength="{{ maximum_website }}" {{ website_readonly }}>
            </div>
        </div>
    </div>
@endif