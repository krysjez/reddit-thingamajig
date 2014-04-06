# count number of occurrences of comments containing "idiot", "stupid", "moron", relative to number of comments, in a particular thread

import praw
user_agent = ("Thingamajig437/experimental by augusthex")
r = praw.Reddit(user_agent=user_agent)

#r.login()

"""
subreddit = r.get_subreddit('worldnews')
for submission in subreddit.get_hot(limit=10):
    # for testing can use submission id '22c5m8' (the current top one)
    #submission.title
    #submission.num_comments
"""
submission = r.get_submission(submission_id='22c5m8')
#submission.replace_more_comments(limit=None, threshold=0)
flat_comments = praw.helpers.flatten_tree(submission.comments)
# On comment-parsing doc page, there is a way to get comments from whole subreddit or from r/all!

insultWords = ['idiot', 'stupid', 'moron']
insultingPosts = 0

for comment in flat_comments:
    if not (isinstance(comment, praw.objects.MoreComments)) and any(string in comment.body.lower() for string in insultWords):
        insultingPosts = insultingPosts + 1
fraction = insultingPosts / submission.num_comments
print(str(fraction*100) + " percent of this submission's top comments are possibly insulting.")