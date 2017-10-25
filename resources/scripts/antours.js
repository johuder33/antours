jQuery(document).ready(function(){
    (function($, AntoursValidator){
        // register reservation config
        AntoursValidator.setMapper(reservation_config);
        var quickFields = AntoursValidator.getMapper();
        var validators = AntoursValidator.validators;

        var language = JSON.parse(JSON.stringify(antours_language)).language;

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
        var reservation = {};
        var btnMenuMobile = $("#btn-menu");

        // object for making service for transportation
        var roundTrip = $(".roundtrip");
        var startfrom = $(".startfrom");

        var transports = {
            city_id: null,
            commune_id: null,
            is_round_trip: getInitialCheckedValue(roundTrip),
            start_from_home: getInitialCheckedValue(startfrom),
            street: null,
            build_nro: null,
            dpto: null,
            reference_point: null,
            passengers: 1,
            date_start: null,
            date_end: null,
            time_start: null,
            time_end: null,
            service_id: null
        };

        tomorrow.setDate(tomorrow.getDate() + 1);

        datesConfig = {
            "pt": {
                startDate: currentDate,
                format: 'dd/mm/yyyy',
                days: ["Domingo", "Segunda-feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado"],
                daysShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
                daysMin: ['D','S','T','Q','Q','S','S'],
                monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Sep", "Out", "Nov", "Dez"]
            },
            "es": {
                startDate: currentDate,
                format: 'dd/mm/yyyy',
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
            },
            "en": {
                startDate: currentDate,
                format: 'dd/mm/yyyy',
            }
        }

        var datePickerOptions = datesConfig[language];

        var currentTime = new Date();
        currentTime.addHours(2);

        var timePickerOptions = {
            'minTime': "00:00",
            'disableTimeRanges': [["00:00", currentTime]],
            'timeFormat': 'H:i',
            'step': 10,
            'forceRoundTime': true
        };

        var goDatePicker = $("#go-date-transport").datepicker(datePickerOptions);
        $("#go-date-transport").datepicker("setDate", currentDate);

        var returnDatePicker = $("#return-date-transport").datepicker(datePickerOptions);

        var goTimePicker = $("#go-time-transport").timepicker(timePickerOptions);
        // let's set current time and disabled time passed
        var returnTimePicker = $("#return-time-transport").timepicker({ 'timeFormat': 'H:i' });

        // onSelect startDate datepicker
        goDatePicker.on('pick.datepicker', function(e) {
            goDatePicker.datepicker("hide");
            transports['date_start'] = e.date;

            console.log("event", e);

            var currentDate = goDatePicker.datepicker("getDate");
            // if exist returnDate, so verify if less than new startDate, if so, let's delete it
            var hasReturnDate = removeBlanks(returnDatePicker.val());
            if (hasReturnDate.length > 0) {
                var returnDate = returnDatePicker.datepicker("getDate");
                if ((currentDate.getTime() === returnDate.getTime()) || currentDate.getTime() > returnDate.getTime()) {
                    returnDatePicker.datepicker("reset");
                }
            }
            
            returnDatePicker.datepicker("setStartDate", e.date);
        });

        //onSelect startTime timepicker
        goTimePicker.on('changeTime', function(e){
            var currentLocalTime = goTimePicker.timepicker('getTime');
            var currentReturnTime = returnTimePicker.timepicker('getTime');
            var hasReturnDate = removeBlanks(returnDatePicker.val());
            var hasGoDate = removeBlanks(goDatePicker.val());

            if (hasReturnDate.length > 0 && hasGoDate.length > 0) {
                if (goDatePicker.datepicker("getDate").getTime() === returnDatePicker.datepicker("getDate").getTime()) {
                    if ((currentLocalTime && currentLocalTime.getTime()) > (currentReturnTime && currentReturnTime.getTime())) {
                        returnTimePicker.val("");
                    }

                    timePickerOptions.disableTimeRanges = [["00:00", currentLocalTime]];
                } else {
                    timePickerOptions.disableTimeRanges = currentLocalTime;
                }
            }

            returnTimePicker.timepicker("option", timePickerOptions);
        });

        // onSelect returnDate datepicker
        returnDatePicker.on('pick.datepicker', function(e) {
            returnDatePicker.datepicker("hide");
            var startDate = goDatePicker.datepicker('getDate');
            if (startDate.getTime() === e.date.getTime()) {
                var currentLocalTime = goTimePicker.timepicker('getTime');

                returnTimePicker.val("");
                currentLocalTime = currentLocalTime ? currentLocalTime : currentTime;
                timePickerOptions.disableTimeRanges = [["00:00", currentLocalTime]];
            } else {
                timePickerOptions.disableTimeRanges = [];
            }

            returnTimePicker.timepicker("option", timePickerOptions);
        });

        // handle btn-category to change its class css
        var lastActive = $(".btn-category.active");
        $(".btn-category").click(function(){
            lastActive.removeClass("active");
            lastActive = $(this);
            lastActive.addClass("active");
        });

        btnMenuMobile.on("click", function(){
            $(".menu-list").toggleClass("open");
        });

        /* QUICK FORM */
        // handle open and close mini reserve window
        $('.btn-reserve').on('click' ,function(){
            var currentPackage = $(this);
            var quickForm = currentPackage.data("id");
            var fields = $("#" + quickForm + ' .quick-field');

            if (!reservation.hasOwnProperty(quickForm)) {
                reservation[quickForm] = {};
            }

            fields.each(function(index, element){
                var current = $(element);
                var name = current.attr('name');

                reservation[quickForm][name] = {
                    value: current.val(),
                    field: current
                }
            });

            $("#" + quickForm).addClass("open");
        });

        // handle close quick form
        $('.btn-close-quick-form').on('click', function(){
            var currentPackage = $(this);
            var targetId = currentPackage.data("id");
            var target = $("#" + targetId);
            var quickForm = $("#" + targetId + " form");
            if (quickForm.length && quickForm[0] instanceof HTMLElement) {
                quickForm[0].reset();
            }

            // remove any data stored if close the quick form
            if (reservation[targetId]) {
                delete reservation[targetId];
            }

            target.removeClass("open");
        });

        $('.quick-field').on('keyup', function(e){
            var currentField = $(this);
            var currentPackage = currentField.data('id');
            var name = currentField.attr('name');
            var value = removeBlanks(currentField.val());

            var currentReservation = reservation[currentPackage];

            currentReservation[name].value = value;
        });

        $('.btn-makeReserve').on('click', function() {
            var packageId = $(this).data("id");
            var form = $("#" + packageId + " form");
            var fields = reservation[packageId];
            var emptyFormMessage = quickFields.empty;
            var postId = packageId.split("-");
            postId = postId ? postId.pop() : false;

            if (!fields) {
                window.alert(emptyFormMessage);
                return;
            }

            var fieldsToValidate = quickFields.validators;
            var errors = [];

            for (field in fieldsToValidate) {
                var currentField = fieldsToValidate[field];
                var attributes = currentField.attributes;
                var isRequired = attributes.required;
                var currentData = fields[field];
                var currentValue = currentData ? currentData.value : false;
                var currentFieldHTML = currentData.field;
                var errorItem = currentFieldHTML.next();

                if (isRequired || (currentValue && removeBlanks(currentValue).length > 0)) {
                    var validator = validators[field];
                    var error;

                    if (validator) {
                        isValid = validator(currentValue, attributes);

                        if (!isValid) {
                            var errorMessage = currentField.error;
                            currentFieldHTML.parent().addClass('has-error');
                            errorItem.text(errorMessage);
                            errors.push(true);
                            break;
                        }
                    }

                    if (!errorItem.is(':empty')) {
                        errorItem.text("");
                        currentFieldHTML.parent().removeClass('has-error');
                    }
                }
            }

            if (errors.length > 0) {
                return; 
            }

            var data = normalizeDataPackage(fields);

            data.action = quickFields.actionName;
            data.nonce = quickFields.nonce;
            data.postId = postId;
            var loader = $("#"+ packageId + " .layout-loader");
            var btnClosing = $("#" + packageId + " .btn-close-quick-form");

            sendRequest(data, function(data, status) {
                if (form.length > 0) {
                    form.trigger("reset");
                    btnClosing.trigger("click");
                }
            }, function(XHR) {
                console.log("XHR error", XHR);
            }, function() {
                loader.addClass("active");
            }, function() {
                loader.removeClass("active");
            });
            
        });
        /* QUICK FORM */

        function normalizeDataPackage(fields) {
            var data = {};
            $.each(fields, function(fieldName, object) {
                var value = object['value'];
                if (value.length > 0) {
                    data[fieldName] = value;
                }
            });

            return data;
        }

        function sendRequest(data, onSuccess, onError, beforeSend, onComplete) {
            $.ajax({
                url : contact_form_config.ajax_url,
                type : 'post',
                data : data,
                success: function(_data, textStatus, XHR) {
                    if (onSuccess) {
                        onSuccess(_data, textStatus, XHR);
                    }
                },
                error: function(XHR, textStatus, errorThrown) {
                    if (onError) {
                        onError(XHR, textStatus, errorThrown);
                    }
                },
                beforeSend: function(XHR, object) {
                    if (beforeSend) {
                        beforeSend(XHR, object);
                    }
                },
                complete: function(XHR, textStatus) {
                    if(onComplete) {
                        onComplete(XHR, textStatus);
                    }
                }
            });
        }

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

        var validatorByName = {
            fullname: function(field) {
                var isSuccessful = false;
                var maxLength = 100;
                var minLength = 5;
                var value = removeBlanks(field.val());
                var isRequired = field.attr('required');
                var length = value.length;

                if (isRequired) {
                    if (length >= minLength && length <= maxLength) {
                        isSuccessful = true;
                    }
                }

                return isSuccessful;
            },
            id_number: function(field) {
                var isSuccessful = false;
                var idNumberRegExp = /^(?!^0+$)[a-zA-Z0-9]{3,20}$/;
                var value = field.val();

                if (value.match(idNumberRegExp)) {
                    isSuccessful = true;
                }

                return isSuccessful;
            },
            phones: function(field) {
                var isSuccessful = false;
                var phoneRegExp = /^[0-9]{5,15}$/;
                var value = field.val();

                if (value.match(phoneRegExp)) {
                    isSuccessful = true;
                }

                return isSuccessful;
            },
            amount_passenger: function(field) {
                var isSuccessful = false;
                var amountRegExp = /^[0-9]{1,3}$/;
                var value = field.val();

                if (value.match(amountRegExp)) {
                    isSuccessful = true;
                }

                return isSuccessful;
            },
            hotel_address: function(field) {
                var isSuccessful = true;
                var value = field.val();

                if (removeBlanks(value).length > 0) {
                    if (value.length > 255) {
                        isSuccessful = false;
                    }
                }

                return isSuccessful;
            },
            service_type: function(field) {
                var isSuccessful = true;
                var value = field.val();

                return isSuccessful;
            },
            email: function(field) {
                var isSuccessful = false;
                var value = field.val();
                var emailRegexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                if (value.match(emailRegexp)) {
                    isSuccessful = true;
                }

                return isSuccessful;
            }
        }

        function validateFields(postId) {
            if (!postId) return false;
            var fields = $('#package-'+postId+' .quick-field');
            var limit = fields.length;
            var hasValidInputs = true;
            
            fields.each(function(index, element){
                if (!hasValidInputs) {
                    return false;
                }

                var field = $(this);
                var name = field.attr('name');

                if (validatorByName.hasOwnProperty(name)) {
                    hasValidInputs = validatorByName[name](field);
                    if (!hasValidInputs) {
                        field.addClass('error');
                    }
                }
            });

            return !hasValidInputs;
        }

        function makeReservation(button, loader) {

        }

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

        /* Listen for click on reservation button hanlders */
        $(".btn-next-step").on("click", function(event){
            event.preventDefault();
            var currentButton = $(this);
            var nextStep = currentButton.data("step");
            var errors = [];

            if (nextStep === ".step2") {
                errors = validateFirstStep();
            }

            if (errors.length > 0) {
                return;
            }

            $(".steps").removeClass("current");
            $(nextStep).addClass("current");
        });

        $("#getCities").change(function() {
            var communeSelectId = "#getCommuneByCityId";
            var selectByCommune = $(communeSelectId);
            var firstOptionText = $(communeSelectId + " option:first").text();
            var spinner = $(".spinner-commune");
            var value = $(this).val();

            transports["city_id"] = value;
            transports["commune_id"] = null;

            $.ajax({
                url : booking_service_config.ajax_url,
                type : 'post',
                data : { cityId: value, action: booking_service_config.actionName, nonce: booking_service_config.nonce },
                success: function(_data, textStatus, XHR) {
                    selectByCommune.text("");
                    selectByCommune.append($("<option>", {
                        value: "",
                        text: firstOptionText
                    }));

                    if (_data && _data.success) {
                        var communes = _data.data;
                        $.each(communes, function(index, item) {
                            selectByCommune.append($("<option>", {
                                value: item.id_commune,
                                text: item.name
                            }))
                        });
                    }
                },
                error: function(XHR, textStatus, errorThrown) {
                    
                },
                beforeSend: function(XHR, object) {
                    spinner.removeClass("d-none");
                    selectByCommune.addClass("d-none");
                },
                complete: function(XHR, textStatus) {
                    spinner.addClass("d-none");
                    selectByCommune.removeClass("d-none");
                }
            });
        });

        $("#getCommuneByCityId").change(function(evt){
            var self = $(this);
            var communeId = self.val();

            // assign the value to the object transport
            transports["commune_id"] = communeId;
        });

        $(".startfrom").click(function(){
            // update start trip from value
            // 0 => Airport
            // 1 => Home
            var startFrom = $(this).val();
            startFrom = getValAsBool(startFrom);
            transports["start_from_airport"] = startFrom;
        })

        function getValAsBool(value) {
            if (value) {
                var val = parseInt(value, 10);

                if (!isNaN(val)) {
                    val = !!val;
                    return val;
                }
            }

            return value;
        }

        function removeBlanks(value) {
            var val = $.trim(value);

            return val;
        }

        function getInitialCheckedValue(field) {
            var value = null;
            for(var i = 0, limit = field.length; i < limit; i++) {
                var currentField = field[i];
                if (currentField.checked) {
                    var val = currentField.value;
                    value = getValAsBool(val);
                    break;
                }
            }

            return value;
        }

        $(".roundtrip").click(function(){
            // update start trip from value
            // 0 => one trip
            // 1 => round trip
            var roundtrip = $(this).val();
            roundtrip = getValAsBool(roundtrip);
            transports["is_round_trip"] = roundtrip;
            var containerReturnDate = $(".container-return-date");

            if (roundtrip) {
                containerReturnDate.show();
            } else {
                containerReturnDate.hide();
            }
        })

        function sanitizeValue(value) {
            var val = removeBlanks(value);

            return val.length > 0 ? val : null;
        }

        $(".t-input-control").keyup(function(){
            var currentInput = $(this);
            var dataId = currentInput.data("id");
            var newValue = sanitizeValue(currentInput.val());
            var oldValue = transports[dataId];

            if (newValue !== oldValue) {
                transports[dataId] = newValue;
            }
        });

        function validateFirstStep() {
            var errors = [];
            var city_id = transports.city_id;
            var commune_id = transports.commune_id;
            var street = transports.street;
            var build_nro = transports.build_nro;
            var dpto = transports.dpto;
            
            if (city_id === "" || city_id === null || city_id === undefined) {
                errors.push("City is missing");
            }

            if (commune_id === "" || commune_id === null || commune_id === undefined) {
                errors.push("Commune is missing");
            }

            if (street === "" || street === null || street === undefined) {
                errors.push("Street is missing");
            }

            if (build_nro === "" || build_nro === null || build_nro === undefined) {
                errors.push("Nro is missing");
            }

            if (dpto === "" || dpto === null || dpto === undefined) {
                errors.push("Dpto is missing");
            }

            return errors;
        }

    })(jQuery, AntoursValidator);
});

