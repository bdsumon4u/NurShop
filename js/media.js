$(document).ready(function(){
    $(document).on({
        'show.bs.modal': function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        },
        'hidden.bs.modal': function() {
            if ($('.modal:visible').length > 0) {
                // restore the modal-open class to the body element, so that scrolling works
                // properly after de-stacking a modal.
                setTimeout(function() {
                    $(document.body).addClass('modal-open');
                }, 0);
            }
        }
    }, '.modal');
    $(document).find('body').append('<div id="media" class="modal- fade"  data-backdrop="static" data-keyboard="false" role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">File Manager</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button></div><div class="modal-body" style="min-height: 600px" ><iframe src="" style="width: 100%;min-height: 600px" frameborder="0"></iframe></div><div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm waves-effect" data-dismiss="modal">Close</button></div></div></div></div>');
    $(document).on("click", ".image-picker", function (e) {
        console.log('asdf');
        var modal = $('#media');
        var t = this;
        var t =  $('.image-picker'),
            n = e.currentTarget.dataset.inputName,
            i = e.currentTarget.hasAttribute("data-multiple");
        modal.modal('show');
        modal.find('iframe').attr('src', siteURL+'/admin/media/iframe?multiple='+i);
        $('iframe').attr('data-multiple',i);
    });
    $(document).on("click", ".remove-image", function (e) {
        e.preventDefault();
        var html = ('<div class="image-holder placeholder"><i class="fas fa-folder"></i></div>');
        var i = e.currentTarget.hasAttribute("data-multiple");
        if(i){
            if($('.image-list > div').length < 2){
                $(this).closest('.image-holder-wrapper').append(html);
                $(this).closest('.image-holder').remove();
            }else{
                $(this).closest('.image-holder').remove();
            }
        }else{
            $(this).closest('.image-holder-wrapper').append(html);
            $(this).closest('.image-holder').remove();
        }
    });

    $('iframe').on("load", function (e) {
        $('iframe').contents().find(".table").on('click', '.btn-select', function (e) {
            e.preventDefault();
            var multiple= $('iframe').attr('data-multiple');
            if (multiple === 'true') {
                var html = ('<div class="image-holder image-'+e.currentTarget.dataset.id+'"><img src="' + e.currentTarget.dataset.path + '"><button type="button"  data-multiple=""  class="btn btn-danger btn-xs waves-effect waves-light remove-image float-right"><i class="fas fa-times"></i></button><input type="hidden" name="imageID[]" value="' + e.currentTarget.dataset.id + '"></div>');
                $('.image-list').find(".image-holder.placeholder").remove().append(html);
                $('.image-list').append(html);
                toastr.success('Image Added Successful');
            } else {
                var html = ('<div class="image-holder image-'+e.currentTarget.dataset.id+'"><img src="' + e.currentTarget.dataset.path + '"><button type="button" class="btn btn-danger btn-xs waves-effect waves-light remove-image float-right"><i class="fas fa-times"></i></button><input type="hidden" name="imageID" value="' + e.currentTarget.dataset.src + '"></div>');
                $('.single-image').find(".image-holder.placeholder").remove();
                $('.single-image').empty().append(html);
                toastr.success('Image Added Successful');
                $('#media').modal('hide');
            }
        });
    });
    var productImage = new Array;
    var i = 0;
    $("#dropzone").dropzone({
        addRemoveLinks: true,
        maxFiles: 10, //change limit as per your requirements
        dictMaxFilesExceeded: "Maximum upload limit reached",
        acceptedFiles: "image/*",
        dictInvalidFileType: "upload only JPG/PNG",
        init: function () {
            $(this.element).addClass("dropzone");
            this.on("success", function (file, response) {
                if(response.status == 'success'){
                    productImage[i] = {
                        "serverFileName": response.url,
                        "fileName": response.url,
                        "fileId": i
                    };
                    i += 1;
                    toastr.success('Image Uploade Successfull');
                }else{
                    toastr.error('Image Uploade Failed !');
                }
            });
            this.on("removedfile", function (file) {
                var rmvFile = "";
                for (var f = 0; f < productImage.length; f++) {
                    if (productImage[f].fileName == file.name) {
                        rmvFile = productImage[f].serverFileName;
                    }
                }
                if (rmvFile) {
                    $.ajax({
                        url: path, //your php file path to remove specified image
                        type: "POST",
                        data: {
                            filenamenew: rmvFile,
                            type: 'delete',
                        },
                    });
                }
            });
            this.on('drop', function(file) {
                console.log('File',file)
            });
        }
    });
    var token = $("input[name='_token']").val();


});
