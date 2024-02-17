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
    $gender = clean($_POST["gender"] ?? null);
    $skills = $_POST["skills"] ?? null;
    $select = clean($_POST["select"]) ?? null;

    //file
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];

    $allowed = ['gif', 'jpg', 'png', 'jpeg'];
    $fileExt = explode('.', $fileName);
    $actualFileName = strtolower(end($fileExt));
    $maxFileSize = 1 * 1024 * 1024;


    // validations
    //name
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

    
    // Gender
if(empty($gender)){
    $errGender = "Please select your gender";
} else {
    $crrGender = $gender;
}

// Skills
if(empty($skills)){
    $errSkills = "Please select at least one skill";
} else {
    $crrSkills = $skills;
}

// Division
if(empty($select)){
    $errSelect = "Please select your division";
} else {
    $crrSelect = $select;
}

// File upload
$newFileName = ""; 

if (!empty($fileName)) {
    if (!in_array($actualFileName, $allowed)) {
        $errFile = "Please select a valid file format to upload";
    } elseif ($fileSize > $maxFileSize) {
        $errFile = "File size exceeds the maximum limit of 1 MB";
    } else {
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }

        // Creating a unique file name
        $newFileName = str_shuffle(date('HisAFdYDyl')).uniqid('', true) . '.' . $actualFileName;

        // Uploading the file
        $uploadFile = move_uploaded_file($fileTmpName, 'uploads/' . $newFileName);

        if ($uploadFile) {
            $crrFile = "<span style='color: green' >File uploaded successfully</span>";
        } else {
            echo "Error uploading the file";
        }
    }
} else {
    $errFile = "Please select a file to upload";
}

// City validation (additional validation)
if (empty($city)) {
    $errCity = "City field can't be empty!";
} elseif (strlen($city) < 3) {
    $errCity = "City should be at least 3 characters long!";
} else {
    $crrCity = $city;
}


// Check for overall form submission
if (!isset($errName) && !isset($errCity) && !isset($errGender) && !isset($errSkills) && !isset($errSelect) && !isset($errFile)) {
    $name = $conn->real_escape_string($name);
    $city = $conn->real_escape_string($city);
    
    // Convert skills array to a comma-separated string
    $skillsStr = $skills ? implode(", ", $skills) : null;

    $sql = "INSERT INTO `students` (`name`, `city`, `gender`, `skills`, `division`, `img`) 
            VALUES ('$name', '$city', '$gender', '$skillsStr', '$select', '$newFileName')";

    $result = $conn->query($sql);

    if ($result) {
        $crrStudent = "Student added successfully😍";
        echo "<script>setTimeout(() => location.href='./', 2000)</script>";
    } else {
        $errStudent = "Student not added: " . $conn->error;
    }
}

}

?>

