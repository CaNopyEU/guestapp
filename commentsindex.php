<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gadb;
    charset=utf8', 'gadbuser', 'mojeheslo123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);


    if (empty($_POST) === false) {
    	$required_fields = array('name', 'email', 'comment',);
    	foreach ($_POST as $key=>$value){
    		if (empty($value) && in_array($key, $required_fields) === true) {
    			$errors[] = 'Empty error';
    			break 1;
    		}
    	}

if (!empty($errors)) {
    echo '<div class="errors">
    <p>Your comment could not be inserted!!!</p>';
} elseif(empty($errors)) {

    $sql = 'INSERT INTO `comments` SET
        `name` = :name,
        `email` = :email,
        `comment` = :comment,
        `cDate` = CURTIME()';

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->bindValue(':comment', $_POST['comment']);

    $stmt->execute();

    header('Location: index.php');
    }
}


    $sql = 'SELECT * FROM `comments`';
    $result = $pdo->query($sql);

    $title = 'Comments for everyone';

    $output = '';

    ob_start();

    while ($row = $result->fetch()) {
        ?>

           <div class="comment">
               <h3>On <?=htmlspecialchars($row['cDate'], ENT_QUOTES, 'UTF-8')?>,
                   <?=htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')?> wrote: </h3>
               <p>
               <?=htmlspecialchars($row['comment'], ENT_QUOTES, 'UTF-8')?>
               </p>
           </div>
           <?php }
           ?>

           <form method="post">
               <label>Name: </br><input type="text" name="name" placeholder="Yourname"></label>
               <label>Email: </br><input type="text" name="email" placeholder="your@email.com"></label>
               <label>Comment: </br><textarea name="comment" cols="30" rows="10"></textarea></label>
               <input type="submit" value="submit">
           </form>

<?php
        $output = ob_get_clean();
        }
    catch (PDOException $e) {

        $title = 'An error has occured';
        $output = 'Database error:' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
    }

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="comments.css">
        <title><?=$title?></title>
    </head>
    <body>
        <header>
            <h1>Comments for everyone</h1>
        </header>
            <main>
            <?=$output?>
        </main>
        <footer>
            &copy; CFE 2019
        </footer>
    </body>
</html>
