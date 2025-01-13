/* Auto detect checks whether jQuery is loaded and updates the theme settings accordingly. Must use plain JavaScript */
window.addEventListener('load', function(e) {
    var cmtx_page_settings = document.getElementById('cmtx_js_settings_page');

    if (cmtx_page_settings) {
        cmtx_page_settings = cmtx_page_settings.innerHTML;

        cmtx_page_settings = JSON.parse(cmtx_page_settings);

        if (cmtx_page_settings.auto_detect != 0) {
            var cmtx_interval = setInterval(function() {
                clearInterval(cmtx_interval);

                if (window.jQuery) {
                    var jquery = 1;
                } else {
                    var jquery = 0;
                }

                var cmtx_url = cmtx_page_settings.commentics_url + 'frontend/index.php?route=main/page/autodetect&jquery=' + jquery;

                var xhttp = new XMLHttpRequest();
                xhttp.open('GET', cmtx_url, true);
                xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhttp.send();

                if (!jquery) {
                    var body = document.getElementsByTagName('body')[0];

                    body.insertAdjacentHTML('afterbegin', '<div class="cmtx_overlay"></div>');

                    var overlay = document.getElementsByClassName('cmtx_overlay')[0];

                    overlay.style.display = 'block';

                    var modal = document.getElementById('cmtx_autodetect_modal');

                    modal.style.display = 'block';
                }
            }, 5000);
        }
    }
});