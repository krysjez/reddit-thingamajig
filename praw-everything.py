# This is supposed to be the one crawler to rule them all; please don't
# modify unless you are sure of it
# To be integrated with Kevin's functions

import praw
import re
import datetime
import pg8000
import pdb

user_agent = ("Thingamajig437/experimental by augusthex krysjez and versere")
r = praw.Reddit(user_agent=user_agent)
#r.login()

conn = pg8000.connect(user="michaelnestler", password="testing54321", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")
conn.autocommit = True
cursor = conn.cursor()

MAX_SUBMISSIONS = 50 # Limit for how many recent submissions to look at
MAX_COMMENTS = 500
# Check reddit messages later to see if /r/redditdev replied

# TODO: Store the subreddit name from PHP call as SubredditName
SubredditName="russia"
# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit(SubredditName)

#this next block is just some code Kevin was using to debug
#sid = 50505
#first_name = "Barack"
#last_name = "Obama"
#enthusiasm_score = 0.5
#cursor.execute("update public.\"test\" set \"Firstname\" = %s, \"Lastname\" = %s, \"TimeRecorded\" = (select now()), \"EnthusiasmScore\" = %s where \"StudentID\" = %s;", (first_name, last_name, enthusiasm_score, sid))
#cursor.execute("insert into public.\"test\" (\"StudentID\", \"Firstname\", \"Lastname\", \"TimeRecorded\", \"EnthusiasmScore\") select %s, %s, %s, now(), %s where not exists (select 1 from public.\"test\" where \"StudentID\" = %s);", (sid, first_name, last_name, enthusiasm_score, sid))

def upsert_to_users(username, linkkarma, commentkarma, timejoined):
	cursor.execute("update public.\"Users\" set \"LinkKarma\" = %s, \"CommentKarma\" = %s, \"TimeJoined\" = to_timestamp(%s), \"TimeRecorded\" = (select now()) where \"Username\" = %s;", (linkkarma, commentkarma, timejoined, str(username)))
	cursor.execute("insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select %s, %s, %s, to_timestamp(%s), now() where not exists (select 1 from public.\"Users\" where \"Username\" = %s);", (str(username), linkkarma, commentkarma, timejoined, str(username)))

def upsert_to_comments(commentid, permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore):
	cursor.execute("update public.\"Comments\" set \"Permalink\" = %s, \"SubmissionID\" = %s, \"Upvotes\" = %s, \"Downvotes\" = %s, \"CommentText\" = %s, \"TimeSubmitted\" = to_timestamp(%s), \"TimeRecorded\" = (select now()), \"EnthusiasmScore\" = %s, \"ProfanityScore\" = %s where \"CommentID\" = %s;", (permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore, commentid))
	cursor.execute("insert into public.\"Comments\" (\"CommentID\", \"Permalink\", \"SubmissionID\", \"Upvotes\", \"Downvotes\", \"CommentText\", \"TimeSubmitted\", \"TimeRecorded\", \"EnthusiasmScore\", \"ProfanityScore\") select %s, %s, %s, %s, %s, %s, to_timestamp(%s), now(), %s, %s where not exists (select 1 from public.\"Comments\" where \"CommentID\" = %s);", (commentid, permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore, commentid))
	
def upsert_to_subreddits(subredditname, subscribers):
	#pdb.set_trace()
	cursor.execute("update public.\"Subreddits\" set \"Subscribers\" = %s, \"TimeRecorded\" = (select now()) where \"SubredditName\" = %s;", (subscribers, subredditname))
	#pdb.set_trace()
	cursor.execute("insert into public.\"Subreddits\" (\"SubredditName\", \"Subscribers\", \"TimeRecorded\") select %s, %s, now() where not exists (select 1 from public.\"Subreddits\" where \"SubredditName\" = %s);", (subredditname, subscribers, subredditname))

def upsert_to_submissions(submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname):
	cursor.execute("update public.\"Submissions\" set \"Permalink\" = %s, \"Upvotes\" = %s, \"Downvotes\" = %s, \"Title\" = %s, \"LinkTo\" = %s, \"SelfText\" = %s, \"TimeSubmitted\" = to_timestamp(%s), \"SubredditName\" = %s, \"TimeRecorded\" = (select now()) where \"SubmissionID\" = %s;", (permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname, submissionid))
	cursor.execute("insert into public.\"Submissions\" (\"SubmissionID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"Title\", \"LinkTo\", \"SelfText\", \"TimeSubmitted\", \"SubredditName\", \"TimeRecorded\") select %s, %s, %s, %s, %s, %s, %s, to_timestamp(%s), %s, now() where not exists (select 1 from public.\"Submissions\" where \"SubmissionID\" = %s);", (submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname, submissionid))

def insert_to_user_submitted(username, submissionid):
	cursor.execute("insert into public.\"User_submitted\" (\"Username\", \"SubmissionID\") select %s, %s where not exists (select 1 from public.\"User_submitted\" where \"Username\" = %s and \"SubmissionID\" = %s);", (str(username), submissionid, str(username), submissionid))
	
def insert_to_user_commented(username, commentid):
	cursor.execute("insert into public.\"User_commented\" (\"Username\", \"CommentID\") select %s, %s where not exists (select 1 from public.\"User_commented\" where \"Username\" = %s and \"CommentID\" = %s);", (str(username), commentid, str(username), commentid))
	
def insert_to_user_moderates(username, subredditname):
	cursor.execute("insert into public.\"User_moderates\" (\"Username\", \"SubredditName\") select %s, %s where not exists (select 1 from public.\"User_moderates\" where \"Username\" = %s and \"SubredditName\" = %s);", (str(username), subredditname, str(username), subredditname))
	
def cleanvote(vote):
	if (vote < 0):
		return 0
	return vote

# Attributes for table Subreddits
upsert_to_subreddits(subreddit.display_name, subreddit.subscribers)

#does the regex take care of plural forms of these words?
badWords = ['ass', 'asshole', 'arsehole', 'bastard', 'bitch', 'clusterfuck', 'cock', 'cocks', 'cocksucker', 'crap', 'cunt', 'damn', 'dick', 'dickhead', 'dickwad', 'dumbass', 'dumbshit', 'fag', 'fags', 'fagot', 'fagots', 'faggot', 'faggots', 'fuck', 'fucker', 'fucking', 'fucks', 'goatcx', 'goatse', 'goddamn', 'idiot', 'moron', 'motherfucker', 'nigga', 'niggas', 'nigger', 'niggers', 'piss', 'pussy', 'shit', 'slut', 'stupid', 'wanker']

# Attributes for table Submissions
for submission in subreddit.get_new(limit=MAX_SUBMISSIONS):
	upsert_to_submissions(submission.id, submission.permalink, cleanvote(submission.ups), cleanvote(submission.downs), submission.title, submission.url, submission.selftext, submission.created_utc, subreddit.display_name)
	# User who posted the submission
	user = r.get_redditor(submission.author)
	if not (str(user.name) == 'None'):
		upsert_to_users(user.name, user.link_karma, user.comment_karma, user.created_utc)
		insert_to_user_submitted(user.name, submission.id) # Create user_submitted entry
	enthu = 0
	lower = 0
	# Attributes for table Comments
	#submission.replace_more_comments(limit=None, threshold=0)
	flat_comments = praw.helpers.flatten_tree(submission.comments)
	for comment in flat_comments:
		if (isinstance(comment, praw.objects.MoreComments)):
			continue
		badWordCount = 0
		wordCount = 0
		CommentText = comment.body
		#if not (isinstance(comment, praw.objects.MoreComments)) and any(string in comment.body.lower() for string in badWords):
			#insultingPosts = insultingPosts + 1 #this doesn't seem to do anything. need to verify with jessica that it's ok to just remove this line.
		enthu = CommentText.count('?') + CommentText.count('!') + sum(x.isupper() for x in CommentText)
		lower = sum(x.islower() for x in CommentText)
		EnthusiasmScore = (enthu+1)/float(lower+1)
		for w in re.findall(r"\w+", CommentText):
			if w in badWords:
				badWordCount+=1
			wordCount+=1
		ProfanityScore = (badWordCount+1)/float(wordCount+1)
		upsert_to_comments(comment.id, comment.permalink, comment._submission.id, cleanvote(comment.ups), cleanvote(comment.downs), comment.body, comment.created_utc, EnthusiasmScore, ProfanityScore)
		# User who posted the comment
		user = r.get_redditor(comment.author)
		if not (str(user.name) == 'None'):
			upsert_to_users(user.name, user.link_karma, user.comment_karma, user.created_utc)
			insert_to_user_commented(user.name, comment.id) # Create user_commented entry

cursor.close()
conn.close()