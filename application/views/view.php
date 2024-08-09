<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Data</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>
  .main1 {
    width: 800px;
    margin-left: auto;
    margin-right: auto;
  }

  img {
    width: 50px;
    height: 50px;
  }

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
  <p></p>
  <div class="table-responsive main1">
    <div class="clear-fix">

      <a href="<?php echo base_url('HomeController/home'); ?>" class="btn btn-primary">register</a>
      <h2 style="float: left;"> Display Data</h2>

      <form action="<?php echo base_url('HomeController/view'); ?>" method="post">
        <input type="search" name="search" value="<?php echo set_value('search', $this->input->get('search')); ?>" placeholder="Search...">
        <button type="submit" name="submit">Search</button><br><br>
      </form>


    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <!-- <th scope="col">Image</th> -->
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)) : ?>
          <tr>
            <td colspan="5" style="text-align: center;">No records found</td>
          </tr>
        <?php else : ?>


          <?php $i = $index;
          foreach ($users  as $user) : ?>

            <tr>
              <td> <?php echo $i++;  ?></td>
              <td> <?php echo $user->name;  ?></td>
              <td> <?php echo $user->email;  ?></td>
              <!-- <td> -->

             
                        <?php foreach ($user->images as $image): ?>
                          
                            <!-- <img src="<?php // echo base_url("uploads/images/$image"); ?>" alt="Image" > -->
                        <?php endforeach; ?>
            

              <!-- </td> -->
              <td>
                <a href="<?php echo base_url() ?>HomeController/edit/<?php echo $user->id; ?>" class="btn btn-primary">Edit</a>
                <a href="<?php echo base_url() ?>HomeController/delete/<?php echo $user->id; ?>" onclick="return deletefun()" class="btn btn-danger">DELETE</a>
                <button class="view-btn btn btn-primary" data-id="<?php echo $user->id; ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">View</button>

              </td>
            </tr>
          <?php endforeach; ?>

        <?php endif; ?>

      </tbody>
    </table>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">User Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content will be loaded here via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    <div class="page" style="text-align: center; ">

      <?php echo $links; ?>
    </div>
</body>

</html>



<script>
  function deletefun() {
    return confirm('Are You Sure To DELETE This Image');
  }
 
    
  $(document).ready(function() {
    $('.view-btn').click(function() {

        var userId = $(this).data('id'); // Extract the user ID
// console.log(userId);
        var base_url = '<?php echo base_url(); ?>';
        // console.log(base_url);

        $.ajax({
          
            url: base_url + 'HomeController/viewdata', // URL to your CodeIgniter controller method
            type: 'GET',
            data: {
                id: userId
         
            },
            success: function(response) {
              // console.log('response:', response);
                var data = JSON.parse(response);
                var html =
                    '<div><label>Name:</label> ' + data.user.name + '</div>' +
                    '<div><label>Email:</label> ' + data.user.email + '</div>' +
                    '<div><label>Pictures:</label></div>';

                if (data.images.length > 0) {
                    data.images.forEach(function(image) {
                        html += '<img src="' + base_url + 'uploads/images/' + image + '" alt="image" style="max-width: 100%; margin-top: 10px;">';
                    });
                } else {
                    html += '<p>No images available.</p>';
                }              
                $('.modal-body').html(html); 
            },
          
        });
    });
});



</script>