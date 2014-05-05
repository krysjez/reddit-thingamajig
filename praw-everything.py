# This is supposed to be the one crawler to rule them all; please don't
# modify unless you are sure of it
# To be integrated with Kevin's functions

import praw
import datetime

user_agent = ("Thingamajig437/experimental by augusthex")
r = praw.Reddit(user_agent=user_agent)
#r.login()

MAX = 500 # Limit for how many recent submissions to look at
# Check reddit messages later to see if /r/redditdev replied

# TODO: Store the subreddit name from PHP call as SubredditName

# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit('thingamajig437')

# Attributes for table Subreddits
TimeRecorded = strftime("%Y-%m-%d %H:%M:%S")
Subscribers = subreddit.subscribers
#TODO: Write these to table

# Attributes for table Submissions
for submission in subreddit.get_new(limit=MAX):
	SubmissionID = submission.id
	Permalink = submission.permalink
	Title = submission.title
	LinkTo = submission.url
	Selftext = submission.selftext
	TimeSubmitted = datetime.datetime.fromtimestamp(int(submission.created_utc)).strftime('%Y-%m-%d %H:%M:%S')
	Upvotes = submission.ups
	Downvotes = submission.downs
	SubredditName = subreddit.name
	TimeRecorded = strftime("%Y-%m-%d %H:%M:%S")
	# TODO: Write these to the table Submissions now
	# Attributes for table Comments
		#submission.replace_more_comments(limit=None, threshold=0)
		flat_comments = praw.helpers.flatten_tree(submission.comments)
		for comment in flat_comments:
			CommentID = comment.id
			Permalink = comment.permalink
			SubmissionID = comment.submission.id
			Upvotes = comment.ups
			Downvotes = comment.downs
			CommentText = comment.body
			TimeSubmitted = datetime.datetime.fromtimestamp(int(comment.created_utc)).strftime('%Y-%m-%d %H:%M:%S')
			TimeRecorded = strftime("%Y-%m-%d %H:%M:%S")
			EnthusiasmScore = (CommentText.count('?') + CommentText.count('!') + sum(x.isupper() for x in CommentText) +1)/float(sum(x.islower() for x in CommentText) +1)
			# TODO: Write these to the table Comments now