/* alter prototype for Date Object */

Date.prototype.addHours = function(hours) {
    if (!hours) return;
    var hoursAdded = (Math.abs(hours) * 60 * 60 * 1000);
    this.setTime(this.getTime() + hoursAdded);
    return this;
}

Date.prototype.getHumanDate = function() {
    
}

/**
 * Create a Validator for fields
 */

function Validator(values, $) {
    this.$ = $;
    this.fields = {};
    this.errors = [];
    this.values = {};
}

Validator.prototype.setFields = function(fields) {
    for(var i = 0, limit = fields.length; i < limit; i++) {
        var field = fields[i];
        var name = field.element.data("name");
        this.values[name] = field.element.val();
        this.listen(field);
        this.fields[name] = field;
    }
}

Validator.prototype.listen = function(field) {
    field.element.change(function(){
        var name = field.element.data("name");
        this.values[name] = field.element.val();
    }.bind(this));
}

Validator.prototype.setValidator = function(fieldName, validator) {
    this.fields[fieldName] = validator;
}

Validator.prototype.flushErrors = function() {
    this.errors.length = 0;
}

Validator.prototype.makeValidation = function() {
    var limit = arguments.length;

    for(var i = 0; i < limit; i++) {
        var field = arguments[i];

        if (this.fields.hasOwnProperty(field)) {
            var currentField = this.fields[field];
            var value = this.values[field];
            currentField.validator.call(this, value, currentField);
        }
    }
}

Validator.prototype.hasErrors = function() {
    return this.errors.length;
}

Validator.prototype.addError = function(message) {
    this.errors.push(message);
}

/**
 * Field class type
 */

function Field(element) {
    this.element = element;
}