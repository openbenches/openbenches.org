@if autodetect
    <script src="{{ autodetect }}" defer></script>
@endif

@if jquery
    <script src="{{ jquery }}"></script>
@endif

@if timeago
    <script src="{{ timeago }}" defer></script>
@endif

@if recaptcha_api
    <script src="{{ recaptcha_api }}" async defer></script>
@endif

@if highlight
    <script src="{{ highlight }}" defer></script>
@endif

<script src="{{ common }}" defer></script>

<link rel="stylesheet" type="text/css" href="{{ stylesheet }}">

@if custom
    <link rel="stylesheet" type="text/css" href="{{ custom }}">
@endif