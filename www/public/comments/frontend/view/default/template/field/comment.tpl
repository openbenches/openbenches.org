<div class="cmtx_comment_group">
    @if enabled_bb_code or enabled_smilies
        <div class="cmtx_row cmtx_icons_row cmtx_clear {{ cmtx_wait_for_comment }}" role="toolbar">
            <div class="cmtx_col_12">
                <div class="cmtx_container cmtx_icons_container cmtx_clear">
                    @if enabled_bb_code
                        <div class="cmtx_bb_code_container">
                            @if enabled_bb_code_bold
                                <span class="cmtx_bb_code cmtx_bb_code_bold" data-cmtx-tag="{{ lang_tag_bb_code_bold }}" title="{{ lang_title_bb_code_bold }}"></span>
                            @endif

                            @if enabled_bb_code_italic
                                <span class="cmtx_bb_code cmtx_bb_code_italic" data-cmtx-tag="{{ lang_tag_bb_code_italic }}" title="{{ lang_title_bb_code_italic }}"></span>
                            @endif

                            @if enabled_bb_code_underline
                                <span class="cmtx_bb_code cmtx_bb_code_underline" data-cmtx-tag="{{ lang_tag_bb_code_underline }}" title="{{ lang_title_bb_code_underline }}"></span>
                            @endif

                            @if enabled_bb_code_strike
                                <span class="cmtx_bb_code cmtx_bb_code_strike" data-cmtx-tag="{{ lang_tag_bb_code_strike }}" title="{{ lang_title_bb_code_strike }}"></span>
                            @endif

                            @if enabled_bb_code_superscript
                                <span class="cmtx_bb_code cmtx_bb_code_superscript" data-cmtx-tag="{{ lang_tag_bb_code_superscript }}" title="{{ lang_title_bb_code_superscript }}"></span>
                            @endif

                            @if enabled_bb_code_subscript
                                <span class="cmtx_bb_code cmtx_bb_code_subscript" data-cmtx-tag="{{ lang_tag_bb_code_subscript }}" title="{{ lang_title_bb_code_subscript }}"></span>
                            @endif

                            @if enabled_bb_code_code
                                <span class="cmtx_bb_code cmtx_bb_code_code" data-cmtx-tag="{{ lang_tag_bb_code_code }}" title="{{ lang_title_bb_code_code }}"></span>
                            @endif

                            @if enabled_bb_code_php
                                <span class="cmtx_bb_code cmtx_bb_code_php" data-cmtx-tag="{{ lang_tag_bb_code_php }}" title="{{ lang_title_bb_code_php }}"></span>
                            @endif

                            @if enabled_bb_code_quote
                                <span class="cmtx_bb_code cmtx_bb_code_quote" data-cmtx-tag="{{ lang_tag_bb_code_quote }}" title="{{ lang_title_bb_code_quote }}"></span>
                            @endif

                            @if enabled_bb_code_line
                                <span class="cmtx_bb_code cmtx_bb_code_line" data-cmtx-tag="{{ lang_tag_bb_code_line }}" title="{{ lang_title_bb_code_line }}"></span>
                            @endif

                            @if enabled_bb_code_bullet
                                <span class="cmtx_bb_code cmtx_bb_code_bullet" data-cmtx-tag="{{ lang_tag_bb_code_bullet }}" title="{{ lang_title_bb_code_bullet }}" data-cmtx-target-modal="#cmtx_bullet_modal"></span>
                            @endif

                            @if enabled_bb_code_numeric
                                <span class="cmtx_bb_code cmtx_bb_code_numeric" data-cmtx-tag="{{ lang_tag_bb_code_numeric }}" title="{{ lang_title_bb_code_numeric }}" data-cmtx-target-modal="#cmtx_numeric_modal"></span>
                            @endif

                            @if enabled_bb_code_link
                                <span class="cmtx_bb_code cmtx_bb_code_link" data-cmtx-tag="{{ lang_tag_bb_code_link }}" title="{{ lang_title_bb_code_link }}" data-cmtx-target-modal="#cmtx_link_modal"></span>
                            @endif

                            @if enabled_bb_code_email
                                <span class="cmtx_bb_code cmtx_bb_code_email" data-cmtx-tag="{{ lang_tag_bb_code_email }}" title="{{ lang_title_bb_code_email }}" data-cmtx-target-modal="#cmtx_email_modal"></span>
                            @endif

                            @if enabled_bb_code_image
                                <span class="cmtx_bb_code cmtx_bb_code_image" data-cmtx-tag="{{ lang_tag_bb_code_image }}" title="{{ lang_title_bb_code_image }}" data-cmtx-target-modal="#cmtx_image_modal"></span>
                            @endif

                            @if enabled_bb_code_youtube
                                <span class="cmtx_bb_code cmtx_bb_code_youtube" data-cmtx-tag="{{ lang_tag_bb_code_youtube }}" title="{{ lang_title_bb_code_youtube }}" data-cmtx-target-modal="#cmtx_youtube_modal"></span>
                            @endif
                        </div>
                    @endif

                    @if enabled_bb_code and enabled_smilies
                        <div class="cmtx_icons_separator"></div>
                    @endif

                    @if enabled_smilies
                        <div class="cmtx_smilies_container">
                            @if enabled_smilies_smile
                                <span class="cmtx_smiley cmtx_smiley_smile" data-cmtx-tag="{{ lang_tag_smiley_smile }}" title="{{ lang_title_smiley_smile }}"></span>
                            @endif

                            @if enabled_smilies_sad
                                <span class="cmtx_smiley cmtx_smiley_sad" data-cmtx-tag="{{ lang_tag_smiley_sad }}" title="{{ lang_title_smiley_sad }}"></span>
                            @endif

                            @if enabled_smilies_huh
                                <span class="cmtx_smiley cmtx_smiley_huh" data-cmtx-tag="{{ lang_tag_smiley_huh }}" title="{{ lang_title_smiley_huh }}"></span>
                            @endif

                            @if enabled_smilies_laugh
                                <span class="cmtx_smiley cmtx_smiley_laugh" data-cmtx-tag="{{ lang_tag_smiley_laugh }}" title="{{ lang_title_smiley_laugh }}"></span>
                            @endif

                            @if enabled_smilies_mad
                                <span class="cmtx_smiley cmtx_smiley_mad" data-cmtx-tag="{{ lang_tag_smiley_mad }}" title="{{ lang_title_smiley_mad }}"></span>
                            @endif

                            @if enabled_smilies_tongue
                                <span class="cmtx_smiley cmtx_smiley_tongue" data-cmtx-tag="{{ lang_tag_smiley_tongue }}" title="{{ lang_title_smiley_tongue }}"></span>
                            @endif

                            @if enabled_smilies_cry
                                <span class="cmtx_smiley cmtx_smiley_cry" data-cmtx-tag="{{ lang_tag_smiley_cry }}" title="{{ lang_title_smiley_cry }}"></span>
                            @endif

                            @if enabled_smilies_grin
                                <span class="cmtx_smiley cmtx_smiley_grin" data-cmtx-tag="{{ lang_tag_smiley_grin }}" title="{{ lang_title_smiley_grin }}"></span>
                            @endif

                            @if enabled_smilies_wink
                                <span class="cmtx_smiley cmtx_smiley_wink" data-cmtx-tag="{{ lang_tag_smiley_wink }}" title="{{ lang_title_smiley_wink }}"></span>
                            @endif

                            @if enabled_smilies_scared
                                <span class="cmtx_smiley cmtx_smiley_scared" data-cmtx-tag="{{ lang_tag_smiley_scared }}" title="{{ lang_title_smiley_scared }}"></span>
                            @endif

                            @if enabled_smilies_cool
                                <span class="cmtx_smiley cmtx_smiley_cool" data-cmtx-tag="{{ lang_tag_smiley_cool }}" title="{{ lang_title_smiley_cool }}"></span>
                            @endif

                            @if enabled_smilies_sleep
                                <span class="cmtx_smiley cmtx_smiley_sleep" data-cmtx-tag="{{ lang_tag_smiley_sleep }}" title="{{ lang_title_smiley_sleep }}"></span>
                            @endif

                            @if enabled_smilies_blush
                                <span class="cmtx_smiley cmtx_smiley_blush" data-cmtx-tag="{{ lang_tag_smiley_blush }}" title="{{ lang_title_smiley_blush }}"></span>
                            @endif

                            @if enabled_smilies_confused
                                <span class="cmtx_smiley cmtx_smiley_confused" data-cmtx-tag="{{ lang_tag_smiley_confused }}" title="{{ lang_title_smiley_confused }}"></span>
                            @endif

                            @if enabled_smilies_shocked
                                <span class="cmtx_smiley cmtx_smiley_shocked" data-cmtx-tag="{{ lang_tag_smiley_shocked }}" title="{{ lang_title_smiley_shocked }}"></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="cmtx_row cmtx_comment_row cmtx_clear">
        <div class="cmtx_col_12">
            <div class="cmtx_container cmtx_comment_container">
                <textarea name="cmtx_comment" id="cmtx_comment" class="cmtx_field cmtx_textarea_field cmtx_comment_field {{ comment_symbol }}" placeholder="{{ lang_placeholder_comment }}" title="{{ lang_title_comment }}" maxlength="{{ comment_maximum_characters }}">{{ comment }}</textarea>
            </div>
        </div>
    </div>

    @if enabled_counter
        <div class="cmtx_row cmtx_counter_row cmtx_clear {{ cmtx_wait_for_comment }}">
            <div class="cmtx_col_12">
                <div class="cmtx_container cmtx_counter_container">
                    <span id="cmtx_counter" class="cmtx_counter">{{ comment_maximum_characters }}</span>
                </div>
            </div>
        </div>
    @endif
</div>