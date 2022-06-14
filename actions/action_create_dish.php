<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: ../pages/profile.php'));
    

    require_once(__DIR__ . '/../database/connection.db.php');
    
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');
    require_once(__DIR__ . '/../database/category.class.php');
    require_once(__DIR__ . '/../database/dish.class.php');


    require_once(__DIR__ . '/../templates/forms.tpl.php');

    $db = getDatabaseConnection();

    // Insert image data into database
    $stmt = $db->prepare("INSERT INTO Image VALUES(NULL, ?)");
    $stmt->execute(array($_POST['image_title']));

    // Get image ID
    $id = $db->lastInsertId();
    $image_id = $id;

    // Create folders if they don't exist

    if (!is_dir('../images')) mkdir('../images');
    if (!is_dir('../images/dishes')) mkdir('../images/dishes');
    if (!is_dir('../images/dishes/originals')) mkdir('../images/dishes/originals');
    if (!is_dir('../images/dishes/thumbs_small')) mkdir('../images/dishes/thumbs_small');
    if (!is_dir('../images/dishes/thumbs_medium')) mkdir('../images/dishes/thumbs_medium');

    // Generate filenames for original, small and medium files
    $originalFileName = "../images/dishes/originals/$id.jpg";
    $smallFileName = "../images/dishes/thumbs_small/$id.jpg";
    $mediumFileName = "../images/dishes/thumbs_medium/$id.jpg";

    // Move the uploaded file to its final destination
    move_uploaded_file($_FILES['dish-image']['tmp_name'], $originalFileName);

    // Crete an image representation of the original image
    $original = imagecreatefromjpeg($originalFileName);
    if (!$original) $original = imagecreatefrompng($originalFileName);
    if (!$original) $original = imagecreatefromgif($originalFileName);

    if (!$original) die();      

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(200, 200);
    $f_to_int = ($width>$square)?($width-$square)/2:0;
    imagecopyresized($small, $original, 0, 0, (int) $f_to_int, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
    imagejpeg($small, $smallFileName);

    // Calculate width and height of medium sized image (max width: 400)
    $mediumwidth = $width;
    $mediumheight = $height;
    if ($mediumwidth > 400) {
        $mediumwidth = 400;
        $mediumheight = $mediumheight * ( $mediumwidth / $width );
    }

    // Create and save a medium image
    $medium = imagecreatetruecolor($mediumwidth, (int) $mediumheight);
    imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, (int) $mediumheight, $width, $height);
    imagejpeg($medium, $mediumFileName);

    $user = User::getUser($db, $session->getEmail());


    $categories  = Category::getCategories($db, 10);
    foreach($categories as $category){
        if($category->name == $_POST['dish-categ']){
            $categoryId = $category->categoryId;
            $need = true;
        }
    }

    if(!$need){
        $category = Category::createCategory($db, $_POST['dish-categ']);
        $categoryId = $db->lastInsertId();
    }


    $dish = Dish::createDish($db, $_POST['dish-name'], $_POST['dish-price'], $_POST['dish-ing'], boolval(0), $categoryId, $_SESSION['id'], (int) $image_id);


    header('Location: ../pages/restaurant.php?id=' . $_SESSION['id']);
?>