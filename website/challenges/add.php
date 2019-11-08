<?php
  // NOTE: This page should only be accessible by admins.

  require "../global/database.php";

  if (isset($_POST['submit'])) {
    $db = Database::getConnection();

    if ($_POST['postdate'] !== '' && $_POST['title'] !== '' && $_POST['description'] !== '') {
      if ($_POST['example'] === '') {
        $query = $db->prepare('INSERT INTO Challenge(Title, DatePosted, Difficulty, Description) VALUES (?, ?, ?, ?)');
        $query->execute([ $_POST['title'], $_POST['postdate'], $_POST['difficulty'], $_POST['description']]);
      }
      else {
        $query = $db->prepare('INSERT INTO Challenge(Title, DatePosted, Difficulty, Description, Example) VALUES (?, ?, ?, ?, ?)');
        $query->execute([ $_POST['title'], $_POST['postdate'], $_POST['difficulty'], $_POST['description'], $_POST['example']]);
      }
    }

    $query = $db->prepare('SELECT ChallengeId FROM Challenge WHERE DatePosted = ?');
    $query->execute([$_POST['postdate']]);

    $submitted = false;
    if ($query->rowCount() > 0) {
      $submitted = true;
    }

    if ($submitted == true) {
      echo "Challenge successfully added.";
    }
    else {
      echo "Challenge could not be added!";
    }
  }
  else {
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="add.css">
  </head>
  <body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <div>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="">
      </div>
      <div>
        <label for="date">Date to post:</label>
        <input type="date" name="postdate" id="date" value="">
      </div>
      <div>
        <label for="difficulty">Difficulty:</label>
        <select name="difficulty" id="difficulty">
          <option value="1">Easy</option>
          <option value="2">Medium</option>
          <option value="3">Hard</option>
        </select>
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="10" cols="30" value=""></textarea>
      </div>
      <div>
        <label for="example">Example:</label>
        <textarea name="example" id="example" rows="10" cols="30" value=""></textarea>
      </div>
      <input type="submit" name="submit" value="Submit">
    </form>
  </body>
</html>

<?php
  }
?>
