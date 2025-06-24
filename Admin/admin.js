(function ($) {

    let $cForm, $dForm, $cTable, $dTable, $dFile;
    let checked, wChecked, codeId;

    const pageInit = function () {
        //forms
        $cForm = $('#cm-code-form');
        $dForm = $('#cm-doc-form');

        //elements
        $cTable = $('#cm-code-table');
        $dTable = $('#dp-doc-table');
        $dFile = $('#dp-doc-upload');

        //init method
        grabCodes();

        //call form handlers
        $('#cm-code-submit').on('click', handleCodeForm);
        handleDocumentForm();
    }

    const grabCodes = function () {
        $.get(CM_AJAX_URL, {
                action: "cm_get_codes",
            }, function (response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    codeTableInit(response.data);
                } else {
                    toastr.error(response.message);
                }
            }
        );
    }

    const codeTableInit = function (codes) {
        //clear table
        $cTable[0].innerHTML = "";
        $.each(codes, function (key, code) {
            checked = code.active === '1' ? 'checked' : '';
            wChecked = code.winner === '1' ? 'checked' : '';
            $cTable.append('' +
                '<tr>' +
                '<td><input class="cm-code-title" id="code-title-' + code.id + '" type="text" value="' + code.code + '"></td>' +
                '<td><input class="cm-code-message" id="code-message-' + code.id + '" type="text" value="' + code.message.replace(/\\(.)/mg, "$1") + '"></td>' +
                '<td><input class="cm-code-expiration" id="code-expiration-' + code.id + '" type="datetime-local" value="' + code.expiration + '"></td>' +
                '<td><input class="cm-code-checkbox" type="checkbox" id="code-active-' + code.id + '" value="' + code.id + '" ' + checked + '></td>' +
                '<td><input class="cm-code-winner-checkbox" type="checkbox" id="code-winner-' + code.id + '" value="' + code.id + '" ' + wChecked + '> </td>' +
                '<td><a class="cm-code-delete btn btn-danger" id="code-delete-' + code.id + '">Delete</a></td>' +
                '</tr>');
        });

        //call events
        initPageActions();
    }

    const initPageActions = function () {
        //set up triggers
        $('.cm-code-title').off('change').on('change', updateCode);
        $('.cm-code-checkbox').off('click').on('click', updateCode);
        $('.cm-code-winner-checkbox').off('click').on('click', updateCode);
        $('.cm-code-message').off('change').on('change', updateCode);
        $('.cm-code-expiration').off('change').on('change', updateCode);
        $('.cm-code-delete').off('click').on('click', deleteCode);
    }

    const updateCode = function (e) {
        codeId = e.currentTarget.id.split('-')[2];
        $.post(CM_AJAX_URL, {
            action: "cm_update_code",
            code: {
                'id': codeId,
                'code': $('#code-title-' + codeId).val(),
                'message': $('#code-message-' + codeId).val(),
                'active': $('#code-active-' + codeId).is(':checked'),
                'winner': $('#code-winner-' + codeId).is(':checked'),
                'expiration': $('#code-expiration-' + codeId).val()
            }
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        });
    }

    const deleteCode = function (e) {
        $.post(CM_AJAX_URL, {
            action: "cm_delete_code",
            code: e.currentTarget.id.split('-')[2]
        }, function(response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                grabCodes();
            } else {
                toastr.error(response.message);
            }
        })
    }

    const handleCodeForm = function (e) {
        e.preventDefault();

        $.post(CM_AJAX_URL, {
            action: "cm_new_code",
            code: $cForm.serializeObject()
        }, function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                grabCodes();
            } else {
                toastr.error(response.message);
            }

            $cForm.children().val('');       //reset form
        });
    }

    const handleDocumentForm = function () {
        $dFile.on('change', async function (e) {
            if (this.files) {
                var myFile = this.files[0];
                var reader = new FileReader();

                reader.addEventListener('load', function (e) {
                    let csvData = e.target.result;
                    let data = csvData.split('\n');
                    $.each(data, function (key, value) {
                        //0 key is file banner
                        if (key > 0) {
                            let vars = value.split(',');
                            //upload code
                            $.post(CM_AJAX_URL, {
                                action: "cm_new_code",
                                code: {
                                    'cm-code': vars[0],
                                    'message': vars[1],
                                    'active': parseInt(vars[2]),
                                    'winner': parseInt(vars[3]),
                                    'expiration': vars[4]
                                }
                            }, function (response) {
                                if (response.status === 'success') {
                                } else {
                                    toastr.error(response.message);
                                }
                            });
                        }
                    });
                });
                reader.readAsText(myFile);
            }
            $dForm.children('input').val('');
            toastr.success("Codes Imported!");

            //required, otherwise new codes don't appear
            setTimeout(grabCodes, 5000);
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