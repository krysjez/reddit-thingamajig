--Kevin Liu
--these are some queries that we can use to get the data we need from the database, and display on the website

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
;

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
;



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
;

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
;

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

-------------------------------------------------------------------------------------------
--Average of triggerhappiness, niceness, enthusiasm, and profanity across all subreddits---
-------------------------------------------------------------------------------------------

--Average trigger-happiness of all subreddits, including ones with fewer than 100 subscribers
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
;

--Average niceness of all subreddits, including ones with fewer than 100 subscribers
select avg("Niceness") as "AvgNiceness"
from
(
	select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
	from "Submissions"
	group by "SubredditName"
) as t2

--Average enthusiasm of all subreddits, including ones with fewer than 100 subscribers.
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
;

--Average profanity score across all subreddits, including ones with fewer than 100 subscribers.
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
;

------------------------------------------------------------------------
--SQL queries specific to users, rather than subreddits-----------------
------------------------------------------------------------------------


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
	)
	where "CommentCount" >= 10
) as t2
order by "AvgProfanityScore" desc
;

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
	)
	where "CommentCount" >= 10
) as t2
order by "AvgEnthusiasmScore" desc
;

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
	)
	where "SubmissionCount" >= 5
) as t2
order by "UserSubmissionPopularity" desc
;

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
	)
	where "SubmissionCount" >= 5
) as t2
order by "UserSubmissionPopularity" asc
;

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
	)
	where "CommentCount" >= 10
) as t2
order by "UserPopularity" desc
;

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
	)
	where "CommentCount" >= 10
) as t2
order by "UserPopularity" asc


-----------------------------------------------------------------
--If a user asks for a specific subreddit------------------------
-----------------------------------------------------------------

--Profanity of that subreddit
select "SubredditName", avg("ProfanityScore") as "AvgProfanityScore"
from 
(
	select "Comments"."ProfanityScore" as "ProfanityScore", "Submissions"."SubredditName" as "SubredditName"
	from "Comments" join "Submissions" using ("SubmissionID")
	where "SubredditName" = --INSERT HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTIONS
) as t3
group by "SubredditName"
;

--Enthusiasm of that subreddit
select "SubredditName", avg("EnthusiasmScore") as "AvgEnthusiasmScore"
from 
(
	select "Comments"."EnthusiasmScore" as "EnthusiasmScore", "Submissions"."SubredditName" as "SubredditName"
	from "Comments" join "Submissions" using ("SubmissionID")
	where "SubredditName" = --INSERT HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTIONS
) as t3
group by "SubredditName"
;

--Trigger-happiness of that subreddit
select "SubredditName", "AvgVotes" / ("Subscribers"+1) as "TriggerHappiness"
from
(
	select "SubredditName", avg("Upvotes"+"Downvotes") as "AvgVotes"
	from "Submissions"
	where "SubredditName" = --INSERT HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTIONS
	group by "SubredditName"
) as t1
natural join
(
	select "SubredditName", "Subscribers"
	from "Subreddits"
) as t2
;

--"Niceness" of upvotes out of total votes
select "SubredditName", "Niceness"
from
(
	select "SubredditName", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "Niceness"
	from "Submissions"
	where "SubredditName" = --INSERT HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTIONS
	group by "SubredditName"
) as t1
;

---------------------------------------------------------------
--If someone asks about a specific reddit user-----------------
---------------------------------------------------------------

--User profanity, based on their comments. 
select "Username", "AvgProfanityScore"
from
(
	select "Username", avg("ProfanityScore") as "AvgProfanityScore"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	where "Username" = --INSERT USERNAME HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTION
	group by "Username"
) as t1
;

--User enthusiasm
select "Username", "AvgEnthusiasmScore"
from
(
	select "Username", avg("EnthusiasmScore") as "AvgEnthusiasmScore"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	where "Username" = --INSERT USERNAME HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTION
	group by "Username"
) as t1
;


--How highly voted a user is, based on their submissions. 
select "Username", "UserSubmissionPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserSubmissionPopularity"
	from ("Users" natural join "User_submitted") join "Submissions" using ("SubmissionID")
	where "Username" = --INSERT USERNAME HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTION
	group by "Username"
) as t1
;

--how highly voted a user is, based on their comments
select "Username", "UserPopularity"
from
(
	select "Username", avg("Upvotes"::float/("Downvotes"+"Upvotes"+1)) as "UserPopularity"
	from ("Users" natural join "User_commented") join "Comments" using ("CommentID")
	where "Username" = --INSERT USERNAME HERE BUT MAKE SURE TO SANITIZE INPUT TO PREVENT INJECTION
	group by "Username"
) as t1
;