const fileInput = document.getElementById('file-input');
const fileStatus = document.getElementById('file-status');
const dropZone = document.getElementById('drop-zone');
const submitBtn = document.getElementById('submit-btn');

fileInput.addEventListener('change', function (e) {

    const file = e.target.files[0];

    if (file) {
        updateFileStatus(file.name);
    } else {
        resetForm();
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

    const file = fileInput.files[0];
    if (!file) {
        return;
    }

    let formData = new FormData();

    formData.append('file', file);

    let btn = $(this);

    btn.html('<i class="bi bi-arrow-repeat"></i> Reading Sheets...');
    btn.prop('disabled', true);

    let url = $('#upload-form').data('url');

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
            btn.html('Submit Sheet');
            btn.prop('disabled', false);

            if (response.status === 'select_sheets') {
                showSheetSelection(response);
            }
        },

        error: function (xhr) {
            btn.html('Submit Sheet');
            btn.prop('disabled', false);

            let message = 'Something went wrong. Please try again.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            showFlash('error', message);
        }
    });
});

$('#process-selected-sheets').on('click', function () {
    const selectedSheets = Array.from(document.querySelectorAll('#portioningSheetChoices input:checked'))
        .map((checkbox) => checkbox.value);

    if (selectedSheets.length === 0) {
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    selectedSheets.forEach((sheetName) => formData.append('selected_sheets[]', sheetName));

    const submitButton = $(this);
    const uploadButton = $('#submit-btn');
    submitButton.html('<i class="bi bi-arrow-repeat"></i> Processing...');
    submitButton.prop('disabled', true);
    uploadButton.prop('disabled', true);

    $.ajax({
        url: $('#upload-form').data('url'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portioningSheetModal')).hide();
                showFlash('success', response.message);
                resetForm();
                setTimeout(() => location.reload(), 3000);
            }
        },
        error: function (xhr) {
            submitButton.html('Submit Sheet');
            submitButton.prop('disabled', false);
            uploadButton.prop('disabled', false);

            let message = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            showFlash('error', message);
        }
    });
});

function showSheetSelection(response) {
    const choices = document.getElementById('portioningSheetChoices');
    const selected = new Set(response.selected_sheets);
    const selectedCount = document.getElementById('portioningSelectedCount');
    const submitButton = document.getElementById('process-selected-sheets');

    document.getElementById('portioningFileName').textContent = response.file_name;
    choices.replaceChildren();

    response.sheet_names.forEach((sheetName, index) => {
        const wrapper = document.createElement('div');
        const input = document.createElement('input');
        const label = document.createElement('label');

        wrapper.className = 'form-check form-check-inline m-0';
        input.className = 'btn-check';
        input.type = 'checkbox';
        input.id = `portioning-sheet-${index}`;
        input.value = sheetName;
        input.checked = selected.has(sheetName);
        label.className = 'btn btn-outline-secondary btn-sm';
        label.htmlFor = input.id;
        label.textContent = sheetName;

        input.addEventListener('change', updateSelectedCount);
        wrapper.append(input, label);
        choices.append(wrapper);
    });

    function updateSelectedCount() {
        const count = choices.querySelectorAll('input:checked').length;
        selectedCount.textContent = `${count} Tabs selected`;
        submitButton.disabled = count === 0;
    }

    updateSelectedCount();
    bootstrap.Modal.getOrCreateInstance(document.getElementById('portioningSheetModal')).show();
}

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