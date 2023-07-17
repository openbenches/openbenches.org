@if question
    <div class="cmtx_row cmtx_question_row cmtx_clear {{ cmtx_wait_for_user }}">
        <div class="cmtx_col_6">
            <div class="cmtx_container cmtx_question_container">
                <div id="cmtx_question" class="cmtx_field cmtx_text_field cmtx_question_field">{{ question }}</div>
            </div>
        </div>

        <div class="cmtx_col_6">
            <div class="cmtx_container cmtx_answer_container">
                <input type="text" name="cmtx_answer" id="cmtx_answer" class="cmtx_field cmtx_text_field cmtx_answer_field {{ answer_symbol }}" value="" placeholder="{{ lang_placeholder_answer }}" title="{{ lang_title_answer }}" maxlength="250">
            </div>
        </div>
    </div>
@endif