@if enabled_upload
    <div class="cmtx_row cmtx_upload_row cmtx_clear {{ cmtx_wait_for_comment }}">
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_upload_container">
                <input type='file' name="cmtx_files[]" id="cmtx_upload" accept=".png,.jpg,.jpeg,.gif">
                <div class="cmtx_drag_text">
                    <div>{{ lang_text_drag_and_drop }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="cmtx_row cmtx_image_row cmtx_hide cmtx_clear">
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_image_container"></div>
        </div>
    </div>
@endif