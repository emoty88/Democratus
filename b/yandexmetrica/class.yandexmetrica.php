<?php
    class yandexmetrica_block extends control{
        
        public function block(){
        	?>
			<script type="text/javascript">
            			(function (d, w, c) {
						(w[c] = w[c] || []).push(function() {
							try {
								w.yaCounter14085859 = new Ya.Metrika({id:14085859, enableAll: true, trackHash:true, webvisor:true});
							} catch(e) {}
						});
						
						var n = d.getElementsByTagName("script")[0],
							s = d.createElement("script"),
							f = function () { n.parentNode.insertBefore(s, n); };
						s.type = "text/javascript";
						s.async = true;
						s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
				
						if (w.opera == "[object Opera]") {
							d.addEventListener("DOMContentLoaded", f);
						} else { f(); }
					})(document, window, "yandex_metrika_callbacks");
            </script>
        	<?php 	
        }
   }
?>