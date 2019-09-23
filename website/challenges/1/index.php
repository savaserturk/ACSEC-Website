<?php
require 'database.php';

if (isset($_POST['submit'])) {
  $db = Database::getConnection();

  $query = $db->prepare('INSERT INTO File(Code) VALUES (?)');
  $query->execute([$_REQUEST['code']]);
}
?>

<html>
  <head>
    <title>1 - Gates</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js?skin=default"></script>
  </head>
  <body>
    <h1>Challenge 1: Gates</h1>
    <p>Posted on Apr 29 2019</p>
    <p>Difficulty: Easy</p>
    <p>
      Given 2 inputs (each a 0 or a 1), show the outputs of each of the following logic gates: AND, OR, and XOR. This is a good opportunity to look up the bitwise operators if you aren't familiar with them.
    </p>
    <h3>Example 1:</h3>
    <div class="example-code">
      <p>Enter two inputs: 0 1</p>
      <p>AND: 0</p>
      <p>OR:  1</p>
      <p>XOR: 1</p>
    </div>
    <h3>Example 2:</h3>
    <div class="example-code">
      <p>Enter two inputs: 1 1</p>
      <p>AND: 1</p>
      <p>OR:  1</p>
      <p>XOR: 0</p>
    </div>
    <?php
      $db = Database::getConnection();

      $query = $db->prepare('SELECT * FROM File');
      $query->execute();

      if ($query->rowCount() > 0) {
        foreach ($query as $row) {
          echo '<div class="content">';
          echo '  <pre class="prettyprint linenums lang-java">' . $row['Code'] . '</pre>';
          echo '</div>';
        }
      }

      echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">';
      echo '  <div id="code-submission-panel">';
      echo '    <textarea name="code" rows=4 cols=50 placeholder="Enter your solution here..."></textarea>';
      echo '    <input type="submit" name="submit" value="Submit">';
      echo '  </div>';
      echo '</form>';
    ?>
  </body>
</html>
