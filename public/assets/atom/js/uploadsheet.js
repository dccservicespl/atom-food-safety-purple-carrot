  const fileInput = document.getElementById('file-input');
    const fileStatus = document.getElementById('file-status');
    const dropZone = document.getElementById('drop-zone');
    const submitBtn = document.getElementById('submit-btn');

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            updateFileStatus(file.name);
        }
    });

    // Handle Drag & Drop
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

    // Handle Submit click
    submitBtn.addEventListener('click', () => {
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Processing...';
        submitBtn.disabled = true;
        
        // Mock upload delay
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Uploaded Successfully';
            submitBtn.style.backgroundColor = '#059669';
        }, 1500);
    });

    function updateFileStatus(name) {
        fileStatus.innerHTML = `<i class="bi bi-check-circle-fill" style="color:green;"></i> Selected: <strong>${name}</strong>`;
        fileStatus.classList.remove('text-muted');
        fileStatus.style.color = '#0076a8';
        
        // Enable the submit button
        submitBtn.disabled = false;
    }