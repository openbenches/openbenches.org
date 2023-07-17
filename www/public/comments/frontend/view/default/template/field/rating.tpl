@if enabled_rating
    <div class="cmtx_row cmtx_rating_row cmtx_clear {{ cmtx_wait_for_comment }}">
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_rating_container">
                <div id="cmtx_rating" class="cmtx_rating {{ rating_symbol }}">
                    <div class="cmtx_rating_block">
                        <input type="radio" id="cmtx_star_5" name="cmtx_rating" value="5" {{ rating_5_checked }}><label for="cmtx_star_5" title="{{ lang_title_rating_5 }}"></label>
                        <input type="radio" id="cmtx_star_4" name="cmtx_rating" value="4" {{ rating_4_checked }}><label for="cmtx_star_4" title="{{ lang_title_rating_4 }}"></label>
                        <input type="radio" id="cmtx_star_3" name="cmtx_rating" value="3" {{ rating_3_checked }}><label for="cmtx_star_3" title="{{ lang_title_rating_3 }}"></label>
                        <input type="radio" id="cmtx_star_2" name="cmtx_rating" value="2" {{ rating_2_checked }}><label for="cmtx_star_2" title="{{ lang_title_rating_2 }}"></label>
                        <input type="radio" id="cmtx_star_1" name="cmtx_rating" value="1" {{ rating_1_checked }}><label for="cmtx_star_1" title="{{ lang_title_rating_1 }}"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif