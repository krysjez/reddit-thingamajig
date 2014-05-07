<!doctype html>
<html class="no-js" lang="en">
  <head>

<?php
//  SET VARIABLES ACCORDING TO REALITY
$sanitized = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET["subreddit-input"]);
//$sanitized = "russia";
// Connect to database (to run queries during the whole page)
$dbconn = pg_connect("host=cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com dbname=thingamajig user=michaelnestler password=testing54321")
    or die('Could not connect: ' . pg_last_error());
$profanityquerybegin = <<<'EOD'
select "SubredditName", avg("ProfanityScore") as "AvgProfanityScore"
from 
(
	select "Comments"."ProfanityScore" as "ProfanityScore", "Submissions"."SubredditName" as "SubredditName"
	from "Comments" join "Submissions" using ("SubmissionID")
	where "SubredditName" = '
EOD;
$profanityqueryend = <<<'EOD'
') as t3
group by "SubredditName"
EOD;
$profanityquery = $profanityquerybegin . $sanitized . $profanityqueryend;
$profanityresult = pg_query($profanityquery) or die('Query failed: ' . pg_last_error());
// get as php table
$profanitytable = pg_fetch_all($profanityresult);
// Free memory
pg_free_result($profanityresult);

// Does Not Exist In Table:
if ($profanitytable == false) {
echo "<meta http-equiv='refresh' content='0; url=nodata.php?subreddit-input=";
echo $sanitized;
echo "' />";
}
$profanity = $profanitytable[0]["AvgProfanityScore"];

$enthusiasmquerybegin = <<<'EOD'
--Enthusiasm of that subreddit
select "SubredditName", avg("EnthusiasmScore") as "AvgEnthusiasmScore"
from 
(
	select "Comments"."EnthusiasmScore" as "EnthusiasmScore", "Submissions"."SubredditName" as "SubredditName"
	from "Comments" join "Submissions" using ("SubmissionID")
	where "SubredditName" = '
EOD;
$enthusiasmqueryend = <<<'EOD'
') as t3
group by "SubredditName"
EOD;
$enthusiasmquery = $enthusiasmquerybegin . $sanitized . $enthusiasmqueryend;
$enthusiasmresult = pg_query($enthusiasmquery) or die('Query failed: ' . pg_last_error());
$enthusiasmtable = pg_fetch_all($enthusiasmresult);
pg_free_result($enthusiasmresult);
$enthusiasm = $enthusiasmtable[0]["AvgEnthusiasmScore"];

$nicenessquerybegin = <<<'EOD'
select "SubredditName", "Niceness"
from
(
	select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
	from "Submissions"
	where "SubredditName" = '
EOD;
$nicenessqueryend = <<<'EOD'
'
	group by "SubredditName"
) as t1
EOD;
$nicenessquery = $nicenessquerybegin . $sanitized . $nicenessqueryend;
$nicenessresult = pg_query($nicenessquery) or die('Query failed: ' . pg_last_error());
$nicenesstable = pg_fetch_all($nicenessresult);
pg_free_result($nicenessresult);
$niceness = $nicenesstable[0]["Niceness"];



//$niceness = "0.5";
//$enthusiasm = "1.3";
//$avgniceness = "0.6";
//$avgenthusiasm = "0.7";
//$profanity = "0.1";
//$avgprofanity = "0.05";
?>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reddit Thingamajig | Numbers!</title>
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
      <div class='row search-bar'> <!-- search bar -->
          <div class='small-5 columns search-again-prefix'>
            <i class='fi-social-reddit size-60'></i>
            Here's what we found about /r/
          </div>
          <div class='reddit-search small-3 columns'>
          <form action="result.php" method="get">
              <input type='text' id='search-again-input' placeholder='<?php echo $sanitized; ?>' name="subreddit-input"></input>
          </div>
          <div class="reddit-search small-2 columns left">
            <input type="submit" class='button search-button' id='reddit-search-button' value="Crunch numbers again!">
          </div>
          </form>
          </div>
      </div> <!-- end search bar -->
      </div>
    </div> <!-- end fullwidth row -->
    <div class='row'> <!-- main content jumbo row -->
      <div class="small-4 columns">
        <h1>it's 
        <?php
            if ($niceness < $avgniceness) {
                echo "negative";
            } else {
                echo "positive";
            }
        ?>
        </h1>
        <p>
        <?php
        if ($niceness < $avgniceness) {
            echo "<span class='huge downvote'>";
        } else {
            echo "<span class='huge upvote'>";
        }
        echo round($niceness*100,2);
        echo "</span>";
        ?> percent upvotes
        <!--
          <span class='huge upvote'>3 <i class='fi-arrow-up'></i></span>
          <span class='huge downvote'>18 <i class='fi-arrow-down'></i></span>
          -->
        </p>
        <p>average on other subreddits: <?php echo $avgniceness*100; ?></p>
      </div>
      <!-- I don't think we ever calculated this
      <div class="small-4 columns">
        <h1>it's <?php //if($comments>$avgcomments){echo "vocal";}else{echo "quiet";} ?></h1>
        <p>
          <span class='huge upvote'><?php //echo $comments; ?> <i class='fi-comment'></i></span> comments per post
        </p>
        <p>average on other subreddits: <?php //echo $avgcomments; ?></p>
      </div> -->
      <div class="small-4 columns">
        <h1>it's <?php if($enthusiasm>$avgenthusiasm){echo " ...'enthusiastic'";}else{echo "civil";} ?></h1>
        <p>
          <span class='huge'><?php echo round($enthusiasm,4); ?> <i class='fi-comment'></i></span> ratio of CAPS to lowercase
        </p>
        <p>average on other subreddits: <?php echo $avgenthusiasm; ?></p>
      </div>
      <div class="small-4 columns">
        <h1>it's <?php if($profanity>$avgprofanity){echo "profane";}else{echo "decent";} ?></h1>
        <p>
          <span class='huge'><?php echo round($profanity,4); ?> <i class='fi-comment'></i></span> profanity score
        </p>
        <p>average on other subreddits: <?php echo $avgprofanity; ?></p>
      </div>
    </div> <!-- end main content jumbo row -->

<!--    <div class="row bigwidth"> <!-- main content row -->
<!--      <div class='small-4 columns'>
          <h2>What this means</h2>
          <p>Wasdklfjasdkfjafjad</p>
          <img src='http://i.imgur.com/4VrgVJ5.jpg'>
          <p class='caption'>by /u/cartoonheroes</p>
      </div> <!-- end left side -->
<!--      <div class='small-8 columns'> <!-- right side -->
<!--        <div class='row'> <!-- sub row 1 -->
<!--          <div class='small-6 columns'>
            <div class='stat-box'>
              <h3><i class='fi-heart'></i>&nbsp;Users with best karma</h3>
              <p>So much orange!<br> Average ratio of upvotes to downvotes per post.</p>
              <ol>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong>/<li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
              </ol>
            </div>
          </div>
          <div class='small-6 columns'>
            <div class='stat-box'>
              <h3><i class='fi-torsos-all'></i>&nbsp;Most active users</h3>
              <p>These people comment a lot.</p>
              <ol>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
              </ol>
            </div>
          </div>
        </div> <!-- end sdub row 1 -->
<!--        <div class='row'> <!-- sub row 2 -->
<!--          <div class='small-6 columns'>
            <div class='stat-box'>
              <h3><i class='fi-heart'></i>&nbsp;Most profane users</h3>
              <p>So much orange!<br> Average ratio of upvotes to downvotes per post.</p>
              <ol>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
              </ol>
            </div>
          </div>
          <div class='small-6 columns'>
            <div class='stat-box'>
              <h3><i class='fi-heart'></i>&nbsp;Most ??? subreddits</h3>
              <p>So much orange!<br> Average ratio of upvotes to downvotes per post.</p>
              <ol>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
                <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
              </ol>
            </div>
          </div>
        </div> <!-- end sub row 2 -->
<!--      </div> <!-- end right side -->
<!--    </div> <!-- end main content row 1 -->

<!--    <div class="row"> <!-- main content row 2 -->
<!--      <div class='small-12 columns'>
        <div class='stat-box'>
          <h3>Tips for moderators</h3>
          <p>LOOK AT THIS INCREDIBLE VIDEO!!!<br> Ratio of UPPERCASE and punctuation to lowercase.</p>
      </div>
    </div>
    </div> <!-- end main content row 2 -->


    <!-- End Content -->
 
 
    <!-- Footer -->
 
      <footer class="row">
        <hr> 
          <div class="large-8 small-12 columns">
              <p>* or most hated. /r/___, I'm looking at you.</p>
              <p>Made for Introduction to Database Systems, CPSC 473 Spring 2014, Yale University</p>
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
