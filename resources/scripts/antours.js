jQuery(document).ready(function(){
    (function($){
        var currentDate = new Date();
        var tomorrow = new Date();
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
        $(".btn-reserve").click(function(){
            var currentPackage = $(this);
            var targetForm = currentPackage.data("id");
            console.log($("#" + targetForm));
            $("#" + targetForm).addClass("open");
        });

        // handle 
        $(".btn-close-quick-form").click(function(){
            var closer = $(this);
            var targetId = closer.data("id");
            var target = $("#" + targetId);
            var form = $("#" + targetId + " form");
            if (form && form[0] instanceof HTMLElement) {
                form[0].reset();
            }
            target.removeClass("open");
        });
    })(jQuery);
});