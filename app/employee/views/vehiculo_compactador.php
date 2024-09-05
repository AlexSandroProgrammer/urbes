<?php
$titlePage = "Form. Vehiculo Compactador";
require_once("../components/sidebar.php");


?>
<div id="zf_div_hp5tcYezPVRpGJkvnKh8rnA4buxDuxj_hfzR1aT8BZE"></div>
<div id="zf_div_hp5tcYezPVRpGJkvnKh8rnA4buxDuxj_hfzR1aT8BZE"></div>
<script type="text/javascript">
    (function() {
        try {
            var f = document.createElement("iframe");
            f.src =
                'https://forms.zohopublic.com/urbescolaboradores1/form/VehculoCompactador/formperma/hp5tcYezPVRpGJkvnKh8rnA4buxDuxj_hfzR1aT8BZE?zf_rszfm=1&zf_enablecamera=true';
            f.style.border = "none";
            f.style.height = "805px";
            f.style.width = "100%";
            f.style.transition = "all 0.5s ease";
            f.setAttribute("aria-label", 'Actividad\x20en\x20veh\xEDculo\x20compactador');
            f.setAttribute("allow", "camera;");
            var d = document.getElementById("zf_div_hp5tcYezPVRpGJkvnKh8rnA4buxDuxj_hfzR1aT8BZE");
            d.appendChild(f);
            window.addEventListener('message', function() {
                var evntData = event.data;
                if (evntData && evntData.constructor == String) {
                    var zf_ifrm_data = evntData.split("|");
                    if (zf_ifrm_data.length == 2 || zf_ifrm_data.length == 3) {
                        var zf_perma = zf_ifrm_data[0];
                        var zf_ifrm_ht_nw = (parseInt(zf_ifrm_data[1], 10) + 15) + "px";
                        var iframe = document.getElementById(
                            "zf_div_hp5tcYezPVRpGJkvnKh8rnA4buxDuxj_hfzR1aT8BZE").getElementsByTagName(
                            "iframe")[0];
                        if ((iframe.src).indexOf('formperma') > 0 && (iframe.src).indexOf(zf_perma) > 0) {
                            var prevIframeHeight = iframe.style.height;
                            var zf_tout = false;
                            if (zf_ifrm_data.length == 3) {
                                iframe.scrollIntoView();
                                zf_tout = true;
                            }
                            if (prevIframeHeight != zf_ifrm_ht_nw) {
                                if (zf_tout) {
                                    setTimeout(function() {
                                        iframe.style.height = zf_ifrm_ht_nw;
                                    }, 500);
                                } else {
                                    iframe.style.height = zf_ifrm_ht_nw;
                                }
                            }
                        }
                    }
                }
            }, false);
        } catch (e) {}
    })();
</script>


<?php
require_once("../components/footer.php");


?>