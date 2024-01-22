<?php
require_once("./header.php")
?>

<?php

function clean($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}

if (isset($_POST["ast"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean($_POST["name"]);
    $city = clean($_POST["city"]);

    // validation
    if (empty($name)) {
        $errName = "Name field can't be empty!";
    } elseif (!preg_match("/^[A-Za-z. ]*$/", $name)) {
        $errName = "Name can only contain letters and spaces!";
    } elseif (strlen($name) < 3) {
        $errName = "Name should be at least 3 characters long!";
    } else {
        $crrName = $name;
    }

    // city
    if (empty($city)) {
        $errCity = "City field can't be empty!";
    } elseif (strlen($city) < 3) {
        $errCity = "City should be at least 3 characters long!";
    } else {
        $crrCity = $city;
    }


    // Timeout condition
    if (!isset($errName) && !isset($errCity) && ($name !== "" || $city !== "")) {
        $name = $conn->real_escape_string($name);
        $city = $conn->real_escape_string($city);
        $sql = "INSERT INTO `students` (`name`, `city`) VALUES ('$name', '$city')";
        $result = $conn->query($sql);

        if ($result) {
            $crrStudent = "Student added successfully😍";
            echo "<script>setTimeout(() => location.href='./', 2000)</script>";
        } else {
            $errStudent = "Student not added";
        }
    }
}

?>

<!-- form -->
<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 w-100 mt-3 mx-auto">
            <form class="shadow p-4 rounded" action="" method="post">
                
                <!-- name -->
                <h5 class="text-center">Add new student</h5>
                <div class="form-floating mb-3">
                    <input type="text" placeholder="Student Name" name="name" class="form-control <?= isset($errName) ? "is-invalid" : null ?> <?= isset($crrName) ? "is-valid" : null; ?>" value="<?= $name ?? null; ?>">
                    <div class="invalid-feedback">
                        <?= $errName ?? null; ?>
                    </div>
                    <label for="">Student Name</label>
                </div>

                <!-- city -->
                <div class="form-floating">
                    <input type="text" placeholder="Student City" name="city" class="form-control <?= isset($errCity) ? "is-invalid" : null ?> <?= isset($crrCity) ? "is-valid" : null; ?>" value="<?= $city ?? null; ?>" value="test" >
                    <div class="invalid-feedback">
                        <?= $errCity ?? null; ?>
                    </div>
                    <div class="valid-feedback">
                        <?= $crrStudent ?? null; ?>
                    </div>
                    <label for="">Student City</label>
                </div>

                <!-- Submit Button -->
                <input type="submit" class="btn btn-primary mt-3 " name="ast" value="Add Student">
            </form>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>

<?php
require_once("./footer.php")
?>
