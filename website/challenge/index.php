<?php
  require '../global/database.php';

  $challengeId = htmlspecialchars($_GET["id"]);

  // TEMP:
  // This is a placeholder value, until the user accounts system is implemented.
  $memberId = 0;

  $db = Database::getConnection();

  // Check if challenge is valid.
  $query = $db->prepare('SELECT ChallengeId, Title, DatePosted, Difficulty, Description, Example FROM Challenge WHERE ChallengeId = ?');
  $query->execute([$challengeId]);

  if ($query->rowCount() > 0) {
    date_default_timezone_set('America/Toronto');
    $today = date("Y-m-d");

    // Don't show challenges that have been posted ahead of time.
    $challengeRow = $query->fetch();
    $challengeDate = $challengeRow['DatePosted'];
    if ($challengeDate <= $today) {
      $challengeId = $challengeRow['ChallengeId'];
      $challengeTitle = $challengeRow['Title'];
      $challengeDate = $challengeRow['DatePosted'];
      $challengeDifficulty = $challengeRow['Difficulty'];
      $challengeDescription = $challengeRow['Description'];
      $challengeExample = $challengeRow['Example'];

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

        date_default_timezone_set('America/Toronto');
        $timePosted = date("Y-m-d H:i:s");
        $language = $_POST['language'];

        // Delete member's previous submission and files for this challenge.
        $query = $db->prepare('SELECT SubmissionId FROM Submission WHERE ChallengeId = ? AND MemberId = ?');
        $query->execute([$challengeId, $memberId]);
        foreach ($query as $row) {
          $oldSubmissionId = $row['SubmissionId'];
          $query = $db->prepare('DELETE FROM Submission WHERE SubmissionId = ?');
          $query->execute([$oldSubmissionId]);

          $query = $db->prepare('DELETE FROM File WHERE SubmissionId = ?');
          $query->execute([$oldSubmissionId]);
        }

        $query = $db->prepare('INSERT INTO Submission(MemberId, ChallengeId, TimePosted, Language) VALUES (?, ?, ?, ?)');
        $query->execute([$memberId, $challengeId, $timePosted, $language]);

        $submissionId = 0;
        $query = $db->prepare('SELECT SubmissionId FROM Submission WHERE TimePosted = ?');
        $query->execute([$timePosted]);
        if ($query->rowCount() > 0) {
          foreach ($query as $row) {
            $submissionId = $row['SubmissionId'];
          }
        }

        $atLeastOneValidFile = false;
        for ($i = 1; $i <= $fileCount; $i++) {
          $code = htmlspecialchars($_POST['code' . $i]);
          $code = trim($code);
          if (!empty($code)) {
            $atLeastOneValidFile = true;
            if (isset($_POST['filename' . $i])) {
              $filename = $_POST['filename' . $i];
              $query = $db->prepare('INSERT INTO File(SubmissionId, Filename, Code) VALUES (?, ?, ?)');
              $query->execute([$submissionId, $filename, $code]);
            }
            else {
              $query = $db->prepare('INSERT INTO File(SubmissionId, Code) VALUES (?, ?)');
              $query->execute([$submissionId, $code]);
            }
          }
        }

        // If no files were added the submission is invalid and must be removed.
        if (!$atLeastOneValidFile) {
          $query = $db->prepare('DELETE FROM Submission WHERE SubmissionId = ?');
          $query->execute([$submissionId]);
        }
      }
?>

<html>
  <head>
    <title><?php echo $challengeTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="../global/collapsible.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>
    <script src="submission_extender.js"></script>
    <script src="../global/collapsible.js"></script>
  </head>
  <body>
    <h1>Challenge <?php echo $challengeId . ': ' . $challengeTitle; ?></h1>
    <p>Posted on <?php echo date("M j, Y", strtotime($challengeDate)); ?></p>

    <?php
      if ($challengeDifficulty === 1) {
        echo '<p>Difficulty: Easy</p>';
      }
      else if ($challengeDifficulty === 2) {
        echo '<p>Difficulty: Medium</p>';
      }
      else if ($challengeDifficulty === 3) {
        echo '<p>Difficulty: Hard</p>';
      }
    ?>

    <p><?php echo $challengeDescription; ?></p>

    <?php
      if ($challengeExample !== NULL) {
        echo '<h3>Example:</h3>';
        echo '<pre id="example">' . $challengeExample .'</pre>';
      }

      echo '<div id="submissions">';

      $db = Database::getConnection();

      // Get submissions for this challenge.
      $submissionQuery = $db->prepare('SELECT * FROM Submission WHERE ChallengeId = ? ORDER BY SubmissionId');
      $submissionQuery->execute([$challengeId]);
      foreach($submissionQuery as $submissionRow) {
        $submissionId = $submissionRow['SubmissionId'];
        // TEMP:
        // MemberId should be used to gather the member info once the log-in system is added.
        $firstName = "John";
        $lastName = "Smith";

        $language = $submissionRow['Language'];
        if ($language == 'cpp')
          $language = 'C++';
        else if ($language == 'c')
          $language = 'C';
        else if ($language == 'java')
          $language = 'Java';
        else if ($language == 'csharp')
          $language = 'C#';
        else if ($language == 'python')
          $language = 'Python';
        else if ($language == 'javascript')
          $language = 'JavaScript';
        else if ($language == 'other')
          $language = 'Other';
        else
          $language = 'ERROR';

        $submissionTime = date("M j, Y \a\\t g:i a", strtotime($submissionRow['TimePosted']));

        echo '<button class="collapsible">';
        echo '  <span class="language">' . $language . '</span>' . $firstName . ' ' . $lastName . '<i class="date">submitted ' . $submissionTime . '</i>';
        echo '</button>';
        echo '<div class="content">';
        // Group files together by SubmissionId.
        $fileQuery = $db->prepare('SELECT * FROM File WHERE SubmissionId = ? ORDER BY FileId');
        $fileQuery->execute([$submissionId]);
        foreach ($fileQuery as $fileRow) {
          if ($fileRow['Filename'] != NULL) {
            echo '<h3 class="filename">' . $fileRow['Filename'] . '</h3>';
          }
          echo '  <pre class="prettyprint linenums">' . $fileRow['Code'] . '</pre>';
        }
        echo '</div>';
      }

      echo '</div>';

      echo '<form action="' . $_SERVER['PHP_SELF'] . '?id=' . $challengeId . '" method="post">';
      echo '  <div id="code-submission-panel">';
      echo '    <span class="label">Language: </span>';
      echo '    <select name="language">';
      echo '      <option value="java">Java</option>';
      echo '      <option value="c">C</option>';
      echo '      <option value="csharp">C#</option>';
      echo '      <option value="cpp">C++</option>';
      echo '      <option value="python">Python</option>';
      echo '      <option value="javascript">JavaScript</option>';
      echo '      <option value="other">Other</option>';
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

<?php
    }
    else {
      echo 'While we appreciate your enthusiasm, this challenge will not be available until <b>' . date("M j, Y", strtotime($challengeDate)) . '</b>.';
    }
  }
  else {
    echo "Challenge not found!";
  }
?>