<!-- form -->
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 w-100 mt-3 mx-auto">
            <form class="shadow p-4 rounded" action="" method="post" enctype="multipart/form-data" >
                
                <!-- name -->
                <h5 class="text-center">Add new student</h5>
                <div class="form-floating mb-3">
                    <input type="text" placeholder="Student Name" name="name" class="form-control <?= isset($errName) ? "is-invalid" : null ?> <?= isset($crrName) ? "is-valid" : null; ?>" value="<?= $name ?? null; ?>">
                    <div class="invalid-feedback">
                        <?= $errName ?? null; ?>
                    </div>
                    <label for="">Student Name</label>
                </div>

                <!-- Gender-->
                <div class="form-check-inline border p-3 w-100 rounded <?= isset($errGender) ? "border-danger" : (isset($crrGender) ? "border-success" : null) ?> ">
                        <div class="form-check form-check-inline" >
                            <label class="fw-bold" >Gender :</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="gender" id="male" value="Male" <?= isset($gender) && $gender == "Male" ? "checked" : null ?> >
                            <label class="form-check-label " for="male">Male</label>
                        </div>   
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="gender" id="female" value="Female">
                            <label class="form-check-label" for="female">Female</label>
                        </div> 
                    </div>
                    <div style="font-size: 14px"; class="<?= isset($errGender) ? "text-danger" : (isset($crrGender) ? "text-success" : null )?>">
                            <?= $errGender ?? null ?>
                     </div>
                    
                     <!-- Skills -->
                     <div class="form-check form-check-inline  border rounded p-3 mt-3 w-100 <?= isset($errSkills) ? "border-danger" : (isset($crrSkills) ? "border-success" : null) ?> ">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold" >Skills :</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="HTML" value="HTML" <?= isset($crrSkills) && in_array("HTML", $crrSkills) ? "checked" : null ?> >
                            <label class="form-check-label" for="HTML">HTML</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="CSS" value="CSS" <?= isset($crrSkills) && in_array("CSS", $crrSkills) ? "checked" : null ?> >
                            <label class="form-check-label" for="CSS">CSS</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="JS" value="JS" <?= isset($crrSkills) && in_array("JS", $crrSkills) ? "checked" : null ?> >
                            <label class="form-check-label" for="JS">JS</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="PHP" value="PHP" <?= isset($crrSkills) && in_array("PHP", $crrSkills) ? "checked" : null ?> >
                            <label class="form-check-label" for="PHP">PHP</label>
                        </div>
                     </div>
                     <div style="font-size: 14px"; class="<?= isset($errSkills) ? "text-danger" : (isset($crrSkills) ? "text-success" : null) ?>" >
                        <?= $errSkills ?? null ?>
                    </div>
     

                     <!-- Selection field -->
                    <div class="mt-3 py-3 px-4 border rounded d-flex align-items-center <?= isset($errSelect) ? "border-danger" : (isset($crrSelect) ? "border-success" : null ); ?> ">
                        <label class="fw-bold ms-3 me-3 w-25 " >Division :</label>
                        <select class="form-select w-75"  name="select">
                            <option value="">--Select Your Division--</option>
                            <option value="Dhaka" <?= isset($select) && $select == "Dhaka" ? "selected" : null; ?> >Dhaka</option>
                            <option value="Rajshahi" <?= isset($select) && $select == "Rajshahi" ? "selected" : null; ?> >Rajshahi</option>
                            <option value="Chattogram" <?= isset($select) && $select == "Chattogram" ? "selected" : null; ?> >Chattogram</option>
                            <option value="Khulna" <?= isset($select) && $select == "Khulna" ? "selected" : null; ?> >Khulna</option>
                            <option value="Barisal" <?= isset($select) && $select == "Barisal" ? "selected" : null; ?> >Barisal</option>
                            <option value="Sylhet" <?= isset($select) && $select == "Sylhet" ? "selected" : null; ?> >Sylhet</option>
                            <option value="Rangpur" <?= isset($select) && $select == "Rangpur" ? "selected" : null; ?> >Rangpur</option>
                            <option value="Mymensingh" <?= isset($select) && $select == "Mymensingh" ? "selected" : null; ?> >Mymensingh</option>
                        </select>
                    </div>
                    <div style="font-size: 14px"; class="<?= isset($errSelect) ? "text-danger" : (isset($crrSelect) ? "text-success" : null); ?>" >
                        <?= $errSelect ?? null; ?>
                    </div>

                    <!-- File -->
                    <div class=" d-flex mt-3 py-3 rounded px-3 border <?= isset($errFile) ? " border border-danger" : (isset($crrFile) ? "border border-success" : null) ?> ">
                        <div class="class="w-75" >
                          <input type="file" name="file" id="addImg" >
                        </div>
                        <div class="w-25" >
                            <!-- showing image before upload -->
                            <img style="max-width: 30px; max-height: 30px;" src="" alt="" id="showImg">
                            <script>
                                document.getElementById("addImg").addEventListener("change", function () {
                                    const reader = new FileReader();
                                    reader.onload = function () {
                                        if (reader.readyState == 2) {
                                            document.getElementById("showImg").src = reader.result;
                                        }
                                    };
                                    reader.readAsDataURL(this.files[0]);
                                });
                            </script>
                        </div>
                    </div>
                    <div style="font-size: 14px;" class="text-danger " >
                        <?= $errFile ?? null; ?>
                    </div>

                     <!-- city -->
                     <div class="form-floating mt-3">
                        <input type="text" placeholder="Student City" name="city" class="form-control <?= isset($errCity) ? "is-invalid" : null ?> <?= isset($crrCity) ? "is-valid" : null; ?>" value="<?= $city ?? null; ?>">
                        <div style="font-size: 14px" class="invalid-feedback">
                            <?= $errCity ?? null; ?>
                        </div>
                        <div style="font-size: 14px"; class="valid-feedback">
                            <?= $crrStudent ?? null; ?>
                        </div>
                        <label for="">Student City</label>
                    </div>

                <!-- Submit Button -->
                <input type="submit" class="btn btn-primary mt-3 " name="ast" value="Add Student">
            </form>

         <div class="col-md-2"></div>
    </div>
</div>

<?php
require_once("./footer.php")
?>
