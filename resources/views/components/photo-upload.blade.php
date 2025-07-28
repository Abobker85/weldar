<div class="photo-upload" onclick="document.getElementById('welder_photo').click()">
    <div id="photo-preview" style="font-size: 8px; color: #666;">
        Click to upload<br>welder photo
    </div>
    <input type="file" id="welder_photo" name="photo" accept="image/*"
        onchange="previewPhoto(this)">
</div>
