<!doctype html>
<html class="no-js" lang="en">
  <head>
<?php
// Check to make sure we indeed don't have any data for requested sub. If we do, it's probably because they hit refresh and we already started praw-everything.py for them. Redirect to result.php?subreddit-input=...

// COPYPASTED FROM result.php
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

// DOES Exist In Table:
if ($profanitytable != false) {
echo "<meta http-equiv='refresh' content='0; url=result.php?subreddit-input=";
echo $sanitized;
echo "' />";
} else {

// TODO RUN PRAW SCRIPT WITH THIS ARGUM,ENT
exec("python3 praw-everything.py" . $sanitized);

}









?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reddit Thingamajig | Sorry...</title>
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
         Sorry...
      </div> <!-- end search bar -->
      </div>
    </div> <!-- end fullwidth row -->
    <div class='row'> <!-- main content jumbo row -->
        We don't have any data on /r/<?php echo $_GET["subreddit-input"]; ?> right now.  But, we've just started getting it for you! Hit refresh to see what we've got so far, and check back in 20 minutes for fully accurate information.
    </div> <!-- end main content jumbo row -->


    <!-- End Content -->
 
 
    <!-- Footer -->
 
      <footer class="row">
        <hr> 
          <div class="large-8 small-12 columns">
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
