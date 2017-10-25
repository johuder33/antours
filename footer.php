<?php
global $loaderContactForm, $translation;
$logo_url = loadAssetFromResourceDirectory("images", "antours-logo.png");
?>

            <footer class="footer container-fluid">
                <div class="container" id="contact">
                    <div class="row">
                        <div class="row">
                            <div class="footer-information col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                <figure class="footer-logo text-center text-md-left">
                                    <img src="<?php echo $logo_url; ?>" class="img-responsive" />
                                </figure>
                                <div class="text-justify text-md-left">
                                    <p>
                                        Para mayor información, comunicate con nuestros diseñadores de viaje y obtén una experiencia soñada.
                                    </p>

                                    <div class="contact-info-container">
                                        <?php
                                            AntoursContactPage::renderInformation();
                                        ?>
                                    </div>

                                    <div>
                                        <?php
                                            AntoursContactPage::getSocialNetworkLinks();
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="footer-form col">
                                <div class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                                </div>

                                <form class="form" id="contact-form">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <input class="form-control contact-field" required name="name" placeholder="<?php echo $translation['CONTACT_FORM_FIELD_NAME']; ?>" />
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <input class="form-control contact-field" required name="lastname" placeholder="<?php echo $translation['CONTACT_FORM_FIELD_LASTNAME']; ?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <input class="form-control contact-field" name="subject" placeholder="<?php echo $translation['CONTACT_FORM_FIELD_SUBJECT']; ?>" />
                                            <textarea class="form-control contact-field message-field" required name="message" placeholder="<?php echo $translation['CONTACT_FORM_FIELD_MESSAGE']; ?>"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button id="contact-btn" class="btn btn-default contact-field text-uppercase" type="submit">
                                                <i class="fa fa-refresh fa-spin d-none" id="progress-icon-contact"></i>
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
                </div>

                <div class="row copyright">
                    <div class="container copyright-container text-center">
                        <p>
                            Copyright @ 2017 Antours. Todos los derechos reservados
                        </p>
                    </div>
                </div>
                
            </footer> <!--END FOOTER-->

        </div> <!-- ROW -->
    </div> <!--FLUD CONTAINER END-->
    <?php wp_footer(); ?>
</body>
</html>