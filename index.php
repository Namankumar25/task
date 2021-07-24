<?php
include "dbconnect.php";

$imgpath_logo = "";
$imgpath_cover = "";

$sql = "SELECT `profile`,`logo` FROM `business_staff_members` WHERE `id`='20'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['logo'] == "") {
    $imgpath_logo = "upload/user.jpg";
} else {
    $imgpath_logo = "upload/" . $row['logo'];
}

if ($row['profile'] == "") {
    $imgpath_cover = "upload/coverimage.jpg";
} else {
    $imgpath_cover = "upload/" . $row['profile'];
}

?>

<!DOCTYPE html>
<html>

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />

    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/dropzone"></script>
    <script src="https://unpkg.com/cropperjs"></script>

    <style>
        .image_area {
            position: relative;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px;
        }

        .overlay {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.5);
            overflow: hidden;
            height: 0;
            transition: .5s ease;
            width: 100%;
        }

        .image_area:hover .overlay {
            height: 50%;
            cursor: pointer;
        }
    </style>
</head>


<body>

    <div class="row">
        <div class="col-md-4">&nbsp;</div>
        <div class="col-md-4">
            <div class="image_area">

            </div>
        </div>

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-8">
                                    <img src="" id="modal_image" />
                                </div>
                                <div class="col-md-4">
                                    <div class="preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save" class="btn btn-primary">Save</button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-3">
        <div class=" p-md-5 mb-4 text-white rounded bg-dark">
            <div class="px-0">
                <div class="row">

                    <div class="container col-lg-6">
                        <form method="post">
                            <label for="cover_image">
                                <img src="<?php echo $imgpath_cover ?>" id="cover_uploaded_image" class="img-responsive img-circle" style="  width: 558px; height: 168px;" />
                                <input type="file" name="image" class="image" id="cover_image" accept=".png,.jpg" />
                            </label>
                        </form>
                    </div>

                    <div class="container col-lg-6">
                        <form method="post">
                            <label for="profile_image">
                                <img src="<?php echo $imgpath_logo ?>" id="profile_uploaded_image" class="img-responsive img-circle" style="width: 168px;border-radius: 121px;" />
                                <input type="file" name="image" class="image" id="profile_image" accept=".png,.jpg" />
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" id="saveBtn" class="btn btn-primary">Submit</button>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {

        let profile_input = false;
        let cover_input = false;
        let profile_url = "";
        let cover_url = "";

        let imgWidth = 0;
        let imgHeight = 0;

        let imgType = ""

        let profile_set_view = false
        let cover_set_view = false

        let $modal = $('#modal');
        let image = document.getElementById('modal_image');
        let cropper;

        $('#cover_image').change(function(event) {
            cover_input = true
            cover_set_view = true
            imgType = "cover"
            let files = event.target.files;

            let done = function(url) {
                image.src = url
                $modal.modal('show');
            };

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {

                    const file = document.querySelector("#cover_image").files[0];
                    reader.readAsDataURL(file);
                    reader.onload = function(event) {
                        const imgElement = document.createElement("img");
                        imgElement.src = event.target.result;
                        imgElement.onload = function(e) {
                            const canvas = document.createElement("canvas");
                            const MAX_WIDTH = 600;

                            const scaleSize = MAX_WIDTH / e.target.width;
                            canvas.width = MAX_WIDTH;
                            canvas.height = e.target.height * scaleSize;

                            const ctx = canvas.getContext("2d");

                            ctx.drawImage(e.target, 0, 0, canvas.width, canvas.height);

                            const srcEncoded = ctx.canvas.toDataURL(e.target, "image/jpeg");
                            // console.log(srcEncoded);
                            done(srcEncoded);
                        }
                    }

                }
                reader.readAsDataURL(files[0]);
            }
        });

        $('#profile_image').change(function(event) {
            imgType = "profile"
            profile_input = true
            profile_set_view = true
            let files = event.target.files;

            let done = function(url) {
                image.src = url
                $modal.modal('show');
            };

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {

                    const file = document.querySelector("#profile_image").files[0];
                    reader.readAsDataURL(file);
                    reader.onload = function(event) {
                        const imgElement = document.createElement("img");
                        imgElement.src = event.target.result;
                        imgElement.onload = function(e) {
                            const canvas = document.createElement("canvas");
                            const MAX_WIDTH = 600;

                            const scaleSize = MAX_WIDTH / e.target.width;
                            canvas.width = MAX_WIDTH;
                            canvas.height = e.target.height * scaleSize;

                            const ctx = canvas.getContext("2d");

                            ctx.drawImage(e.target, 0, 0, canvas.width, canvas.height);

                            const srcEncoded = ctx.canvas.toDataURL(e.target, "image/jpeg");
                            // console.log(srcEncoded);
                            done(srcEncoded);
                        }
                    }

                }
                reader.readAsDataURL(files[0]);
            }
        });

        $modal.on('shown.bs.modal', function() {
            if (profile_set_view) {
                cropper_view = 1
                imgHeight = 452
                imgWidth = 452
                profile_set_view = false
            }

            if (cover_set_view) {
                cropper_view = 4
                imgHeight = 400
                imgWidth = 800
                cover_set_view = false
            }

            cropper = new Cropper(image, {
                aspectRatio: cropper_view,
                viewMode: 3,
                preview: '.preview'
            });
            
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $('#save').click(function() {
            canvas = cropper.getCroppedCanvas({
                width: imgWidth,
                height: imgHeight
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                let reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    let base64data = reader.result;
                    if (cover_input) {
                        $('#cover_uploaded_image').attr('src', reader.result);
                        cover_input = false
                        cover_url = reader.result
                    }
                    if (profile_input) {
                        // console.log(typeof reader.result);
                        // console.log(reader.result);
                        $('#profile_uploaded_image').attr('src', reader.result);
                        profile_input = false
                        profile_url = reader.result;
                    }
                    $(function() {
                        $('#modal').modal('toggle');
                    });

                };
            });
        });

        $("#saveBtn").click(function() {
            if (profile_url.length <= 0 || cover_url.length <= 0) {
                alert("you have to select both images")
            } else {
                $.ajax({
                    url: 'upload.php',
                    method: 'POST',
                    data: {
                        imageUpdate : true,
                        image_cover: cover_url,
                        image_profile: profile_url
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        $modal.modal('hide');
                        $('#cover_uploaded_image').attr('src', data[1]);
                        $('#profile_uploaded_image').attr('src', data[0]);
                        alert("images updated sucessfully")
                        // if (cover_input) {               
                        //     cover_input = false
                        // }
                        // if (profile_input) {
                        //     profile_input = false
                        // }
                    }
                });
                // console.log(profile_url, cover_url);
            }
        })
    });
</script>