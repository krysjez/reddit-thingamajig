<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reddit Thingamajig | Welcome</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/home.css" />
    <link rel="stylesheet" href="css/foundation-icons.css" />
    <!-- Optional, web font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>
   
  <nav class="top-bar" data-topbar>
    <ul class="title-area">
      <!-- Title Area -->
      <li class="name">
        <h1>
          <a href="index.php">
            Reddit Thingamajig
          </a>
        </h1>
      </li>
      <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
    </ul>
 
    <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">
        <li><a href="#credits">Made by krysjez/versere/augusthex</a></li>
        <li class="divider"></li>
        <li>          
          <a href='mailto:reddit@jessicayang.org'><i class='fi-mail' style='color:white; margin-right: 10px'></i>Feedback</a>
        </li>
      </ul>
    </section>
  </nav>
 
  <!-- End Top Bar -->
 
  <div class="row fullwidth">
    <div class="large-12 columns">
      <div class="row">   <!-- Jumbo Header -->
        <div class="large-8 large-centered columns jumbo">
          <h1>Get insights on your<br> favorite* subreddits</h1>
          <h3>Enter a subreddit name below and we'll tell you how it measures up.</h3>
        </div>
      </div>    <!-- End Jumbo Header -->

      <div class='row search-bar'> <!-- search bar -->
          <!-- <div class='row collapse'> -->
          <div class='small-5 columns reddit-prefix'>
            <i class='fi-social-reddit size-60'></i>
            reddit.com/r/
          </div>
          <!-- </div> -->
          <div class='reddit-search small-3 columns'>
            <form action="result.php" method="get">
              <input type='text' id='subreddit-input' name="subreddit-input"></input>
          </div>
          <div class="reddit-search small-2 columns left">
            <input type="submit" class='button search-button' id='reddit-search-button' value="Give me numbers!">
        </div>

            </form>
          </div>
      </div> <!-- end search bar -->
 
      </div>
    </div> <!-- end fullwidth row -->
 
    <div class='row'> <!-- main content header row -->
      <div class="small-12 columns">
        <p>Here are some interesting statistics we've from the information we've collected so far. Click on any subreddit name to see more details about that subreddit!</p>
      </div>
    </div> <!-- end main content header row -->
<?php
// Connect to database (to run queries during the whole page)
$dbconn = pg_connect("host=cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com dbname=thingamajig user=michaelnestler password=testing54321")
    or die('Could not connect: ' . pg_last_error());
?>

    <div class="row bigwidth"> <!-- main content row 1 -->
      <div class='large-4 columns'> <!-- trigger happiness -->
        <div class='stat-box'>
          <h3><i class='fi-arrow-up'></i><i class='fi-arrow-down'></i>&nbsp;Trigger-happy subreddits</h3>
          <p>Ooh, shiny buttons! Based on the number of votes per submission, normalized for subscribership.</p>
          <?php
            // Performing SQL query
            // multiline strings start with <<<'EOD' and end with EOD;
            $query = <<<'EOD'
--Most trigger-happy subreddits
--This will give the top subreddits based on the average total votes per post, normalized by the number of subscribers. We normalize by the number of subscribers because otherwise, we'll simply get the most active subreddits, like r/worldnews and r/politics. Also, we cut off subreddits that are too small, with fewer than 100 subscribers. 
select "SubredditName", "AvgVotes" / ("Subscribers"+1) as "TriggerHappiness"
from
(
	select "SubredditName", avg("Upvotes"+"Downvotes") as "AvgVotes"
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName", "Subscribers"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by "TriggerHappiness" desc
EOD;
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>

          <ol>
            <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><a href='result.php?subreddit-input=";
                echo $table[$i]["SubredditName"];
                echo "'><strong>";
                echo $table[$i]["SubredditName"];
                echo "</strong></a> with trigger-happiness of <strong>";
                echo round($table[$i]["TriggerHappiness"]*10000,2);
                echo "</strong></li>";
            }
            ?>
          </ol>
        </div>
      </div>
      <div class='large-4 columns'> <!-- meanest subreddits -->
        <div class='stat-box'>
          <h3><i class='fi-arrow-down'></i><i class='fi-arrow-down'></i>&nbsp;Meanest subreddits</h3>
          <p>If you're looking for a friendly place, this isn't it.
          <br> Lowest percentage of votes that are upvotes.</p>
          <?php
            // Performing SQL query
            $query = <<<'EOD'
