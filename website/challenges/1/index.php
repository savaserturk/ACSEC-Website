<?php
require 'database.php';

if (isset($_POST['submit'])) {
  $fileCount = 0;
  $fileFound = true;
  while ($fileFound) {
    if (isset($_POST['code' . ($fileCount + 1)])) {
      $fileCount++;
    }
    else {
      $fileFound = false;
    }
  }

  // TEMP:
  // This is a placeholder value, until the user accounts system is implemented.
  $memberId = 0;

  $challengeId = 1;
  date_default_timezone_set('America/Toronto');
  $timePosted = date("Y-m-d H:i:s");
  $language = $_POST['language'];

  $db = Database::getConnection();

  if ($language != 'none') {
    $query = $db->prepare('INSERT INTO Submission(MemberId, ChallengeId, TimePosted, Language) VALUES (?, ?, ?, ?)');
    $query->execute([$memberId, $challengeId, $timePosted, $language]);
  }
  else {
    $query = $db->prepare('INSERT INTO Submission(MemberId, ChallengeId, TimePosted) VALUES (?, ?, ?)');
    $query->execute([$memberId, $challengeId, $timePosted]);
  }

  // TODO: Get submission id.
  // $submissionId = ?;

  for ($i = 1; $i <= $fileCount; $i++) {
    $code = $_POST['code' . $i];
    if (isset($_POST['filename' . $i])) {
      $filename = $_POST['filename' . $i];
      $query = $db->prepare('INSERT INTO File(SubmissionId, Filename, Code) VALUES (?, ?, ?)');
      $query->execute([0, $filename, $code]);
    }
    else {
      $query = $db->prepare('INSERT INTO File(SubmissionId, Code) VALUES (?, ?)');
      $query->execute([0, $code]);
    }
  }
}
?>

<html>
  <head>
    <title>1 - Gates</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    <script src="submission_extender.js"></script>
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

      // TODO:
      // Group files by submission and by user as seen on mockup page.
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
      echo '    <select name="language">';
      echo '      <option value="none">select language</option>';
      echo '      <option value="java">java</option>';
      echo '      <option value="c">c</option>';
      echo '      <option value="csharp">c#</option>';
      echo '      <option value="cpp">c++</option>';
      echo '      <option value="python">python</option>';
      echo '      <option value="python">javascript</option>';
      echo '    </select>';
      echo '    <div id="file-area">';
      echo '      <div>';
      echo '        <input type="text" name="filename1" placeholder="filename">';
      echo '        <textarea name="code1" rows=4 cols=50 placeholder="Enter your solution here..."></textarea>';
      echo '      </div>';
      echo '    </div>';
      echo '    <button id="add-another-file" type="button">Add another file</button>';
      echo '    <input type="submit" name="submit" value="Submit">';
      echo '  </div>';
      echo '</form>';
    ?>
  </body>
</html>
