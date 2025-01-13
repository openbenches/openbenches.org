<div class="cmtx_sort_by_block">
    <div class="cmtx_sort_by_container">
        <span class="cmtx_sort_by_text">{{ lang_text_sort_by }}</span>

        <select name="cmtx_sort_by" class="cmtx_sort_by_field" title="{{ lang_title_sort_by }}">
            @if show_sort_by_1
                <option value="1" {{ option_1_selected }}>{{ lang_entry_sort_by_1 }}</option>
            @endif

            @if show_sort_by_2
                <option value="2" {{ option_2_selected }}>{{ lang_entry_sort_by_2 }}</option>
            @endif

            @if show_sort_by_3
                <option value="3" {{ option_3_selected }}>{{ lang_entry_sort_by_3 }}</option>
            @endif

            @if show_sort_by_4
                <option value="4" {{ option_4_selected }}>{{ lang_entry_sort_by_4 }}</option>
            @endif

            @if show_sort_by_5
                <option value="5" {{ option_5_selected }}>{{ lang_entry_sort_by_5 }}</option>
            @endif

            @if show_sort_by_6
                <option value="6" {{ option_6_selected }}>{{ lang_entry_sort_by_6 }}</option>
            @endif
        </select>
    </div>
</div>