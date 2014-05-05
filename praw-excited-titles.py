# Counts CAPS/?!-to-lowercase ratio on a subreddit

import praw
user_agent = ("Thingamajig437/experimental by augusthex")
r = praw.Reddit(user_agent=user_agent)

MAX = 500

#r.login()

# TODO: Write the subreddit name to the database as a primary key

# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit('thingamajig437')

# This is currently set up to keep track of 
# stats across the ENTIRE subreddit; just move these into the
# inner loop if the DB is set up to have per-submission stats

total_comments = 0
total_up = 0
total_down = 0
punct = 0 # ? and !
caps = 0 # capital letters
lowercase = 0

# Only looks at the last MAX submissions
for submission in subreddit.get_new(limit=MAX):
	t = submission.title
	punct += t.count('?')
	punct += t.count('!')
	caps += sum(x.isupper() for x in t)
	lowercase += sum(x.islower() for x in t)
	total_comments += submission.num_comments
	total_up += submission.ups
	total_down += submission.downs


# TODO: Write attributes to the database