--Meanest subreddits
--Subreddits with the lowest average percentage of upvotes in its submissions. Same query as the "Niceness" measure, just flip the order.
select "SubredditName", "Niceness"
from
(
	select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by "Niceness" asc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result); 
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><a href='result.php?subreddit-input=";
                echo $table[$i]["SubredditName"];
                echo "'><strong>";
                echo $table[$i]["SubredditName"];
                echo "</strong></a> with <strong>";
                echo round($table[$i]["Niceness"]*100,2);
                echo "%</strong> upvotes</li>";
            }
          ?></ol>
        </div>
      </div>
      <div class='large-4 columns'> <!-- nicest subreddits -->
        <div class='stat-box'>
          <h3><i class='fi-heart'></i>&nbsp;Nicest subreddits</h3>
          <p>So much orange!<br> Highest percentage of votes that are upvotes.</p>
<?php
// Performing SQL query
$query = <<<'EOD'
--Nicest subreddits
--Subreddits with highest average percentage of upvotes in its submissions
select "SubredditName", "Niceness"
from
(
	select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by "Niceness" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><a href='result.php?subreddit-input=";
                echo $table[$i]["SubredditName"];
                echo "'><strong>";
                echo $table[$i]["SubredditName"];
                echo "</strong></a> with <strong>";
                echo round($table[$i]["Niceness"]*100,2);
                echo "%</strong> upvotes</li>";
            }
          ?>
          </ol>
        </div>
      </div>
    </div> <!-- end main content row 1 -->

    <div class="row bigwidth"> <!-- main content row 2 -->
      <div class='large-4 columns'> <!-- enthusiastic -->
        <div class='stat-box'>
          <h3><i class='fi-volume'></i>&nbsp;Enthusiastic subreddits</h3>
          <p>THEY USE LOTS OF CAPS AND PUNCTUATION?!?!!<br> 
          (opposed to lowercase, in comments.)</p>
          <?php
            // Performing SQL query
$query = <<<'EOD'
--Most enthusiastic subreddits
--Gives subreddits that use a lot of uppercase characters and exclamation marks
select "SubredditName", "AvgEnthusiasmScore"
from
(
	select "SubredditName", avg("EnthusiasmScore") as "AvgEnthusiasmScore"
	from 
	(
		select "Comments"."EnthusiasmScore" as "EnthusiasmScore", "Submissions"."SubredditName" as "SubredditName"
		from "Comments" join "Submissions" using ("SubmissionID")
	) as t3
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by "AvgEnthusiasmScore" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
            <?php 
              for ($i="0"; $i<3; $i=$i+1){
                  echo "<li><a href='result.php?subreddit-input=";
                  echo $table[$i]["SubredditName"];
                  echo "'><strong>";
                  echo $table[$i]["SubredditName"];
                  echo "</strong></a> with an enthusiasm of <strong>";
                  echo round($table[$i]["AvgEnthusiasmScore"],2);
                  echo "</strong></li>";
              }
            ?>
          </ol>
        </div>
      </div>
      <div class='large-4 columns'> <!-- profane subs -->
        <div class='stat-box'>
          <h3><i class='fi-comment-quotes'></i>&nbsp;Most profane subreddits</h3>
          <p>#@$^$*#&! Profanity as a percentage of all words in comments on that subreddit.</p>
          <?php
            // Performing SQL query
$query = <<<'EOD'
--Most profane subreddits
--Gives the subreddits with the highest average profanity score in the submissions. Each submission has a profanity score, which is calculated from its comments. Also, cut off subreddits with fewer than 100 subscribers.
select "SubredditName", "AvgProfanityScore"
from
(
	select "SubredditName", avg("ProfanityScore") as "AvgProfanityScore"
	from 
	(
		select "Comments"."ProfanityScore" as "ProfanityScore", "Submissions"."SubredditName" as "SubredditName"
		from "Comments" join "Submissions" using ("SubmissionID")
	) as t3
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by "AvgProfanityScore" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><a href='result.php?subreddit-input=";
                echo $table[$i]["SubredditName"];
                echo "'><strong>";
                echo $table[$i]["SubredditName"];
                echo "</strong></a> with <strong>";
                echo round($table[$i]["AvgProfanityScore"]*100,2);
                echo "%</strong> profanity</li>";
            }
          ?>
          </ol>
        </div>
      </div>
      <div class='large-4 columns'> <!-- profane users -->
        <div class='stat-box'>
          <h3><i class='fi-torso'></i>&nbsp;Most profane users</h3>
          <p>You know which subreddits swear a lot...now meet the ones doing it. 
          Percentage of profanity in user's comments.</p>
          <?php
            // Performing SQL query
$query = <<<'EOD'
--Most profane users, based on their comments. Only users with at least 10 comments are considered. 
select "Username", "AvgProfanityScore"
from
(
	select "Username", avg("ProfanityScore") as "AvgProfanityScore"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "CommentCount" 
		from "User_commented" 
		group by "Username"
	) as asdf
	where "CommentCount" >= 10
) as t2
order by "AvgProfanityScore" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with <strong>";
                echo round($table[$i]["AvgProfanityScore"]*100,2);
                echo "%</strong> profanity</li>";
            }
          ?>
          </ol>
        </div>
      </div>
    </div>

