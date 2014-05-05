# Counts total number of comments on a subreddit

import praw
user_agent = ("Thingamajig437/experimental by augusthex")
r = praw.Reddit(user_agent=user_agent)

MAX = 500

#r.login()

# TODO: Write the subreddit name to the database as a primary key

# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit('thingamajig437')

num_submissions = 0
total_comments = 0
# Only looks at the last MAX submissions
for submission in subreddit.get_new(limit=MAX):
	total_comments += submission.num_comments

# TODO: Write attribute total_comments to the database