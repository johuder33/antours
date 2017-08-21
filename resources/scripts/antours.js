jQuery(document).ready(function(){
    (function($){
        var page = 0;
        var canLoadComments = true;
        var canSendContactForm = true;
        var commentList = $(".comment-list");
        var buttonSenderContact = $("#contact-btn");
        var loadCommentsButton = $('#load-comments');
        var iconProgress = $('#progress-icon');
        var btnReservation = $(".btn-reserve");
        var iconProgressContact = $("#progress-icon-contact");
        var alert = $("#alert");
        var alertMessage = $("#alert-message");
        var loadBtnText = $('#load-btn-text');
        var contactBtnText = $("#contact-btn-text");
        var currentDate = new Date();
        var tomorrow = new Date();
        var contactFields = $('.contact-field');
        var contactInformation = {};

        tomorrow.setDate(tomorrow.getDate() + 1);

        var datePickerOptions = {
            startDate: currentDate,
            format: 'dd/mm/yyyy',
            days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
        };

        var timePickerOptions = {
            'minTime': new Date(),
            'timeFormat': 'H:i'
        };

        $("#go-date-transport").datepicker(datePickerOptions);
        $("#go-date-transport").datepicker("setDate", currentDate);

        $("#goback-date-transport").datepicker(datePickerOptions);
        $("#goback-date-transport").datepicker("setDate", tomorrow);

        $("#go-time-transport").timepicker(timePickerOptions);
        $("#goback-time-transport").timepicker({ 'timeFormat': 'H:i' });

        // handle btn-category to change its class css
        var lastActive = $(".btn-category.active");
        $(".btn-category").click(function(){
            lastActive.removeClass("active");
            lastActive = $(this);
            lastActive.addClass("active");
        });

        // handle open and close mini reserve window
        $('.each-package').on('click', '.btn-reserve' ,function(){
            var currentPackage = $(this);
            var targetForm = currentPackage.data("id");
            $("#" + targetForm).addClass("open");
        });

        // handle 
        $('.each-package').on('click', '.btn-close-quick-form', function(){
            var closer = $(this);
            var targetId = closer.data("id");
            var target = $("#" + targetId);
            var form = $("#" + targetId + " form");
            if (form && form[0] instanceof HTMLElement) {
                form[0].reset();
            }
            target.removeClass("open");
        });

        //handle scrollable links
        $('.menu-link').on('click', function(e){
            var self = $(this);
            var isScrollable = self.data('scrollable');
            if (isScrollable) {
                e.preventDefault();
                var target = self.attr('href');
                target = $(target);

                if (target.length > 0) {
                    var offset = target.offset();
                    var screen = $('html, body');
                    screen.stop().animate({scrollTop: offset.top}, 500, 'swing');
                }
            }
        });

        // handle active links
        var pathname = document.location.pathname;
        var links = $('.menu-link');
        
        links.each(function(index, element){
            var self = $(element);
            var href = self.data('href');

            if (href) {
                if (pathname.indexOf(href) > -1) {
                    self.addClass('active');
                    return;
                }
            }
        });

        function hideButtonWhenNotMore(more) {
            var loaderText = comment_config.loaderCommentText;
            if(!more) {
                loadBtnText.text(loaderText);
                iconProgress.addClass('hide');
                loadCommentsButton.addClass('hide');
                canLoadComments = false;
                return;
            }
            
            loadBtnText.text(loaderText);
            iconProgress.addClass('hide');
            canLoadComments = true;
        }

        contactFields.on('keyup', function(){
            var input = $(this);
            var type = input.attr("name");

            if(type) {
                var value = input.val();
                contactInformation[type] = value;
            }
        });

        function cleanFields() {
            if ($("#contact-form").length > 0) {
                $("#contact-form")[0].reset();
            }
        }

        buttonSenderContact.click(function(event){
            event.preventDefault();

            if(!canSendContactForm) {
                return;
            }

            jQuery.ajax({
                url : contact_form_config.ajax_url,
                type : 'post',
                data : {
                    action: contact_form_config.actionName,
                    nonce: contact_form_config.nonce,
                    name: contactInformation.name,
                    lastname: contactInformation.lastname,
                    subject: contactInformation.subject,
                    message: contactInformation.message
                },
                success : function( response ) {
                    if (response.success && response.data.sent) {
                        contactInformation = {};
                    }

                    if(!response.success) {
                        alertMessage.text(response.data.error);
                        alert.addClass('alert alert-dismissible alert-danger').removeClass('hide');
                    }

                    contactBtnText.text(contact_form_config.contact_text);
                    iconProgressContact.addClass('hide');
                    canSendContactForm = true;
                    cleanFields();
                },
                error : function(error) {
                    console.log("error", error);
                    canSendContactForm = true;
                },
                beforeSend: function() {
                    contactBtnText.text(contact_form_config.contact_progress_text);
                    iconProgressContact.removeClass('hide');
                    canSendContactForm = false;
                }
            });
        });

        $("#loader_posts").click(function(){
            var self = $(this);

            if (page === 0) {
                page = 1;
            }

            page++;

            jQuery.ajax({
                url : services_config.ajax_url,
                type : 'post',
                data : {
                    action: services_config.actionName,
                    nonce: services_config.nonce,
                    page: page,
                    taxID: self.data('tax')
                },
                success : function( response ) {
                    if (response.success) {
                        var packages = response.data.packages;

                        if (packages && packages.length > 0) {
                            packages.forEach(function(item){
                                $('.each-package').append(item);
                            });
                        }
                    }
                    
                    if (!response.data.more) {
                        self.remove();
                    }
                },
                error : function(error) {
                    console.log("error", error);
                },
                beforeSend: function() {
                    return;
                }
            });
        });

        loadCommentsButton.click(function(){
            if (!canLoadComments) {
                return;
            }
            
            page++;

            jQuery.ajax({
                url : comment_config.ajax_url,
                type : 'post',
                data : {
                    action : 'get_more_comments',
                    post_id : comment_config.post_id,
                    page: page,
                    nonce: comment_config.nonce
                },
                success : function( response ) {
                    var more = response.data.more;
                    
                    if (response.success) {
                        var comments = response.data.comments;
                        if (comments.length > 0) {
                            comments.forEach(function(comment) {
                                commentList.append(comment);
                            });
                        }
                    }

                    hideButtonWhenNotMore(more);
                },
                error : function(error) {
                    console.log("error", error);
                },
                beforeSend: function() {
                    loadBtnText.text(comment_config.loadingCommentText);
                    iconProgress.removeClass('hide');
                    canLoadComments = false;
                }
            });
        });
    })(jQuery);
});