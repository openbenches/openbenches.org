<div class="cmtx_pagination_block" role="navigation">
    <div class="cmtx_pagination_container">
        @if current_page more than 1
            <a href="{{ pagination_url_first }}" class="cmtx_pagination_url"><span class="cmtx_pagination_box" title="{{ lang_title_first }}" data-cmtx-page="1">{{ lang_link_first }}</span></a>

            <a href="{{ pagination_url_previous }}" class="cmtx_pagination_url"><span class="cmtx_pagination_box" title="{{ lang_title_previous }}" data-cmtx-page="{{ previous_page }}">{{ lang_link_previous }}</span></a>
        @endif

        @if total_pages more than 1
            @foreach pages as page
                @if current_page equals page.number
                    <span class="cmtx_pagination_box cmtx_pagination_box_active" title="{{ page.number }}" data-cmtx-page="{{ page.number }}">{{ page.number }}</span>
                @else
                    <a href="{{ page.url }}" class="cmtx_pagination_url"><span class="cmtx_pagination_box" title="{{ page.number }}" data-cmtx-page="{{ page.number }}">{{ page.number }}</span></a>
                @endif
            @endforeach
        @else
            &nbsp;
        @endif

        @if current_page less than total_pages
            <a href="{{ pagination_url_next }}" class="cmtx_pagination_url"><span class="cmtx_pagination_box" title="{{ lang_title_next }}" data-cmtx-page="{{ next_page }}">{{ lang_link_next }}</span></a>

            <a href="{{ pagination_url_last }}" class="cmtx_pagination_url"><span class="cmtx_pagination_box" title="{{ lang_title_last }}" data-cmtx-page="{{ total_pages }}">{{ lang_link_last }}</span></a>
        @endif
    </div>
</div>