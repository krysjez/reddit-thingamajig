<?php
$dbconn = pg_connect("host=cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com dbname=thingamajig user=michaelnestler password=testing54321")
    or die('Could not connect: ' . pg_last_error());

// Performing SQL query
// PUT ACTUAL QUERY SQL STRING IN HERE. multiline strings start with <<<'EOD' and end with EOD;
$query = <<<'EOD'
select "SubredditName", AvgVotes / "Subscribers" as TriggerHappiness
from
(
	select "SubredditName", avg("Upvotes"+"Downvotes") as AvgVotes
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName", "Subscribers"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by TriggerHappiness desc
EOD;
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// print contents of $thresult (trigger happiness)
// get as php table
$table = pg_fetch_all($result);
var_dump($table);

// Free resultset
pg_free_result($result);



// Closing connection
pg_close($dbconn);
?>