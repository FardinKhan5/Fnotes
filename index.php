<?php
$server = "localhost";
$user = "root";
$password = "";
$dbname = "notes";
$conn = new mysqli($server, $user, $password, $dbname);
$isInserted = false;
$isDeleted = false;
$isUpdated = false;
if ($conn->connect_error) {
    die("" . $conn->connect_error);
}
if (isset($_GET["delete"])) {
    $dno = $_GET["delete"];
    $sql = "DELETE FROM notes WHERE `srno` = $dno";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $isDeleted = true;
    } else {
        echo "failed:" . mysqli_connect_error();
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["msno"])) {
        $uno = $_POST["msno"];
        $utitle = $_POST["mtitle"];
        $udescription = $_POST["mdescription"];
        $sql = "UPDATE `notes` SET `title` = '$utitle', `description` = '$udescription' WHERE `notes`.`srno` = $uno;";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $isUpdated = true;
        } else {
            echo "failed:" . mysqli_connect_error();
        }
    }else{
        $title = $_POST["title"];
        $description = $_POST["description"];
        $sql = "INSERT INTO `notes` (`srno`, `title`, `description`, `tstamp`) VALUES (NULL, '$title', '$description', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $isInserted = true;
        } else {
            echo "failed:" . mysqli_connect_error();
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
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Fnotes</title>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/Fnotes/index.php" method="POST">
                        <input type="hidden" name="msno" id="msno">
                        <div class="form-group">
                            <label for="mtitle">Title</label>
                            <input type="text" class="form-control" id="mtitle" name="mtitle"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="mdescription">Description</label>
                            <textarea class="form-control" id="mdescription" name="mdescription" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" id="madd">Add Note</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Fnotes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href=".">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#myTable">Notes</a>
            </li>
          </ul>
        </div>
      </nav>

    <?php
    if ($isInserted) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success</strong> Note has been added successfully
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }
    if ($isDeleted) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success</strong> Note has been Deleted successfully
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }
    if ($isUpdated) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Success</strong> Note has been Updated successfully
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>";
    }
    ?>
    <div class=" m-3">
        <div class="d-flex justify-content-center">
            <p class="h1">Add a Note</p>
        </div>

        <div class="d-flex justify-content-center">
            <form action="/Fnotes/index.php" method="POST" class="w-50">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary" id="add">Add Note</button>
                </div>

            </form>
        </div>

    </div>

    <div class="container my-5">
        <table class="table bg-dark text-light" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sr.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM notes";
                $result = mysqli_query($conn, $sql);
                $sno = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                    <th scope='row'>" . $sno . "</th>
                    <td>" . $row['title'] . " </td>
                    <td>" . $row["description"] . "</td>
                    <td><button class='btn btn-sm btn-primary mx-2 update' id=" . $row['srno'] . ">Update</button><button class='btn btn-sm btn-danger delete' id=d" . $row['srno'] . ">Delete</button></td>
                </tr>";
                    $sno++;
                }

                ?>

            </tbody>
        </table>
    </div>
    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script>
        let table = new DataTable('#myTable');
        let deleteBtns = document.getElementsByClassName("delete")
        for (let i = 0; i < deleteBtns.length; i++) {
            deleteBtns[i].addEventListener("click", (e) => {
                let c = confirm("Are you sure?")
                if (c) {
                    let dId = e.target.id.substr(1,)
                    window.location = `/Fnotes/index.php?delete=${dId}`
                }
            })
        }
        let updateBtns = document.getElementsByClassName("update")
        for (let i = 0; i < deleteBtns.length; i++) {
            updateBtns[i].addEventListener("click", (e) => {

                let tInput = document.getElementById("mtitle")
                let dInput = document.getElementById("mdescription")
                let tr = e.target.parentNode.parentNode
                tInput.value = tr.getElementsByTagName("td")[0].innerText
                dInput.value = tr.getElementsByTagName("td")[1].innerText
                let hiddenInput = document.getElementById("msno")
                hiddenInput.value = e.target.id
                $('#myModal').modal('toggle')
            })
        }
        

    </script>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
</body>

</html>