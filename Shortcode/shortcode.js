(function ($) {

    let $cForm;
    let myPopup;
    const plugin_url = `${window.location.origin}/wp-content/plugins/CodeManager/Shortcode/code.php`;

    const pageInit = function () {
        $cForm = $('#code-check-form');

        handleCodeForm();
    }

    const handleCodeForm = function () {
        $cForm.validate({
            focusInvalid: false,
            rules: {},
            message: {},

            submitHandler: function (f, e) {
                e.preventDefault();
                let validator = this;
                let promise = $.ajax({
                    url: $cForm.prop('action'),
                    type: 'get',
                    data: $cForm.serializeObject(),
                }).done(function (response) {
                    if (response.data.content != null) {
                        //not great implementation but couldn't get SQL to do it so we check here
                        if (new Date(response.data.content.expiration) <= new Date()) {
                            myPopup = new Popup({
                                id: "popup",
                                title: "Code Result",
                                content: "Code Expired!",
                            });
                        } else {
                            myPopup = new Popup({
                                id: "popup",
                                title: "Code Result",
                                content: response.data.content.message,
                            });

                            console.log(response.data.content.id);
                            console.log(response.data.content.active);

                            //expireCode(response.data.content.id);
                        }
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

    const expireCode = function (code, active) {
        let promise = $.ajax({
            url: $cForm.prop('action'),
            type: 'POST',
            data: {
                'cm-code-id': code,
            },
        }).done(function (response) {
            if (response.data.success === 'success') {
                toastr.success(response.data.message);
            } else {
                toastr.error(response.data.message);
            }
        }).always(function (response, s, r) {
        });
    }

    $(document).ready(function () {
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
                if (!objectData[this.name].push) {
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