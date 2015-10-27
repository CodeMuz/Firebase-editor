$.cloudinary.config({cloud_name: 'dmeekrvrr', api_key: '863893379355596'});

$('.upload_form').append($.cloudinary.unsigned_upload_tag("s7oovao8", {cloud_name: 'dmeekrvrr'}));

$('.cloudinary_fileupload').unsigned_cloudinary_upload("s7oovao8",
    {cloud_name: 'dmeekrvrr', tags: 'browser_uploads'},
    {multiple: true}
);
$('.cloudinary_fileupload').bind('fileuploadstart', function (e) {
    $('.preview').html('Upload started...');
}).bind('cloudinarydone', function (e, data) {
    $('.preview').append(
        $.cloudinary.image(data.result.public_id,
            {
                format: 'jpg', width: 150, height: 100,
                crop: 'thumb', gravity: 'face', effect: 'saturation:50'
            })
    );
    return true;
});

