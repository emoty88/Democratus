<?php
    class meetdemocratus_block extends control{
        
        public function block(){
            global $model;
?>
                        <!-- Meet Democratus [Begin] -->
                        <div class="box" id="meet_democratus">
                            <span class="title">Democratus'u Tanıyın</span>
                            <div class="line"></div>
                            
                            <p>Profilinizi eksiksiz olarak tamamlayarak insanların size olan desteğini arttırın.</p>
                            
                            <ul>
                                <li>
                                    <a href="/about" alt="">Hakkımızda</a>
                                    <div class="line"></div>
                                </li>
                                <li>
                                    <a href="http://blog.democratus.com" alt="">Blog</a>
                                    <div class="line"></div>
                                </li>
                                <li>
                                    <a href="/privacy" alt="">Gizlilik</a>
                                    <div class="line"></div>
                                </li>
                                
                                <li>
                                    <a href="/service-terms" alt="">Hizmet Sözleşmesi</a>
                                    <div class="line"></div>
                                </li>
                                <li>
                                    <a href="/contact" alt="">İletişim</a>
                                    <div class="line"></div>
                                </li>
                            </ul>
                        </div>
                        <!-- Meet Democratus [End] -->
<?php
        }
    }
?>