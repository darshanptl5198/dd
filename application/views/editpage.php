<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update Form</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>
    .main {
        /* vertical-align: middle; */
        width: 350px;
        border-radius: 10px;
        border: 2px solid;
        margin-left: auto;
        margin-right: auto;
        padding: 10px;
        background-color: lightskyblue;
    }

    img {
        height: 50px;
        width: 50px;
    }

    .image-container {
        display: flex;
    align-items: self-start;
    flex-direction: column;
        margin: 5px 0;
    }

    .remove-button {
        margin-bottom: 3px;
        /* Space between button and image */
        padding: 3px 5px;
        font-size: 12px;
        background-color: #ff0000;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px;
    }

    .remove-button:hover {
        background-color: #cc0000;
    }
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>


    <form action="<?php echo base_url(); ?>HomeController/update/<?php echo  $id_data->id ?>" method="post" enctype="multipart/form-data">

        <div class="main">
            <h2 style="text-align: center;"> Update Data </h2>

            <div class="mb-2">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $id_data->name ?>">
                <?php echo form_error('name'); ?>
            </div>

            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo $id_data->email  ?>">
                <?php echo form_error('email'); ?>
            </div>

            <div class="mb-2">
                <label for="image" class="form-label">image</label><br>
                <div class='image-container'>
                   
                    <?php
                    
                    foreach ($images as $fileName): ?>
                    <div class="container">

                  
                         <button class="remove-button" data-file="<?php echo $fileName ?>">Delete</button>
 
                        <img src='<?php echo base_url("uploads/images/" . $fileName); ?>' alt='image'>
                        </div>
                        <?php endforeach; ?>
                </div>

                <input type="file" class="form-control" name="image[]" id="image" multiple>
                <br>



                <input type="submit" name="update" value="Update" class="btn btn-primary">
                <a href="<?php echo base_url() ?>HomeController/view" type="submit" name="back" class="btn btn-primary">Back</a>

            </div>
    </form>

</body>

<script>

$(document).ready(function() {
    $('.remove-button').click(function() {
        event.preventDefault();
        var fileNames = $(this).data('file');
        var container = $(this).closest('.container');
        var base_url = '<?php echo base_url(); ?>';
       
    if(confirm('Are You Sure To delete This Image')){
       
    $.ajax({
            url: base_url + 'HomeController/imgdelete',
            type: 'GET',
            data: { 
                fileName: fileNames
            },
            success: function(response) {
                console.log(response);
            
                if(response.trim() === 'yes') {
                    container.remove();
                    alert('successfully to delete the image.')
                    redirect('HomeController/view');

                } 
                else {
                    alert('Failed to delete the image.');
                }
            }
        });
    }


    });
});
</script>


<!-- // if(confirm('Are You Sure To delete This Image')){
    
    // } -->
</html>




