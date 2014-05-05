# This is supposed to be the one script to rule them all; please don't
# modify unless you are sure of it
# This script should be called once for each subreddit-info-gathering 
# request

import praw
user_agent = ("Thingamajig437/experimental by augusthex")
r = praw.Reddit(user_agent=user_agent)

MAX = 500

#r.login()

# TODO: Write the subreddit name to the database as a primary key

# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit('thingamajig437')

# Only looks at the last MAX submissions
for submission in subreddit.get_new(limit=MAX):
	special = 0
	t = submission.title
	special += t.count('?')
	special += t.count('!')
	caps = sum(x.isupper() for x in t)
	lowercase = sum(x.islower() for x in t)
	# TODO: Write attributes to the database