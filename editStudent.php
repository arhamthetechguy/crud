<?php 
require_once ("./header.php");

// selecting data
$id = $_GET['id'] ?? header("location:./");
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
    $gender = clean($_POST["gender"]);
    $skills = $_POST["skills"] ?? [];
    $skillsString = implode(", ", $skills);
    $select = clean($_POST["select"]);

    // cleaning data
    $name = $conn->real_escape_string($name);
    $city = $conn->real_escape_string($city);

    // updating sql
    $result = $conn->query("UPDATE `students` SET `name` = '$name', `city` = '$city', `gender` = '$gender', `skills` = '$skillsString', `division` = '$select' WHERE `id` = $id");
    if ($result) {
        $upStudent = "Student updated Successfully😊";
        echo "<script>setTimeout(() => location.href='./', 2000)</script>";
    } else {
        echo "Student not updated";
    }

    // file validation and upload
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];

        $allowed = ['gif', 'jpg', 'png', 'jpeg'];
        $fileExt = explode('.', $fileName);
        $actualFileName = strtolower(end($fileExt));
        $maxFileSize = 1 * 1024 * 1024;

        if (in_array($actualFileName, $allowed)) {
            if ($fileSize < $maxFileSize) {
                $picNameNew = uniqid('', true) . "." . $actualFileName;
                $picDestination = "./uploads/" . $picNameNew;
                $move = move_uploaded_file($fileTmpName, $picDestination);

                if ($move) {
                    $result = $conn->query("UPDATE `students` SET `img`='$picNameNew' WHERE `id`= $id");

                    if ($result) {
                        $delFile = unlink("./uploads/" . $row->img);

                        if ($delFile) {
                            $upStudent = "Student Updated Successfully";
                            echo "<script>setTimeout(() => location.href='./', 2000)</script>";
                        } else {
                            echo "Student Not Updated";
                        }
                    } else {
                        echo "Student Not Updated";
                    }
                } else {
                    echo "File Not Uploaded";
                }
            } else {
                echo "Your file is too big";
            }
        } else {
            echo "You cannot upload files of this type";
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 w-100 mt-3 mb-5 mx-auto">
            <form class="shadow p-4 rounded" action="" method="post" enctype="multipart/form-data" >

                <!-- name -->
                <h5 class="text-center" >Edit Student</h5>
                <div class="form-floating mb-3">
                    <input type="text" placeholder="Student Name" name="name" class="form-control" value="<?= $row->name ?>" >
                    <div class="invalid-feedback">
                        <?= $errName ?? null; ?>
                    </div>
                    <label for="">Student Name</label>
                </div>

                    <!-- Gender-->
                    <div class="form-check-inline border p-3 w-100 rounded">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold">Gender :</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="gender" id="male" value="Male" <?= $row->gender == "Male" ? "checked" : null ?>>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" class="form-check-input" name="gender" id="female" value="Female" <?= $row->gender == "Female" ? "checked" : null ?>>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>

                    
                     <!-- Skills -->
                     <div class="form-check form-check-inline  border rounded p-3 mt-3 w-100 ">
                        <div class="form-check form-check-inline">
                            <label class="fw-bold" >Skills :</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="HTML" value="HTML" <?= isset($skills) && in_array("HTML", $skills) ? "checked" : (!isset($skills) && in_array("HTML", explode(", ", $row->skills)) ? "checked" : null ) ?> >
                            <label class="form-check-label" for="HTML">HTML</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="CSS" value="CSS" <?= isset($skills) && in_array("CSS", $skills) ? "checked" : (!isset($skills) && in_array("CSS", explode(", ", $row->skills)) ? "checked" : null ) ?> >
                            <label class="form-check-label" for="CSS">CSS</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="JS" value="JS" <?= isset($skills) && in_array("JS", $skills) ? "checked" : (!isset($skills) && in_array("JS", explode(", ", $row->skills)) ? "checked" : null ) ?> >
                            <label class="form-check-label" for="JS">JS</label>
                        </div>
                        <div class="form-check form-check-inline ">
                            <input class="form-check-input " type="checkbox" name="skills[]" id="PHP" value="PHP" <?= isset($skills) && in_array("PHP", $skills) ? "checked" : (!isset($skills) && in_array("PHP", explode(", ", $row->skills)) ? "checked" : null ) ?> >
                            <label class="form-check-label" for="PHP">PHP</label>
                        </div>
                     </div>
     

                     <!-- Selection field -->
                    <div class="mt-3 py-3 px-4 border rounded d-flex align-items-center ">
                        <label class="fw-bold ms-3 me-3 w-25 " >Division :</label>
                        <select class="form-select w-75"  name="select">
                            <option value="">--Select Your Division--</option>
                            <option value="Dhaka" <?= $row->division == "Dhaka" ? "selected" : null ?> >Dhaka</option>
                            <option value="Rajshahi" <?= $row->division == "Rajshahi" ? "selected" : null ?> >Rajshahi</option>
                            <option value="Chattogram" <?= $row->division == "Chattogram" ? "selected" : null ?>>Chattogram</option>
                            <option value="Khulna" <?= $row->division == "Khulna" ? "selected" : null ?> >Khulna</option>
                            <option value="Barisal" <?= $row->division == "Barisal" ? "selected" : null ?> >Barisal</option>
                            <option value="Sylhet" <?= $row->division == "Sylhet" ? "selected" : null ?> >Sylhet</option>
                            <option value="Rangpur" <?= $row->division == "Rangpur" ? "selected" : null ?> >Rangpur</option>
                            <option value="Mymensingh" <?= $row->division == "Mymensingh" ? "selected" : null ?> >Mymensingh</option>
                        </select>
                    </div>

                    
                    <!-- File -->
                <div class=" d-flex mt-3 py-3 rounded px-3 border <?= isset($errFile) ? " border border-danger" : (isset($crrFile) ? "border border-success" : null) ?> ">
                    <div class="class="w-75" >
                    <input type="file" name="file" id="editImg" >
                    </div>
                        <div class="w-25" >

                            <!-- showing image before upload -->
                            <img style="max-width: 30px; max-height: 30px;" src="./uploads/<?= $row->img ?>" alt="" id="changeImg">
                            <script>
                                // JavaScript code to keep the reuploaded file selected
                                document.addEventListener('DOMContentLoaded', function () {
                                    const editImg = document.getElementById('editImg');
                                    const changeImg = document.getElementById('changeImg');

                                    editImg.addEventListener('change', () => {
                                        const [file] = editImg.files;
                                        if (file) {
                                            changeImg.src = URL.createObjectURL(file);
                                        }
                                    });

                                    // Check if there is a previously uploaded image
                                    const uploadedImg = '<?= $row->img ?>';
                                    if (uploadedImg) {
                                        changeImg.src = './uploads/' + uploadedImg;
                                        editImg.value = '';
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    
                    <!-- city -->
                    <div class="form-floating mt-3">
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