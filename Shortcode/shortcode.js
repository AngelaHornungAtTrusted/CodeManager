(function($) {

    let $cForm;
    let myPopup;
    const plugin_url = `${window.location.origin}/wp-content/plugins/CodeManager/Shortcode/code.php`;

    const pageInit = function() {
        $cForm = $('#code-check-form');

        handleCodeForm();
    }

    const handleCodeForm = function() {
        $cForm.validate({
            focusInvalid: false,
            rules: {},
            message: {},

            submitHandler: function (f, e) {
                e.preventDefault();
                let validator = this;
                let promise = $.ajax({
                    url: plugin_url,
                    type: 'get',
                    data: $cForm.serializeObject(),
                }).done(function (response) {
                    if (response.data.content != null) {
                        myPopup = new Popup({
                            id: "popup",
                            title: "Your Code Is A",
                            content: response.data.content.message,
                        });
                        myPopup.show();
                   } else {
                       toastr.error('Invalid Code');
                   }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                    $cForm.children('input').val('');
                })
            }
        })
    }

    $(document).ready(function() {
        pageInit();
    });

    // secret sauce method, thanks Paul Colella
    jQuery.fn.serializeObject = function () {
        let arrayData, objectData;
        arrayData = this.serializeArray();
        objectData = {};

        $.each(arrayData, function () {
            let value;

            if (this.value != null) {
                value = this.value;
            } else {
                value = '';
            }

            if (objectData[this.name] != null) {
                if (! objectData[this.name].push) {
                    objectData[this.name] = [objectData[this.name]];
                }

                objectData[this.name].push(value);
            } else {
                objectData[this.name] = value;
            }
        });

        return objectData;
    };
})(jQuery);