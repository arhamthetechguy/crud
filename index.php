<?php 
require_once ("./header.php");

$sql = "SELECT * FROM `students`";
$result = $conn->query($sql);

if ($result->num_rows == 0){
    echo "<div class='container mt-4 text-danger h5 text-center '>No students found!😔</div>";
} else{
?>
<div class='container mt-2 text-center '>
<table class="table table-success table-striped" >
    <tr>
        <th>SN</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Skills</th>
        <th>Division</th>
        <th>City</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php
    $sn = 1;
    while ($row = $result->fetch_object()) {
    ?>

    <tr>
        <td><?= $sn++ ?></td>
        <td><?= $row->name ?></td>
        <td><?= $row->gender ?></td>
        <td><?= $row->skills ?></td>
        <td><?= $row->division ?></td>
        <td><?= $row->city ?></td>
        <td>
            <img src="./uploads/<?= $row->img ?>" alt="" style="max-width: 30px; max-height: 30px;" >
        </td>
        <td>
            <a href="./editStudent.php?id=<?= $row->id ?>"><button class="btn btn-primary btn-sm " >Edit</button></a>
            <a href="./deleteStudent.php?id=<?= $row->id ?>"><button class="btn btn-outline-danger btn-sm" >Delete</button></a>
        </td>
    </tr>

    <?php } ?>
</table>
</div>

<?php
}
?>

<?php 
require_once ("./footer.php");
?>