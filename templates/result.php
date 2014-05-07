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

// Average queries

$avgnicenessquery = <<<'EOD'
--Average niceness of all subreddits, including ones with fewer than 100 subscribers
select avg("Niceness") as "AvgNiceness"
from
(
  select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
  from "Submissions"
  group by "SubredditName"
) as t2
EOD;

$avgnicenessresult = pg_query($avgnicenessquery) or die('Query failed: ' . pg_last_error());
$avgnicenesstable = pg_fetch_all($avgnicenessresult);
pg_free_result($avgnicenessresult);
$avgniceness = $avgnicenesstable[0]["AvgNiceness"];


$avgtriggerquery = <<<'EOD'
select avg("TriggerHappiness") as "AvgTriggerHappiness"
from
(
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
  ) as t2
) as t3
EOD;

$avgtriggerresult = pg_query($avgtriggerquery) or die('Query failed: ' . pg_last_error());
$avgtriggertable = pg_fetch_all($avgtriggerresult);
pg_free_result($avgtriggerresult);
$avgtrigger = $avgtriggertable[0]["AvgTriggerHappiness"];

$avgprofquery = <<<'EOD'
select avg("AvgProfanityScore") as "AvgAvgProfanityScore"
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
EOD;
$avgprofresult = pg_query($avgprofquery) or die('Query failed: ' . pg_last_error());
$avgproftable = pg_fetch_all($avgprofresult);
pg_free_result($avgprofresult);
$avgprofanity = $avgproftable[0]["AvgAvgProfanityScore"];

$avgenthquery = <<<'EOD'
select avg("AvgEnthusiasmScore") as "AvgAvgEnthusiasmScore"
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
EOD;
$avgenthresult = pg_query($avgenthquery) or die ('Query failed: ' . pg_last_error());
$avgenthtable = pg_fetch_all($avgenthresult);
pg_free_result($avgenthresult);
$avgenthusiasm = $avgenthtable[0]["AvgAvgEnthusiasmScore"];

// Per-subreddit queries

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

$triggerquerybegin = <<<'EOD'
select "SubredditName", "AvgVotes" / ("Subscribers"+1) as "TriggerHappiness"
from
(
  select "SubredditName", avg("Upvotes"+"Downvotes") as "AvgVotes"
  from "Submissions"
  where "SubredditName" = '
EOD;
$triggerqueryend = <<<'EOD'
'
  group by "SubredditName"
) as t1
natural join
(
  select "SubredditName", "Subscribers"
  from "Subreddits"
) as t2
EOD;
$triggerquery = $triggerquerybegin . $sanitized . $triggerqueryend;
$triggerresult = pg_query($triggerquery) or die('Query failed:' . pg_last_error());
$triggertable = pg_fetch_all($triggerresult);
pg_free_result($triggerresult);
$trigger = $triggertable[0]["TriggerHappiness"];

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
          <div class='large-5 columns search-again-prefix'>
            <i class='fi-social-reddit size-60'></i>
            Here's you go, /r/
          </div>
          <div class='reddit-search large-3 columns'>
          <form action="result.php" method="get">
              <input type='text' id='search-again-input' placeholder='<?php echo $sanitized; ?>' name="subreddit-input"></input>
          </div>
          <div class="reddit-search large-2 columns left">
            <input type="submit" class='button search-button' id='reddit-search-button' value="Crunch numbers again!">
          </div>
          </form>
          </div>
      </div> <!-- end search bar -->
      </div>
    </div> <!-- end fullwidth row -->
    <div class='row bigwidth'> <!-- main content jumbo row -->
       <div class="large-3 columns">
        <h2>it's 
        <?php
            if ($trigger < $avgtrigger) {
                echo "stingy";
            } else {
                echo "trigger-happy";
            }
        ?>
        </h2>
        <p>
        <?php
        if ($trigger < $avgtrigger) {
            echo "<span class='huge downvote'>";
        } else {
            echo "<span class='huge upvote'>";
        }
        echo round($trigger*10000,2);
        echo "</span><br>trigger-happiness index";
        ?>
        </p>
        <p class='center'>average on other subreddits: <strong><?php echo round($avgniceness*100,2); ?></strong></p>
      </div>

      <div class="large-3 columns">
        <h2>it's 
        <?php
            if ($niceness < $avgniceness) {
                echo "negative";
            } else {
                echo "positive";
            }
        ?>
        </h2>
        <p>
        <?php
        if ($niceness < $avgniceness) {
            echo "<span class='huge downvote'>";
        } else {
            echo "<span class='huge upvote'>";
        }
        echo round($niceness*100,2);
        echo "% </span><br>upvotes";
        ?>
        </p>
        <p class='center'>average on other subreddits: <strong><?php echo round($avgniceness*100,2); ?>%</strong></p>
      </div>
     
      <div class="large-3 columns">
        <h2>it's <?php if($enthusiasm>$avgenthusiasm){echo " ...'enthusiastic'";}else{echo "civil";} ?></h2>
        <p>
            <?php
        if ($enthusiasm > $avgenthusiasm) {
            echo "<span class='huge downvote'>";
        } else {
            echo "<span class='huge upvote'>";
        }
        echo round($enthusiasm,4);
        echo "</span><br>enthusiasm index";
        ?>
      </p>
        </p>
        <p>average on other subreddits: <strong><?php echo round($avgenthusiasm,2); ?></strong></p>
      </div>
      <div class="large-3 columns">
        <h2>it's <?php if($profanity>$avgprofanity){echo "profane";}else{echo "decent";} ?></h2>
        <p>
            <?php
        if ($profanity > $avgprofanity) {
            echo "<span class='huge downvote'>";
        } else {
            echo "<span class='huge upvote'>";
        }
        echo round($profanity*100,2);
        echo "%</span><br>profanity";
        ?>
      </p>
        <p>average on other subreddits: <strong><?php echo round($avgprofanity*100,2); ?>%</strong></p>
      </div>
    </div> <!-- end main content jumbo row -->

    <!-- End Content -->
 
 
    <!-- Footer -->
 
      <footer class="row">
        <hr> 
          <div class="large-8 large-12 columns">
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
