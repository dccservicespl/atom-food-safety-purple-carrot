const fileInput = document.getElementById('file-input');
const fileStatus = document.getElementById('file-status');
const dropZone = document.getElementById('drop-zone');
const submitBtn = document.getElementById('submit-btn');

fileInput.addEventListener('change', function (e) {

    const file = e.target.files[0];

    if (file) {
        updateFileStatus(file.name);
    }

});

// drag & drop
dropZone.addEventListener('dragover', (e) => {

    e.preventDefault();
    dropZone.classList.add('dragover');

});

dropZone.addEventListener('dragleave', () => {

    dropZone.classList.remove('dragover');

});

dropZone.addEventListener('drop', (e) => {

    e.preventDefault();

    dropZone.classList.remove('dragover');

    const file = e.dataTransfer.files[0];

    if (file) {

        fileInput.files = e.dataTransfer.files;

        updateFileStatus(file.name);

    }

});

// submit using ajax
$('#submit-btn').on('click', function () {

    let formData = new FormData();

    formData.append('file', $('#file-input')[0].files[0]);

    let btn = $(this);

    btn.html('<i class="bi bi-arrow-repeat"></i> Processing...');
    btn.prop('disabled', true);

    let url = $('#upload-form').data('url');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                // btn.html('<i class="bi bi-check-circle-fill"></i> Uploaded Successfully');
                btn.html('Submit Sheet');
                btn.removeClass('btn-primary').addClass('btn-success');
                showFlash('success', response.message);
                resetForm();
            }
        },

        error: function (xhr) {
            btn.html('<i class="bi bi-upload"></i> Upload');
            btn.prop('disabled', false);

            let message = 'Something went wrong. Please try again.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            showFlash('error', message);
        }
    });
});

function showFlash(type, message) {

    const config = {
        success: { alertClass: 'alert-success', borderClass: 'border-success', btnClass: 'text-success' },
        warning: { alertClass: 'alert-warning', borderClass: 'border-warning', btnClass: 'text-warning' },
        info: { alertClass: 'alert-info', borderClass: 'border-info', btnClass: 'text-info' },
        error: { alertClass: 'alert-danger', borderClass: 'border-danger', btnClass: 'text-danger' },
    };

    const { alertClass, borderClass, btnClass } = config[type] || config['error'];

    const html = `
        <div class="alert ${alertClass} border ${borderClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close ${btnClass}" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

    $('#flash-container').html(html);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        $('#flash-container .alert').alert('close');
    }, 5000);
}

function updateFileStatus(name) {

    fileStatus.innerHTML =
        `<i class="bi bi-check-circle-fill" style="color:green;"></i>
        Selected: <strong>${name}</strong>`;

    fileStatus.classList.remove('text-muted');

    fileStatus.style.color = '#0076a8';

    submitBtn.disabled = false;
}

function resetForm() {
    $('#file-input').val('');

    fileStatus.innerHTML = 'No file selected';
    fileStatus.classList.add('text-muted');
    fileStatus.style.color = '';

    submitBtn.disabled = true;
}