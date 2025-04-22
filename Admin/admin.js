(function($) {

    let $cForm, $dForm, $cTable, $dTable, $dFile;
    let checked, wChecked, catId;

    const pageInit = function() {
        //forms
        $cForm = $('#cm-code-form');
        $dForm = $('#cm-doc-form');

        //elements
        $cTable = $('#cm-code-table');
        $dTable = $('#dp-doc-table');
        $dFile = $('#dp-doc-upload');

        //grab categories (grab docs after we have categories as those depend on the categories)
        grabCodes();

        //call form handlers
        handleCodeForm();
        handleDocumentForm();
    }

    const grabCodes = function () {
        let promise = $.ajax({
            url: $cTable.data('loader'),
            type: 'GET',
            data: {},
        }).done(function (response) {
            if (response.data.success === 'success') {
                toastr.success(response.data.message);
                codeTableInit(response.data.content);
            } else {
                toastr.error(response.data.message);
            }
        }).always(function (response, s, r) {
        });
    }

    const codeTableInit = function (codes) {
        //clear table
        $cTable[0].innerHTML="";
        $.each(codes, function (key, code) {
            /*'<td><input class="cm-code-expiration" type="datetime-local" id="cm-code-expiration-' + code.id + '" value="' + code.expiration + '"></td>' +*/
            checked = code.active === '1' ? 'checked' : '';
            wChecked = code.winner === '1' ? 'checked' : '';
            $cTable.append('' +
                '<tr>' +
                '<td><input class="cm-code-title" id="code-title-' + code.id + '" type="text" value="' + code.code + '"></td>' +
                '<td><input class="cm-code-message" id="code-message-' + code.id + '" type="text" value="' + code.message + '"></td>' +
                '<td><input class="cm-code-checkbox" type="checkbox" id="code-check-' + code.id + '" value="' + code.id + '" ' + checked + '></td>' +
                '<td><input class="cm-code-winner-checkbox" type="checkbox" id="code-winner-check-' + code.id + '" value="' + code.id + '" ' + wChecked + '> </td>' +
                '</tr>');
        });

        //call events
        codeActiveWatch();
        codeWinnerWatch();
        codeInputWatch();
        codeMessageWatch();
        codeExpirationWatch();
    }

    const codeActiveWatch = function () {
        $('.cm-code-checkbox').off('click');
        $('.cm-code-checkbox').on('click', function(e){
            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'cm-code-id':e.currentTarget.value,
                    'cm-code-status':e.currentTarget.checked,
                    'cm-post-type':1            //0 is for new category, 1 is to update activity, 2 is for title
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const codeWinnerWatch = function () {
        $('.cm-code-winner-checkbox').off('click');
        $('.cm-code-winner-checkbox').on('click', function(e){
            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'cm-code-id':e.currentTarget.value,
                    'cm-code-winner':e.currentTarget.checked,
                    'cm-post-type':3            //0 is for new category, 1 is to update activity, 2 is for title, 3 is for winner
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const codeInputWatch = function () {
        $('.cm-code-title').off('change');
        $('.cm-code-title').on('change', function(e){
            catId = e.currentTarget.id;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'cm-code-id':catId.split('-')[2],
                    'cm-code-title':e.currentTarget.value,
                    'cm-post-type':2            //0 is for new category, 1 is to update, 2 is for title
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const codeMessageWatch = function () {
        $('.cm-code-message').off('change');
        $('.cm-code-message').on('change', function(e){
            catId = e.currentTarget.id;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'cm-code-id':catId.split('-')[2],
                    'cm-code-message':e.currentTarget.value,
                    'cm-post-type':4            //0 is for new category, 1 is to update, 2 is for title, 3 is for winner and 4 is for title
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
    }

    const codeExpirationWatch = function () {
        $('.cm-code-expiration').off('change');
        $('.cm-code-expiration').on('change', function(e){
            catId = e.currentTarget.id;

            let promise = $.ajax({
                url: $cTable.data('loader'),
                type: 'POST',
                data: {
                    'cm-code-id':catId.split('-')[2],
                    'cm-code-expiration':e.currentTarget.value,
                    'cm-post-type':5            //0 is for new category, 1 is to update, 2 is for title, 3 is for winner, 4 is for title and 5 is for date
                },
            }).done(function (response) {
                if (response.data.success === 'success') {
                    toastr.success(response.data.message);
                } else {
                    toastr.error(response.data.message);
                }
            }).always(function (response, s, r) {
            });
        });
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
                    url: $cForm.prop('action'),
                    type: 'post',
                    data: $cForm.serializeObject(),
                }).done(function (response) {
                    if (response.data.success === 'success') {
                        toastr.success(response.data.message);
                        grabCodes();
                    } else {
                        toastr.error(response.data.message);
                    }
                }).fail(function () {
                    toastr.error('Unknown Error');
                }).always(function () {
                    $cForm.children('input').val('');
                });
            }
        });
    }

    const handleDocumentForm = function() {
        $dFile.on('change', function(e) {
            if (this.files) {
                var myFile = this.files[0];
                var reader = new FileReader();

                reader.addEventListener('load', function(e){
                    let csvData = e.target.result;
                    //console.log(csvData);
                    let data = csvData.split('\n');
                    $.each(data, function(key, value) {
                        //0 key is file banner
                        if (key > 0) {
                            let vars = value.split(',');
                            //upload code
                            let promise = $.ajax({
                                url: $cTable.data('loader'),
                                type: 'POST',
                                data: {
                                    'cm-code': vars[0],
                                    'cm-code-message': vars[1],
                                    'cm-code-active': parseInt(vars[2]),
                                    'cm-code-winner': parseInt(vars[3]),
                                    'cm-code-expiration': vars[4],
                                    'cm-post-type':0            //0 is for new category, 1 is to update, 2 is for title, 3 is for winner and 4 is for title
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
                    });
                });
                reader.readAsText(myFile);
            }
            $dForm.children('input').val('');
            grabCodes();
        });
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