<div class='row bigwidth'>
      <div class='large-4 columns'> <!-- enthusiastic users -->
        <div class='stat-box'>
          <h3><i class='fi-torsos-all'></i>&nbsp;Most enthusiastic <strong>users</strong></h3>
          <p>THESE PEOPLE LIKE SHOUTING?!<br>Based on the contents of user comments.</p>
          <?php
            // Performing SQL query
$query = <<<'EOD'
--Most enthusiastic users, based on their comments. Only users with at least 10 comments are included.
select "Username", "AvgEnthusiasmScore"
from
(
	select "Username", avg("EnthusiasmScore") as "AvgEnthusiasmScore"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "CommentCount" 
		from "User_commented" 
		group by "Username"
	) as asdf
	where "CommentCount" >= 10
) as t2
order by "AvgEnthusiasmScore" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with an enthusiasm of <strong>";
                echo round($table[$i]["AvgEnthusiasmScore"],2);
                echo "</strong></li>";
            }
          ?>
          </ol>
        </div>
      </div>

      <div class='large-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-arrows-out'></i>&nbsp;Top submitters</h3>
          <p>We like them!<br>Percentage upvotes on their submissions.</p>
          <?php
            // Performing SQL query
            $query = <<<'EOD'
--Most highly voted users, based on their submissions. These are the users with the highest average percentage of upvotes in their submissions. Only users with at least 5 submissions are considered.
select "Username", "UserSubmissionPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserSubmissionPopularity"
	from ("Users" natural join "User_submitted") join "Submissions" using ("SubmissionID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "SubmissionCount" 
		from "User_submitted" 
		group by "Username"
	) as asdf
	where "SubmissionCount" >= 5
) as t2
order by "UserSubmissionPopularity" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with <strong>";
                echo round($table[$i]["UserSubmissionPopularity"]*100,2);
                echo "%</strong> upvotes</li>";
            }
          ?>
          </ol>
        </div>
      </div>
      <div class='large-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-arrows-in'></i>&nbsp;Unpopular submitters</h3>
          <p>We...don't like these as much.<br>Percentage upvotes on their submissions.</p>
          <?php
            // Performing SQL query
            $query = <<<'EOD'
