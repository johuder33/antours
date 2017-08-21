<?php
global $loaderContactForm;
$logo_url = loadAssetFromResourceDirectory("images", "antours-logo.png");
?>

        <footer class="row">
            <div class="footer-container" id="contact">
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
                        <div class="hide" id="alert" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <span id="alert-message"></span>
                        </div>
                        <form class="form" id="contact-form">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input class="form-control contact-field" required name="name" placeholder="Nombre" />
                                </div>
                                <div class="col-xs-6">
                                    <input class="form-control contact-field" required name="lastname" placeholder="Apellido" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <input class="form-control contact-field" name="subject" placeholder="Asunto" />
                                    <textarea class="form-control contact-field" required name="message" placeholder="Mensaje"></textarea>
                                </div>
                                <div class="col-xs-12">
                                    <button id="contact-btn" class="btn btn-default contact-field text-uppercase" type="submit">
                                        <i class="fa fa-refresh fa-spin hide" id="progress-icon-contact"></i>
                                        <span id="contact-btn-text">
                                            <?php echo $loaderContactForm; ?>
                                        <span>
                                    </button>
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