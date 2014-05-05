<!doctype html>
<html class="no-js" lang="en">
  <head>
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
          <form action="result.php" method="post">
              <input type='text' id='search-again-input' placeholder='<?php echo $_POST["subreddit-input"]; ?>' name="subreddit-input"></input>
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
        <h1>it's negative</h1>
        (dynamic descriptor based on ratio; can do with jquery)
        <p>
          <span class='huge upvote'>3 <i class='fi-arrow-up'></i></span>
          <span class='huge downvote'>18 <i class='fi-arrow-down'></i></span>
        </p>
        <p>average on other subreddits: xx.x</p>
      </div>
      <div class="small-4 columns">
        <h1>it's vocal</h1>
        <p>
          <span class='huge upvote'>3 <i class='fi-comment'></i></span> comments per post
        </p>
        <p>average on other subreddits: xx.x</p>
      </div>
      <div class="small-4 columns">
        <h1>it's quiet</h1>
        <p>
          <span class='huge upvote'>3 <i class='fi-comment'></i></span> CAPS to lowercase
        </p>
        <p>average on other subreddits: xx.x</p>
      </div>
    </div> <!-- end main content jumbo row -->

    <div class="row bigwidth"> <!-- main content row -->
      <div class='small-4 columns'>
          <h2>What this means</h2>
          <p>Wasdklfjasdkfjafjad</p>
          <img src='http://i.imgur.com/4VrgVJ5.jpg'>
          <p class='caption'>by /u/cartoonheroes</p>
      </div> <!-- end left side -->
      <div class='small-8 columns'> <!-- right side -->
        <div class='row'> <!-- sub row 1 -->
          <div class='small-6 columns'>
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
        <div class='row'> <!-- sub row 2 -->
          <div class='small-6 columns'>
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
      </div> <!-- end right side -->
    </div> <!-- end main content row 1 -->

    <div class="row"> <!-- main content row 2 -->
      <div class='small-12 columns'>
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
