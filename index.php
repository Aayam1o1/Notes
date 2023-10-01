<?php
//inset query
// INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'Buy books', 'Please buy books', current_timestamp());

//for alert mesage
$insert = false;
$update = false;
$delete = false;
$notinsert = false;

//connecting to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "notes";

// Create connection for database
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


//issest used for checking error and data aako xa ki xaina check garxa
if(isset($_GET['delete'])){
  $sno =  $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
}

//update record
//here if ma gaye update and else ma gaye insert
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(isset($_POST['snoEdit'])){
    // variables declare
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];

    // Update query 
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);

    // Check if the update was successful
    if($result){
      $update = true;
    } else {
      echo "We have updated the record successfully";
    }
  } 
  else {
    // Insert starts here
    // Declare variables for insert query
    $title = $_POST["title"];
    $description = $_POST["description"];

    // Check if title and description are not empty
    if (!empty($title) && !empty($description)) {
      // SQL query for insert
      $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
      $result = mysqli_query($conn, $sql);

      // Check if the insert was successful
      if ($result){
        $insert = true;
      } 
      else {
        echo "The record is not successfully recorded! <br>" . mysqli_error($conn);
      }
    }
    else 
    {
      $notinsert = true;
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <title>Notes - Notes taking made easy!</title>
   
</head>
<body>
<!-- Edit modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
  Edit Modal
</button> -->

<!-- Edit Modal  -->
<!-- pop up message aauxa eidt thichda -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="/basic/phpcrud/notes/index.php" method="post">  
        <input type="hidden" name="snoEdit" id="snoEdit" >
        <div class="form-group">
          <label for="title">Note Title</label>
          <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp" placeholder="Enter new Note">
          
        </div>
        
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Note Description</label>
            <textarea class="form-control" id="descriptionEdit" rows="3" name="descriptionEdit" placeholder="Enter Note Description"></textarea>
          </div>
        <button type="submit" class="btn btn-primary">Update Note</button>
      </form>
      </div>
      
    </div>
  </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this note?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a id="confirmDelete" class="btn btn-danger" href="#">Delete</a>
      </div>
    </div>
  </div>
</div>
<!-- nav section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">iNotes</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>


<?php
//messgae  after inserting notes
if($insert){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!!</strong> You notes has been added sucessfully.
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}

?>
<?php
//message after delete
if($delete){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!!</strong> You notes has been deleted sucessfully.
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}

?>
<?php
//messgae after updates
if($update){
  echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>Success!!</strong> You notes has been updated sucessfully.
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}
?>
<?php
//messgae after updates
if($notinsert){
  echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
  <strong>Warning!! </strong>Title and Description field must not be empty.
  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>
</div>";
}
?>

<!-- note haru halne container -->
<div class="container my-5">
    <h2>Add a Note</h2>
    <form action="/basic/phpcrud/notes/index.php" method="post">  
        <div class="form-group">
          <label for="title">Note Title</label>
          <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp" placeholder="Enter a note">
          
        </div>
        
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Note Description</label>
            <textarea class="form-control" id="description" rows="3" name="description" placeholder="Enter Note Description"></textarea>
          </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
      </form>

      <div class="container mt-5" >
        <table class="table" id= "myTable">
          <thead>
            <tr>
              <th scope="col">SNo</th>
              <th scope="col">Title</th>
              <th scope="col">Description</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            //display query
              $sql = "SELECT * FROM `notes` ORDER BY `sno` DESC";
              $result = mysqli_query($conn, $sql);
              $sno = 0;
              while ($row = mysqli_fetch_array($result))
              {
                $sno =$sno + 1;
                echo "<tr>
                <th scope='row'>". $sno . "</th>
                <td>". $row['title'] . "</td>
                <td>". $row['description'] . "</td>
                <td> <button class='edit btn btn-sm btn-primary ' id=".$row['sno'].">Edit</button> 
                <button class='delete btn btn-sm btn-danger' id=d".$row['sno'].">Delete</button>
              </tr>";
              }
            ?> 
           
          </tbody>
        </table>
      </div>
</div>
<hr>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
      let table = new DataTable('#myTable');
    </script>
     <script>
      // Script for modal edit, works according to the 
      edits = document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener('click',(e)=>{
          console.log("edit ", );
          tr = e.target.parentNode.parentNode;   //parent wala linxa table am tr tr garera mathi
          title = tr.getElementsByTagName('td')[0].innerText;
          description = tr.getElementsByTagName('td')[1].innerText; //index ma rahe xa tesko anusar tanxa data for edit
          console.log(description);
          console.log(title);
          titleEdit.value = title;
          descriptionEdit.value = description;
          snoEdit.value = e.target.id;
          console.log(e.target.id); //action listener
          $('#editModal').modal('toggle');
      })
    });

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
        element.addEventListener('click', (e) => {
        console.log("delete", e);
        sno = e.target.id.substr(1);
        // Set the href of the confirmation modal's delete button
        document.getElementById('confirmDelete').setAttribute('href', `/basic/phpcrud/notes/index.php?delete=${sno}`);
        // Open the confirmation modal
        $('#deleteConfirmationModal').modal('show');
      });
    });
    </script>
  </body>
</html>