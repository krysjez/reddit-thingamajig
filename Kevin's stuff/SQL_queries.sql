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
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by TriggerHappiness desc
;

--Most profane subreddits
--Gives the subreddits with the highest average profanity score in the submissions. Each submission has a profanity score, which is calculated from its comments. Also, cut off subreddits with fewer than 100 subscribers.
select "SubredditName", AvgProfanityScore
(
	select "SubredditName", avg(ProfanityScore) as AvgProfanityScore
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by AvgProfanityScore desc
;

--Most enthusiastic subreddits
--Gives subreddits that use a lot of uppercase characters and exclamation marks
select "SubredditName", AvgEnthusiasmScore
(
	select "SubredditName", avg(EnthusiasmScore) as AvgEnthusiasmScore
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by AvgEnthusiasmScore desc
;

--Nicest subreddits
--Subreddits with highest average ratio of upvotes to downvotes in its submissions
select "SubredditName", Niceness
from
(
	select "SubredditName", avg("Upvotes"/"Downvotes") as Niceness
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by Niceness desc
;

--Meanest subreddits
--Subreddits with the lowest average ratio of upvotes to downvotes in its submissions. Same query as the "Niceness" measure, just flip the order.
select "SubredditName", Niceness
from
(
	select "SubredditName", avg("Upvotes"/"Downvotes") as Niceness
	from "Submissions"
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName"
	from "Subreddits"
	where "Subscribers" >= 100
) as t2
order by Niceness asc