<?php 
require_once ("./header.php");

// selecting data
$id = $_GET['id'] ?? header("location./");
$id = $conn->real_escape_string($id);
$sql = "SELECT * FROM `students` WHERE `id` = '$id'";
$result = $conn->query($sql);
$result->num_rows == 0 ? header("location:./") : null;
$row = $result->fetch_object();

// clean Function
function clean($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

// form submitting
if (isset($_POST["ust"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean($_POST["name"]);
    $city = clean($_POST["city"]);

    // cleaning data
    $name = $conn->real_escape_string($name);
    $city = $conn->real_escape_string($city);

    // updating sql
    $result = $conn->query("UPDATE `students` SET `name` = '$name', `city` = '$city' WHERE `id` = $id");
    if ($result) {
        $upStudent = "Student updated Successfully😊";
        echo "<script>setTimeout(() => location.href='./', 2000)</script>";
    } else {
        echo "Student not updated";
    }
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 w-100 mt-3 mx-auto">
            <form class="shadow p-4 rounded" action="" method="post">

                <!-- name -->
                <h5 class="text-center" >Edit Student</h5>
                <div class="form-floating mb-3">
                    <input type="text" placeholder="Student Name" name="name" class="form-control" value="<?= $row->name ?>" >
                    <div class="invalid-feedback">
                        <?= $errName ?? null; ?>
                    </div>
                    <label for="">Student Name</label>
                </div>

                <!-- city -->
                <div class="form-floating">
                    <input type="text" placeholder="Student City" name="city" class="form-control" value="<?= $row->city ?>">
                    <label for="">Student City</label>
                    <div class="text-success">
                        <?= $upStudent ?? null; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <input type="submit" class="btn btn-primary mt-3 " name="ust" value="Update Student">
            </form>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>

<?php 
require_once ("./footer.php");
?>