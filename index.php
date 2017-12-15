<?php 

require "vendor/autoload.php";

$url = "http://localhost/project/";

$client = new MongoDB\Client();
$db = $client->cars;

// Inserting Data
if (isset($_POST['send'])) {
   $data = [
        "name"   => $_POST['name'],
        "mirror" => $_POST['mirror'],
        "wheels" => $_POST['wheels'],
        "brake"  => $_POST['brake']
    ];

    $implode = implode("", $data);
    // Check Data not NULL
    if (!empty($implode)) {
        $col = $db->spare_parts->insertOne($data);
    } else {
        echo "Data is NULL";
    }
}

// Deleting Data By ID
if (isset($_GET['delete'])) {
    $id = new MongoDB\BSON\ObjectID($_GET['id']);
    $db->spare_parts->deleteOne(["_id" => $id]);
}

// Updating Data By ID
    if (isset($_GET['update'])) {
        $resultUp = $db->spare_parts->find([
            '_id' => new MongoDB\BSON\ObjectID($_GET['id'])
        ]);

        foreach ($resultUp as $value) {

            ?>
            <form method="post">
                <input type="text" name="up_name" value="<?= $value['name'] ?>"> 
                <input type="text" name="up_mirror" value="<?= $value['mirror'] ?>">
                <input type="text" name="up_wheels" value="<?= $value['wheels'] ?>">
                <input type="text" name="up_brake" value="<?= $value['brake'] ?>">
                
                <input type="hidden" name="up_id" value="<?= $value['_id'] ?>">
                <input type="submit" name="update" value="Update">
            </form>
            <?php
        }   
    }

// Insert Data Updating 
    if (isset($_POST['update'])) {
        
     $id = new MongoDB\BSON\ObjectID($_POST['up_id']);
        $data = [
            "name"   => $_POST['up_name'],
            "mirror" => $_POST['up_mirror'],
            "wheels" => $_POST['up_wheels'],
            "brake"  => $_POST['up_brake']
        ];

        $col = $db->spare_parts->updateOne(
            ['_id'  => $id], 
            ['$set' => $data]
        );
        header("location: {$url}");
    }
?>




<!DOCTYPE html>
<html>
<head>
    <title>Simple CRUD MongoDB</title>
</head>
<body>

    <form method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="mirror" placeholder="Mirror">
        <input type="text" name="wheels" placeholder="Wheels"> 
        <input type="text" name="brake" placeholder="Brake">
        <input type="submit" name="send" value="Send">
    </form>

    <table style="border: 2px solid #000">
        <tr>
            <th>No</th>
            <th style="border: 2px solid #000">Name</th>
            <th>Mirror</th>
            <th>Wheels</th>
            <th>Brake</th>
        </tr>
        <?php
            $results = $db->spare_parts->find();
            $no = 1;
            foreach ($results as $result) {
                ?>
                <tr >
                    <td><?= $no++?></td>
                    <td style="border: 0.5px solid #000"> <?= $result['name'] ?></td>
                    <td><?= $result['mirror'] ?></td>
                    <td><?= $result['wheels'] ?></td>
                    <td><?= $result['brake'] ?></td>
                    <td>
                        <a href="?delete&id=<?= $result['_id'] ?>">delete</a>
                        <a href="?update&id=<?= $result['_id'] ?>">edit</a>
                    </td>
                </tr>
                <?php
            }
        ?>
    </table>

</body>
</html>


