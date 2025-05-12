<style>
.image-uploader-custom {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
}
.image-uploader-custom .uploader-card {
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
.image-uploader-custom .uploader-card:hover {
    border-color: #a0a0a0;
}
.image-uploader-custom .preview-container {
    position: relative;
    display: inline-block;
    margin-top: 5px;
}
.image-uploader-custom .preview-img {
    max-width: 160px;
    max-height: 120px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    border: 1px solid #eee;
    background: #fff;
}
.image-uploader-custom .remove-btn {
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
.image-uploader-custom .remove-btn:hover {
    background: #ffeaea;
}
.image-uploader-custom .custom-file-label {
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
.image-uploader-custom .custom-file-label:hover {
    background: #e8e8e8;
}
.image-uploader-custom input[type="file"] {
    display: none;
}
</style>
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="image-uploader-custom">
    @if($label)
        <label style="margin-bottom:6px;">{{ $label }}</label>
    @endif
    <div class="uploader-card">
        <label class="custom-file-label" for="input-{{ $name }}">
            <i class="fa fa-upload"></i> اختر صورة
        </label>
        <input type="file" accept="image/*" name="{{ $name }}" id="input-{{ $name }}" onchange="previewImage_{{ $name }}(event)">
        <input type="hidden" name="remove_{{ $name }}" id="remove-{{ $name }}" value="0">
        <div class="preview-container" id="preview-container-{{ $name }}" style="{{ $current ? '' : 'display:none;' }}">
            <img id="preview-{{ $name }}" class="preview-img"
                 src="{{ $current ? asset($current) : '' }}">
            <button type="button" class="remove-btn" onclick="removeImage_{{ $name }}()" title="حذف الصورة">&times;</button>
        </div>
    </div>
</div>
<script>
function previewImage_{{ $name }}(event) {
    const input = event.target;
    const preview = document.getElementById('preview-{{ $name }}');
    const container = document.getElementById('preview-container-{{ $name }}');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'inline-block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function removeImage_{{ $name }}() {
    const input = document.getElementById('input-{{ $name }}');
    const preview = document.getElementById('preview-{{ $name }}');
    const container = document.getElementById('preview-container-{{ $name }}');
    const removeInput = document.getElementById('remove-{{ $name }}');
    input.value = "";
    preview.src = "";
    container.style.display = 'none';
    if (removeInput) removeInput.value = "1";
}
</script>
