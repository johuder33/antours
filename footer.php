<?php
$logo_url = loadAssetFromResourceDirectory("images", "antours-logo.png");
?>

        <footer class="row">
            <div class="footer-container">
                <div class="row footer-wrapper">
                    <div class="col-xs-5">
                        <figure class="footer-logo">
                            <img src="<?php echo $logo_url; ?>" class="img-responsive" />
                        </figure>
                        <p>
                            Para mayor información, comunicate con nuestros diseñadores de viaje y obtén una experiencia soñada.
                        </p>
                    </div>

                    <div class="col-xs-7">
                        <form class="form">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input class="form-control contact-field" placeholder="Nombre" />
                                </div>
                                <div class="col-xs-6">
                                    <input class="form-control contact-field" placeholder="Apellido" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <input class="form-control contact-field" placeholder="Asunto" />
                                    <textarea class="form-control contact-field" placeholder="Mensaje"></textarea>
                                </div>
                                <div class="col-xs-12">
                                    <button class="btn btn-default contact-field text-uppercase" type="submit">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="copyright">
                <div class="copyright-container text-center">
                    <p>
                        Copyright @ 2017 Antours. Todos los derechos reservados
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>