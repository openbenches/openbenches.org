<div class="cmtx_row cmtx_extra_row cmtx_clear">
    @if field.type equals 'select'
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_extra_container cmtx_extra_select_container">
                <select name="cmtx_{{ key }}" class="cmtx_field cmtx_select_field cmtx_extra_field {{ field.symbol }}" title="{{ field.name }}">
                    <option value="" hidden>{{ field.name }}</option>
                    @foreach field.values as value
                       <option value="{{ value }}">{{ value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    @if field.type equals 'text'
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_extra_container cmtx_extra_text_container">
                <input type="text" name="cmtx_{{ key }}" class="cmtx_field cmtx_text_field cmtx_extra_field {{ field.symbol }}" value="{{ field.default }}" placeholder="{{ field.name }}" title="{{ field.name }}" maxlength="{{ field.maximum }}">
            </div>
        </div>
    @endif

    @if field.type equals 'textarea'
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_extra_container cmtx_extra_textarea_container">
                <textarea name="cmtx_{{ key }}" class="cmtx_field cmtx_textarea_field cmtx_extra_field {{ field.symbol }}" placeholder="{{ field.name }}" title="{{ field.name }}" maxlength="{{ field.maximum }}">{{ field.default }}</textarea>
            </div>
        </div>
    @endif
</div>