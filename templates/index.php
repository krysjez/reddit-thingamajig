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
          <a href="#">
            Reddit Thingamajig
          </a>
        </h1>
      </li>
      <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
    </ul>
 
    <section class="top-bar-section">
      <!-- Right Nav Section -->
      <ul class="right">
        <li><a href="#">Made by krysjez/versere/augusthex</a></li>
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
        <div class="small-6 small-centered columns jumbo">
          <h1>Get insights on your<br> favorite* subreddits</h1>
          <h3>Enter a subreddit name below and we'll find out what their and did those feet in ancient time walk upon England's mountains green.</h3>
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
            <form action="result.php" method="post">
              <input type='text' id='subreddit-input' name="subreddit-input"></input>
          </div>
          <div class="reddit-search small-2 columns left">
            <input type="submit" class='button' id='reddit-search-button' value="Give me numbers!">
        </div>

             
              <!-- <i class='fi-magnifying-glass size-48'></i> -->
            </form>
          </div>
      </div> <!-- end search bar -->
 
      </div>
    </div> <!-- end fullwidth row -->
 
    <div class='row'> <!-- main content header row -->
      <div class="small-12 columns">
        <p>Here are some interesting summaries of the information collected from all user searches done so far, last updated <b>PROBABLY NEED A DATABASE ENTRY FOR THIS</b>. Search for a subreddit of your own above to refresh our data!</p>
        <?php echo '<p> This sentence is brought to you by PHP</p>'; ?>
      </div>
    </div> <!-- end main content header row -->

    <div class="row bigwidth"> <!-- main content row 1 -->
      <div class='small-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-arrow-up'></i><i class='fi-arrow-down'></i>&nbsp;Most trigger-happy subreddits</h3>
          <p>Ooh, shiny buttons!<br>Total votes per post.</p>
          <ol>
            <li><a href='#'><strong>subreddit name</strong></a> with <strong>x.xx</strong> votes per post</li>
            <li><a href='#'><strong>subreddit name</strong></a> with <strong>x.xx</strong> votes per post</li>
            <li><a href='#'><strong>subreddit name</strong></a> with <strong>x.xx</strong> votes per post</li>
          </ol>
        </div>
      </div>
      <div class='small-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-arrow-down'></i><i class='fi-arrow-down'></i>&nbsp;Meanest subreddits</h3>
          <p>If you're looking for a friendly place, this isn't it.<br> Average ratio of downvotes to upvotes per post.</p>
          <ol>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
          </ol>
        </div>
      </div>
      <div class='small-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-heart'></i>&nbsp;Nicest subreddits</h3>
          <p>So much orange!<br> Average ratio of upvotes to downvotes per post.</p>
          <ol>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
          </ol>
        </div>
      </div>
    </div> <!-- end main content row 1 -->

    <div class="row bigwidth"> <!-- main content row 2 -->
      <div class='small-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-volume'></i>&nbsp;Most enthusiastic subreddits</h3>
          <p>LOOK AT THIS INCREDIBLE VIDEO!!!<br> Ratio of UPPERCASE and punctuation to lowercase.</p>
          <ol>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
          </ol>
        </div>
      </div>
      <div class='small-4 columns'>
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
      <div class='small-4 columns'>
        <div class='stat-box'>
          <h3><i class='fi-heart'></i>&nbsp;Most asdlfkja subreddits</h3>
          <p>So much orange!<br> Average ratio of upvotes to downvotes per post.</p>
          <ol>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
            <li><a href='#'><strong>subreddit name</strong></a> with a ratio of <strong>x.xx</strong></li>
          </ol>
        </div>
      </div>
    </div> <!-- end main content row 2 -->

    <div class="row bigwidth"> <!-- main content row 3 -->
     <div class="small-4 columns">
      <div class="stat-box">
        <h3>Average comments-to-votes ratio</h3>
        Insert a chart here
      </div>
     </div>
     <div class="small-8 columns">
      <div class="stat-box">
        <h3>Go ahead and give it a try!</h3>
        <p>Here is some copy to make it sound more impressive than it actually is. I guess describe it too.</p>
      </div>
    </div> <!-- end main content row 3 -->


    <!-- End Content -->
 
 
    <!-- Footer -->
 
      <footer class="row">
        <div class="large-12 columns"><hr>
            <div class="row">
 
              <div class="large-8 small-12 columns">
                  <p>* or most hated. /r/___, I'm looking at you.</p>
                  <p>Made for Introduction to Database Systems, CPSC 473 Spring 2014, Yale University</p>
              </div>

              <div class='large-6 columns'>
                <p></p>
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