--Kevin Liu
--these are some queries that we can use to get the data we need from the database, and display on the website

--Most trigger-happy subreddits
--This will give the top subreddits based on the average total votes per post, normalized by the number of subscribers. We normalize by the number of subscribers because otherwise, we'll simply get the most active subreddits, like r/worldnews and r/politics. Also, we cut off subreddits that are too small, with fewer than 100 subscribers. 
 


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
;
 