<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
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
    background-color: lightsteelblue;
  }
</style>

<body>
  <!-- <?php echo validation_errors(); ?> -->

  <div>
    <a href="view" type="button" class="btn btn-primary">view Data</a>
  </div>
  <form action="<?php echo base_url(); ?>HomeController/validate" method="post" enctype="multipart/form-data" >

    <div class="main">
      <h2 style="text-align: center;"> User Form</h2>

      <div class="mb-2">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name'); ?>">
        <div style="color: red;"><?php echo form_error('name'); ?></div>
      </div>

      <div class="mb-2">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
        <div style="color: red;"><?php echo form_error('email'); ?></div>
      </div>

      <div class="mb-2">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>">
        <div style="color: red;"><?php echo form_error('password'); ?></div>

      </div>

      <div class="mb-2">
        <label for="image" class="form-label">Upload Image</label>
        <input class="form-control" type="file" id="image" name="image" value="<?php echo set_value('password'); ?>">
        <div style="color: red;">
        <?php
       
        if (isset($upload_error) && !empty($upload_error)) {
            echo $upload_error;
        }
        ?>
        <?php echo form_error('image'); ?>
    </div>

      </div>

      <div class="mb-2 form-check">
        <input type="checkbox" name="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
        <div style="color: red;"><?php echo form_error('checkbox'); ?></div>

      </div>

      <button type="submit" class="btn btn-primary">Submit</button>

    </div>
  </form>

</body>

</html>