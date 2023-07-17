<div class="cmtx_social_block">
    @if show_digg
        <a href="{{ digg_url }}" {{ new_window }} title="{{ lang_title_digg }}"><span class="cmtx_social cmtx_social_digg"></span></a>
    @endif

    @if show_facebook
        <a href="{{ facebook_url }}" {{ new_window }} title="{{ lang_title_facebook }}"><span class="cmtx_social cmtx_social_facebook"></span></a>
    @endif

    @if show_linkedin
        <a href="{{ linkedin_url }}" {{ new_window }} title="{{ lang_title_linkedin }}"><span class="cmtx_social cmtx_social_linkedin"></span></a>
    @endif

    @if show_reddit
        <a href="{{ reddit_url }}" {{ new_window }} title="{{ lang_title_reddit }}"><span class="cmtx_social cmtx_social_reddit"></span></a>
    @endif

    @if show_twitter
        <a href="{{ twitter_url }}" {{ new_window }} title="{{ lang_title_twitter }}"><span class="cmtx_social cmtx_social_twitter"></span></a>
    @endif

    @if show_weibo
        <a href="{{ weibo_url }}" {{ new_window }} title="{{ lang_title_weibo }}"><span class="cmtx_social cmtx_social_weibo"></span></a>
    @endif
</div>