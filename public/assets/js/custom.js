$(document).ready(function () {
    $('.complaint_person_name').on('keyup', function (param) {
        param.preventDefault();
        var input_value = $(this).val();
        if (input_value.length > 2) {
            $.ajax({
                data: {
                    'complaint': input_value
                },
                url: 'complaint_person_name',
                success: function (responce) {
                    $('#complaint_filter_list').show();
                    $('#complaint_filter_list').html(responce.data);
                    $(document).on('click', 'a[class^="get_complaint_"]', function (e) {
                        e.preventDefault();
                        var dataId = $(this).data('id');
                        var data_name = $(this).data('name');
                        $('input.complaint_person_name').val(data_name);
                        $('input.employee_name_input').val(dataId);
                        $('input.employee_name_input').attr('value', dataId);
                        $('#complaint_filter_list').hide();
                    });
                },
            });
        } else {
            // $('input.employees_filter_input').removeAttr('data-id');
        }
    });

    $('#complaint_filter').on('submit', function (e) {
        e.preventDefault();
        var complaint_filter_data = $(this).serialize();
        $('#overlay').show();

        $.ajax({
            url: '/complaint_filter',
            data: complaint_filter_data,
            success: function (response) {
                $('.complaint_table_list').html(response.data);
                $('.dataTables_info').text('Showing ' + response.count + ' of ' + response.count + ' entries');
                $('.data-table').removeAttr('data-datatables');
                $('.data-table').attr('data-datatables', '{"paging":false,"scrollY":"400px","searching":false,"scrollCollapse":true,"scrollX":true, "ordering": false}')
                // $('table').DataTable({
                //     searching: true
                // });
                $('#overlay').hide();
            },
            error: function (xhr, status, error) {
                console.error('An error occurred:', error);
                $('#overlay').hide();
            }
        });
    });

    $('input[name=start_date]').on('change', function (e) {
        e.preventDefault();
        var start_date = $(this).val();
        var today = new Date();
        var day = String(today.getDate()).padStart(2, '0');
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var year = today.getFullYear();
        var formattedDate = year + '-' + month + '-' + day;
        $('input[name=end_date]').val(start_date);
        $('input[name=end_date]').attr('max', formattedDate);
        $('input[name=end_date]').attr('min', start_date);
    });

    //File type file
    $('#files').on('change', function (event) {
        var files = event.target.files;
        var defaultImage = '/assets/img/generic/image-file-2.png';
        $('#file-preview').empty();
        $.each(files, function (index, file) {
            var fileContainer = $(
                '<div class="file-container pt-3 pb-3" style="border-bottom:1px solid #d9dada;display: flex;"></div>'
            );
            var img;
            var fileName = $(
                '<p class="file-name col-md-8" style="margin-left: 15px;"></p>').text(
                file.name);
            var removeButton = $('<button>&times;</button>').attr('data-file-index', index);
            removeButton.on('click', function () {
                $(this).parent().remove();
                var fileInput = $('#files')[0];
                var dt = new DataTransfer();
                var {
                    files
                } = fileInput;

                for (var i = 0; i < files.length; i++) {
                    if (i !== parseInt($(this).attr('data-file-index'))) {
                        dt.items.add(files[i]);
                    }
                }
                fileInput.files = dt.files;
            });

            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    img = $('<img style="height: 40px;">').attr('src', e.target.result);
                    fileContainer.append(img).append(fileName).append(removeButton);
                    $('#file-preview').append(fileContainer);
                };
                reader.readAsDataURL(file);
            } else {
                img = $('<img style="height: 40px;">').attr('src', defaultImage);
                fileContainer.append(img).append(fileName).append(removeButton);
                $('#file-preview').append(fileContainer);
            }
        });
    });

    // blending_details_form Validation
    // $("#blending_details_form").validate({
    //     rules: {
    //         ph_result: "required",
    //         temperature: "required",
    //         appearance: "required",
    //         odor: "required",
    //         taste: "required",
    //         comments: "required",
    //         initial: "required",

    //     },
    //     messages: {
    //         ph_result: "Please enter your PH Result.",
    //         temperature: "Temperature is required.",
    //         appearance: "Appearance is required.",
    //         odor: "Odor is required.",
    //         taste: "Taste is required.",
    //         comments: "Comments is required.",
    //         initial: "Initial is required.",
    //     },
    //     errorPlacement: function (error, element) {
    //         if (element.is(":radio")) {
    //             error.appendTo(element.parents('.form-group'));
    //         } else { // This is the default behavior
    //             error.insertAfter(element);
    //         }
    //     },
    //     submitHandler: function (form) {
    //         form.submit();
    //     }

    // });

    $("#blending_details_form").validate({
        rules: {
            ph_result: "required",
            temperature: "required",
            appearance: {
                required: true
            },
            odor: {
                required: true,
                checked: true,
            },
            taste: {
                required: true
            }
        },
        messages: {
            ph_result: "pH Result is require.",
            temperature: "Temperature is required.",
            appearance: "Appearance is required.",
            odor: "Odor is required.",
            taste: "Taste is required.",
            comments: "Comments are required.",
            initial: "Initial is required.",
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio")) {
                error.appendTo(element.closest('.radio-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#mixing_measure_form").validate({
        rules: {
            temperature_1: "required",
            weight_1: "required",
            table: "required",
            scale: "required",
        },
        messages: {
            temperature_1: "Temperature is require.",
            weight_1: "Weight  is required.",
            table: "Table is required.",
            scale: "Scale is required.",
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio")) {
                error.appendTo(element.closest('.radio-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $("#guacamole_measure_form").validate({
        rules: {
            temperature: "required",
            weight_checks_1: "required",
            total_containers: "required",
            retains_collected: "required",
        },
        messages: {
            temperature: "Temperature is required.",
            weight_checks_1: "Weight  is required.",
            total_containers: "Total Containers is required.",
            retains_collected: "Retains Collected is required.",
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio")) {
                error.appendTo(element.closest('.radio-group'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    $(".upload_qr_code_images").validate({
        rules: {
            image: "required",
        },
        messages: {
            image: "Please scan a bar code.",
        }
    });

    // add blending item form validation
    $('#add_blending_item_form').validate({
        rules: {
            item_name: "required",
            ph_min: {
                required: true,
                number: true,
                max: 99999
            },
            ph_max: {
                required: true,
                number: true,
                max: 99999
            },
            temperature: {
                required: true,
                number: true
            },
            weight: {
                required: true,
                number: true
            }
        },
        messages: {
            item_name: "Item name is required.",
            ph_min: {
                required: "Minimum pH is required.",
                number: "Minimum pH must be a number.",
                max: "Minimum pH cannot exceed 99999."
            },
            ph_max: {
                required: "Maximum pH is required.",
                number: "Maximum pH must be a number.",
                max: "Maximum pH cannot exceed 99999."
            },
            temperature: {
                required: "Temperature is required.",
                number: "Temperature must be a number."
            },
            weight: {
                required: "Weight is required.",
                number: "Weight must be a number."
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // add guacamole item form validation
    $('#add_guacamole_item_form').validate({
        rules: {
            item_name: "required",
            weight: {
                required: true,
                number: true,
            }
        },
        messages: {
            item_name: "Item name is required.",
            weight: {
                required: "Weight is required.",
                number: "Weight must be a number.",
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // add metal detector item form validation
    $('#add_metal_detector_item_form').validate({
        rules: {
            item_name: "required",
            weight: {
                required: true,
                number: true,
            }
        },
        messages: {
            item_name: "Item name is required.",
            weight: {
                required: "Weight is required.",
                number: "Weight must be a number.",
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // add mix item form validation
    $('#add_mix_item_form').validate({
        rules: {
            item_name: "required",
            ph_min: {
                required: true,
                number: true,
                max: 99999
            },
            ph_max: {
                required: true,
                number: true,
                max: 99999
            },
            temperature: {
                required: true,
                number: true
            },
            weight: {
                required: true,
                number: true,
            }
        },
        messages: {
            item_name: "Item name is required.",
            ph_min: {
                required: "Minimum pH is required.",
                number: "Minimum pH must be a number.",
                max: "Minimum pH cannot exceed 99999."
            },
            ph_max: {
                required: "Maximum pH is required.",
                number: "Maximum pH must be a number.",
                max: "Maximum pH cannot exceed 99999."
            },
            temperature: {
                required: "Temperature is required.",
                number: "Temperature must be a number."
            },
            weight: {
                required: "Weight is required.",
                number: "Weight must be a number",
            }
        },
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        highlight: function (element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
