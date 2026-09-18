<div class="signature-container">
    <div class="signature-pad-wrapper">
        <canvas 
            id="signature-pad-{{ $name }}" 
            class="signature-pad"
            width="{{ $width ?? 600 }}" 
            height="{{ $height ?? 200 }}"
            style="border: 1px solid #ddd; background-color: #f9f9f9; touch-action: none;"
        ></canvas>
    </div>
    
    <div class="signature-controls mt-3">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSignature('{{ $name }}')">
            <i class="bi bi-eraser"></i> Clear
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="undoSignature('{{ $name }}')">
            <i class="bi bi-arrow-counterclockwise"></i> Undo
        </button>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="saveSignature('{{ $name }}')">
            <i class="bi bi-check-circle"></i> Save Signature
        </button>
    </div>
    
    <input type="hidden" name="{{ $name }}" id="signature-input-{{ $name }}" value="{{ old($name) }}">
    
    <div class="signature-status mt-2" id="signature-status-{{ $name }}">
        @if(old($name))
            <div class="alert alert-success py-1 px-2 d-inline-block">
                <i class="bi bi-check-circle-fill"></i> Signature saved
            </div>
        @else
            <div class="alert alert-warning py-1 px-2 d-inline-block">
                <i class="bi bi-exclamation-circle"></i> No signature yet
            </div>
        @endif
    </div>
    
    @if(old($name))
    <div class="signature-preview mt-3">
        <p class="text-muted mb-1">Preview:</p>
        <img src="{{ old($name) }}" class="signature-image-preview" style="max-width: 300px; border: 1px solid #ddd;">
    </div>
    @endif
</div>

@push('styles')
<style>
    .signature-pad {
        cursor: crosshair;
        width: 100%;
        height: 200px;
        touch-action: none;
    }
    
    .signature-pad-wrapper {
        position: relative;
        background: white;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .signature-controls .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .signature-image-preview {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px;
        background: white;
        max-height: 150px;
    }
</style>
@endpush

@push('scripts')
<!-- Load Signature Pad from CDN -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
// Store all signature pad instances
let signaturePads = {};

document.addEventListener('DOMContentLoaded', function() {
    initializeSignaturePad('{{ $name }}');
});

function initializeSignaturePad(name) {
    const canvas = document.getElementById('signature-pad-' + name);
    const ctx = canvas.getContext('2d');
    
    // Set up canvas dimensions
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        
        // Get the actual display size of the canvas
        const displayWidth = canvas.clientWidth;
        const displayHeight = canvas.clientHeight;
        
        // Set the canvas internal size to match display size * ratio
        canvas.width = displayWidth * ratio;
        canvas.height = displayHeight * ratio;
        
        // Scale all drawing operations by the ratio
        ctx.scale(ratio, ratio);
        
        // Clear and redraw if there was existing data
        if (signaturePads[name] && !signaturePads[name].isEmpty()) {
            const data = signaturePads[name].toData();
            signaturePads[name].clear();
            signaturePads[name].fromData(data);
        }
    }
    
    // Create signature pad instance
    signaturePads[name] = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        throttle: 16, // milliseconds
        minWidth: 0.5,
        maxWidth: 2.5,
        velocityFilterWeight: 0.7,
    });
    
    // Handle window resize
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    
    // Auto-save on end of drawing
    canvas.addEventListener('mouseup', function() {
        updateSignature(name);
    });
    
    canvas.addEventListener('touchend', function() {
        updateSignature(name);
    });
    
    // Load existing signature if available
    const existingSignature = document.getElementById('signature-input-' + name).value;
    if (existingSignature) {
        loadSignatureFromDataURL(name, existingSignature);
    }
}

function clearSignature(name) {
    if (signaturePads[name]) {
        signaturePads[name].clear();
        document.getElementById('signature-input-' + name).value = '';
        updateSignatureStatus(name, false);
    }
}

function undoSignature(name) {
    if (signaturePads[name]) {
        const data = signaturePads[name].toData();
        if (data) {
            data.pop(); // Remove the last dot or line
            signaturePads[name].fromData(data);
            updateSignature(name);
        }
    }
}

function saveSignature(name) {
    updateSignature(name);
    
    if (!signaturePads[name].isEmpty()) {
        alert('Signature saved successfully!');
    } else {
        alert('Please draw your signature first.');
    }
}

function updateSignature(name) {
    if (!signaturePads[name] || signaturePads[name].isEmpty()) {
        document.getElementById('signature-input-' + name).value = '';
        updateSignatureStatus(name, false);
        return;
    }
    
    const signatureData = signaturePads[name].toDataURL('image/png');
    document.getElementById('signature-input-' + name).value = signatureData;
    updateSignatureStatus(name, true);
    
    // Show preview
    showSignaturePreview(name, signatureData);
}

function updateSignatureStatus(name, isSaved) {
    const statusDiv = document.getElementById('signature-status-' + name);
    if (isSaved) {
        statusDiv.innerHTML = '<div class="alert alert-success py-1 px-2 d-inline-block"><i class="bi bi-check-circle-fill"></i> Signature saved</div>';
    } else {
        statusDiv.innerHTML = '<div class="alert alert-warning py-1 px-2 d-inline-block"><i class="bi bi-exclamation-circle"></i> No signature yet</div>';
    }
}

function showSignaturePreview(name, dataUrl) {
    // Remove existing preview
    const existingPreview = document.querySelector('.signature-preview');
    if (existingPreview && existingPreview.parentNode === document.querySelector('.signature-container')) {
        existingPreview.remove();
    }
    
    // Create new preview
    const previewDiv = document.createElement('div');
    previewDiv.className = 'signature-preview mt-3';
    previewDiv.innerHTML = `
        <p class="text-muted mb-1">Preview:</p>
        <img src="${dataUrl}" class="signature-image-preview" style="max-width: 300px; border: 1px solid #ddd;">
    `;
    
    document.querySelector('.signature-container').appendChild(previewDiv);
}

function loadSignatureFromDataURL(name, dataUrl) {
    if (!signaturePads[name] || !dataUrl) return;
    
    const img = new Image();
    img.onload = function() {
        const canvas = document.getElementById('signature-pad-' + name);
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        
        // Update signature pad data
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        signaturePads[name].fromData(signaturePads[name].toData());
        
        updateSignatureStatus(name, true);
    };
    img.src = dataUrl;
}

// Form validation
document.getElementById('applicationForm')?.addEventListener('submit', function(e) {
    const signatureInput = document.getElementById('signature-input-{{ $name }}');
    if (!signatureInput.value) {
        e.preventDefault();
        alert('Please provide your signature before submitting.');
        return false;
    }
    return true;
});
</script>
@endpush