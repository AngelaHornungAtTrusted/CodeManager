(function ($) {

    let $cForm;
    let myPopup;

    const pageInit = function () {
        $cForm = $('#code-check-form');

        $('#code-form-button').on('click', handleCodeForm);
    }

    const handleCodeForm = function (e) {
        e.preventDefault();

        $.post(CM_AJAX_URL, {
            action: 'cm_code_exists',
            code: $cForm.serializeObject()
        }, function(response){
            if (response.status === 'success') {
                handlePopUp(response.data);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const handlePopUp = function(code) {
        myPopup = new Popup({
            id: "popup",
            title: "Code Result",
            content: (code.length > 0) ? code[0].message : 'No Code Found',
        });

        myPopup.show();
    }

    $(document).ready(function () {
        pageInit();
    });
})(jQuery);