@if enabled_town or enabled_country or enabled_state
    <div class="cmtx_row cmtx_geo_row cmtx_clear {{ geo_row_visible }}">
        @if enabled_town
            <div class="cmtx_col_{{ geo_column_size }}">
                <div class="cmtx_container cmtx_town_container">
                    <input type="text" name="cmtx_town" id="cmtx_town" class="cmtx_field cmtx_text_field cmtx_town_field {{ town_symbol }}" value="{{ town }}" placeholder="{{ lang_placeholder_town }}" title="{{ lang_title_town }}" maxlength="{{ maximum_town }}" {{ town_readonly }}>
                </div>
            </div>
        @endif

        @if enabled_country
            <div class="cmtx_col_{{ geo_column_size }}">
                <div class="cmtx_container cmtx_country_container">
                    <select name="cmtx_country" id="cmtx_country" class="cmtx_field cmtx_select_field cmtx_country_field {{ country_symbol }}" title="{{ lang_title_country }}" {{ country_disabled }}>
                        <option value="" hidden>{{ lang_placeholder_country }}</option>
                    </select>
                </div>
            </div>
        @endif

        @if enabled_state
            <div class="cmtx_col_{{ geo_column_size }}">
                <div class="cmtx_container cmtx_state_container">
                    <select name="cmtx_state" id="cmtx_state" class="cmtx_field cmtx_select_field cmtx_state_field {{ state_symbol }}" title="{{ lang_title_state }}" {{ state_disabled }}>
                        <option value="" hidden>{{ lang_placeholder_state }}</option>
                    </select>
                </div>
            </div>
        @endif
    </div>
@endif