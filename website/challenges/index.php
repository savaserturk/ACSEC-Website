<?php
require '../global/database.php';

// TEMP:
// This is a placeholder value, until the user accounts system is implemented.
$memberId = 0;

?>

<html>
  <head>
    <title>Challenges</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>Challenges</h1>
    <ul>
      <?php

      // Get list of challenges.
      $db = Database::getConnection();
      $query = $db->prepare('SELECT * FROM Challenge ORDER BY ChallengeId DESC');
      $query->execute();
      foreach ($query as $row) {
        date_default_timezone_set('America/Toronto');
        $today = date("Y-m-d");

        // Don't show challenges that have been posted ahead of time.
        if ($row['DatePosted'] <= $today) {
          $challengeId = $row['ChallengeId'];
          $title = $row['Title'];
          $date = $row['DatePosted'];

          // Difficulties are stored in the database as integers from 1-3, so we must convert it into a more useful form. We'll set 1 as Easy and 3 as Hard.
          $difficulty = $row['Difficulty'];
          if ($difficulty == 1)
            $difficulty = 'Easy';
          else if ($difficulty == 2)
            $difficulty = 'Medium';
          else if ($difficulty == 3)
            $difficulty = 'Hard';

          // Count the number of members that have submitted a solution for this challenge.
          $submissionCountQuery = $db->prepare('SELECT * FROM Submission WHERE ChallengeId = ?');
          $submissionCountQuery->execute([$challengeId]);
          $submissionCount = $submissionCountQuery->rowCount();

          // Check whether this member has submitted a solution for this challenge already.
          $submittedQuery = $db->prepare('SELECT * FROM Submission WHERE ChallengeId = ? AND MemberId = ?');
          $submittedQuery->execute([$challengeId, $memberId]);
          $hasSubmitted = $submittedQuery->rowCount() > 0;

          echo '<li>';
          echo '  <a href="../challenge/?id=' . $challengeId . '">';

          if ($hasSubmitted)
            echo '  <div class="challenge-item submitted">';
          else
            echo '  <div class="challenge-item">';

          echo '      <h3 class="challenge-number">' . $challengeId . '</h3>';
          echo '      <h3 class="challenge-title">' . $title . '</h3>';
          echo '      <h3 class="challenge-difficulty">' . $difficulty . '</h3>';
          echo '      <h3 class="challenge-submissions">Submissions: ' . $submissionCount . '</h3>';
          echo '      <h3 class="challenge-date">' . $date . '</h3>';
          echo '    </div>';
          echo '  </a>';
          echo '</li>';
        }
      }

      ?>
    </ul>
  </body>
</html>
