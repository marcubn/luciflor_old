{include file="site/inc/top.tpl" page='contact'}
    <!--  Content -->
    <div class="content full-height">
        <!--  wrapper-inner  -->
        <div class="wrapper-inner">
            <!--  align-content  -->
            <div class="align-content">
                <section>
                    <div class="container small-container">
                        <h3 class="dec-text">Contact</h3>
                        <p>Design interior, mobilare, arhitectura, urbanism, decoratiuni, capitonare, pereti si mobilier comanda sau piese unicat. </p>
                        <ul class="contact-list">
                            <li><span>Adress </span>
                                <a href="#">Mihnea Voda, nr 3B,  Bucuresti</a>
                            </li>
                            <li><span>Phone</span>
                                <a href="#">(0722) 580 704</a>
                            </li>
                            <li>
                                <span>E-mail </span>
                                <a href="mailto:office@pure-mess.com">office@pure-mess.com</a>
                            </li>
                        </ul>
                        <a href="#" class=" btn anim-button   trans-btn   transition  fl-l showform"><span>Scrie-ne!</span><i class="fa fa-eye"></i></a>
                    </div>
                </section>
            </div>
            <!--  align-content  end-->
            <!--  contact-form-holder  -->
            <div class="contact-form-holder">
                <div class="close-contact"></div>
                <div class="align-content">
                    <section>
                        <div id="contact-form">
                            <div id="message"></div>
                            <form name="contact_form" id="contact_form" method="post" action="/process-contact/">
                                <input type="hidden" name="spoofContactId" value="{$spoofContactId}">
                                <input type="hidden" name="form" value="act" />
                                <input name="nume" type="text" id="nume"  onClick="this.select()" placeholder="Nume" >
                                <input name="telefon" type="text" id="telefon"  onClick="this.select()" placeholder="Telefon" >
                                <input name="email" type="text" id="email" onClick="this.select()" placeholder="E-mail" >
                                <textarea name="mesaj"  id="mesaj" onClick="this.select()" placeholder="Mesaj"></textarea>
                                <button type="submit"  id="submit"><span>Trimite </span> <i class="fa fa-long-arrow-right"></i></button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <!--  contact-form-holder end -->
        </div>
        <!--  fixed-column -->
        <div class="fixed-column">
            <div class="map-box">
                <div  id="map-canvas"></div>
            </div>
        </div>
        <!--  fixed-column end-->
    </div>
    <!--  Content  end -->
    {include file="site/inc/share.tpl"}
</div>
<!-- Content holder  end -->
</div>
<!-- wrapper end -->
{include file="site/inc/bottom.tpl"}