<style>
.file-uploader-custom {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}
.file-uploader-custom .uploader-card {
    border: 2px dashed #d6d6d6;
    border-radius: 14px;
    padding: 18px 20px 16px 20px;
    background: #fafbfc;
    min-width: 220px;
    min-height: 170px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: border-color 0.2s;
    position: relative;
}
.file-uploader-custom .uploader-card:hover {
    border-color: #a0a0a0;
}
.file-uploader-custom .preview-container {
    position: relative;
    display: inline-block;
    margin-top: 5px;
}
.file-uploader-custom .preview-img {
    max-width: 160px;
    max-height: 120px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    border: 1px solid #eee;
    background: #fff;
}
.file-uploader-custom .preview-file {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 8px 12px;
    max-width: 200px;
    word-break: break-all;
}
.file-uploader-custom .preview-file i {
    margin-right: 8px;
    font-size: 18px;
}
.file-uploader-custom .remove-btn {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #fff;
    border: none;
    border-radius: 50%;
    color: #d00;
    font-size: 18px;
    width: 28px;
    height: 28px;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,0.10);
    z-index: 2;
    transition: background 0.2s;
}
.file-uploader-custom .remove-btn:hover {
    background: #ffeaea;
}
.file-uploader-custom .custom-file-label {
    display: inline-block;
    padding: 8px 18px;
    background: #f5f5f5;
    color: #333;
    border-radius: 6px;
    border: 1px solid #d6d6d6;
    cursor: pointer;
    font-size: 15px;
    margin-bottom: 0;
    transition: background 0.2s;
}
.file-uploader-custom .custom-file-label:hover {
    background: #e8e8e8;
}
.file-uploader-custom input[type="file"] {
    display: none;
}
</style>
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    
    $isImage = Str::startsWith($accept ?? '', 'image/');
    $fieldId = $field ?? $name ?? 'file';
    $value = $value ?? '';
    $required = $required ?? false;
@endphp
<div class="file-uploader-custom">
    @if($label)
        <label style="margin-bottom:6px;">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>
    @endif
    <div class="uploader-card">
        <label class="custom-file-label" for="input-{{ $fieldId }}">
            <i class="fa fa-upload"></i> {{ $isImage ? 'اختر صورة' : 'اختر ملف' }}
        </label>
        <input type="file" accept="{{ $accept ?? '*' }}" name="{{ $fieldId }}" id="input-{{ $fieldId }}" onchange="previewFile_{{ $fieldId }}(event)" {{ $required ? 'required' : '' }}>
        <input type="hidden" name="remove_{{ $fieldId }}" id="remove-{{ $fieldId }}" value="0">
        <div class="preview-container" id="preview-container-{{ $fieldId }}" style="{{ $value ? '' : 'display:none;' }}">
            @if($isImage)
                <img id="preview-{{ $fieldId }}" class="preview-img" src="{{ $value }}">
            @else
                <div id="preview-{{ $fieldId }}" class="preview-file">
                    <i class="fa fa-file"></i>
                    <span id="filename-{{ $fieldId }}">{{ basename($value) }}</span>
                </div>
            @endif
            <button type="button" class="remove-btn" onclick="removeFile_{{ $fieldId }}()" title="حذف الملف">&times;</button>
        </div>
    </div>
</div>
<script>
function previewFile_{{ $fieldId }}(event) {
    const input = event.target;
    const preview = document.getElementById('preview-{{ $fieldId }}');
    const container = document.getElementById('preview-container-{{ $fieldId }}');
    const isImage = {{ $isImage ? 'true' : 'false' }};
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
        } else {
            const filenameEl = document.getElementById('filename-{{ $fieldId }}');
            if (filenameEl) filenameEl.textContent = file.name;
            container.style.display = 'inline-block';
        }
    }
}

function removeFile_{{ $fieldId }}() {
    const input = document.getElementById('input-{{ $fieldId }}');
    const preview = document.getElementById('preview-{{ $fieldId }}');
    const container = document.getElementById('preview-container-{{ $fieldId }}');
    const removeInput = document.getElementById('remove-{{ $fieldId }}');
    const isImage = {{ $isImage ? 'true' : 'false' }};
    
    input.value = "";
    if (isImage) {
        preview.src = "";
    } else {
        const filenameEl = document.getElementById('filename-{{ $fieldId }}');
        if (filenameEl) filenameEl.textContent = "";
    }
    container.style.display = 'none';
    if (removeInput) removeInput.value = "1";
}
</script>