--Least highly voted users, based on submissions. Only users with at least 5 submissions are considered.
select "Username", "UserSubmissionPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserSubmissionPopularity"
	from ("Users" natural join "User_submitted") join "Submissions" using ("SubmissionID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "SubmissionCount" 
		from "User_submitted" 
		group by "Username"
	) as asdf
	where "SubmissionCount" >= 5
) as t2
order by "UserSubmissionPopularity" asc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with a score of <strong>";
                echo round($table[$i]["UserSubmissionPopularity"],2);
                echo "</strong></li>";
            }
          ?>
          </ol>
        </div>
      </div>
    </div> <!-- end content row 3 -->

<div class='row bigwidth'> <!-- content row 4 -->
      <div class='large-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-comments'></i>&nbsp;Popular commenters</h3>
          <p>Redditors with the highest average upvote percentage, based on their comments.</p>
          <?php
            // Performing SQL query
            $query = <<<'EOD'
--Highly voted commenters. The redditors with the highest average upvote percentage, based on their comments. Only users with at least 10 comments are considered. 
select "Username", "UserPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserPopularity"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "CommentCount" 
		from "User_commented" 
		group by "Username"
	) as asdf
	where "CommentCount" >= 10
) as t2
order by "UserPopularity" desc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with a score of <strong>";
                echo round($table[$i]["UserPopularity"],2);
                echo "</strong></li>";
            }
          ?>
          </ol>
        </div>
      </div>
      

      <div class='large-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-comment-minus'></i>&nbsp;Unpopular commenters.</h3>
          <p>Redditors with the lowest average upvote percentage, based on their comments.</p>
          <?php
            // Performing SQL query
            $query = <<<'EOD'
--Least highly voted commenters. Only users with at least 10 comments are considered. 
select "Username", "UserPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserPopularity"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	group by "Username"
) as t1
natural join
(
	select "Username"
	from
	(
		select "Username", count(*) as "CommentCount" 
		from "User_commented" 
		group by "Username"
	) as asdf
	where "CommentCount" >= 10
) as t2
order by "UserPopularity" asc
EOD;
            $result = pg_query($query);// or die('Query failed: ' . pg_last_error());
            // get as php table
            $table = pg_fetch_all($result);
            // Free memory
            pg_free_result($result);
          ?>
          <ol>
          <?php 
            for ($i="0"; $i<3; $i=$i+1){
                echo "<li><strong>";
                echo $table[$i]["Username"];
                echo "</strong> with a score of <strong>";
                echo round($table[$i]["UserPopularity"],2);
                echo "</strong></li>";
            }
          ?>
          </ol>
        </div>
      </div>

      <div class='large-4 columns'>
          <h2>Thanks for using!</h2>
          <div class='row'>
            <div class='small-6 columns'>
              <p>Leaving already? Try this out with your favorite subreddit!</p>
            </div>
            <div class='small-6 columns'>
              <img src='http://www.redditstatic.com/about/assets/reddit-alien.png' width='150' class='right'>
            </div>
      </div>
      </div>
    <!-- end main content row 4 -->



    <!-- End Content -->
 
 
    <!-- Footer -->
 
      <footer class="row">
        <div class="large-12 columns"><hr>
            <div class="row">
 
              <div class="large-8 small-12 columns">
                  <p>* or most hated. /r/___, I'm looking at you.</p>
                  <p>Made for Introduction to Database Systems, CPSC 473 Spring 2014, Yale University</p>
              </div>

              <div class='large-6 columns' id='credits'>
                <p>krysjez is <a href='http://jessicayang.org'>Jessica Yang</a>; versere is Kevin Liu; augusthex is <a href='http://www.michaelnestler.com'>Michael Nestler</a>.</p>
              </div>
 
            </div>
        </div>
      </footer>
 
    <!-- End Footer -->
 
    </div>
  </div>
 
  <script>
  document.write('<script src=js/vendor/' +
  ('__proto__' in {} ? 'zepto' : 'jquery') +
  '.js><\/script>')
  </script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
<!-- End Footer -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/templates/foundation.js"></script>
    <script>
      $(document).foundation();

      var doc = document.documentElement;
      doc.setAttribute('data-useragent', navigator.userAgent);
    </script>
  </body>
</html>
<?php
// Close database connection (probably superfluous)
pg_close($dbconn);
?>