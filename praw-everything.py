# This is supposed to be the one crawler to rule them all; please don't
# modify unless you are sure of it
# To be integrated with Kevin's functions

import praw
import re
import datetime
import pg8000

user_agent = ("Thingamajig437/experimental by augusthex krysjez and versere")
r = praw.Reddit(user_agent=user_agent)
#r.login()

conn = pg8000.connect(user="michaelnestler", password="testing54321", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")
conn.autocommit = True
cursor = conn.cursor()

MAX_SUBMISSIONS = 500 # Limit for how many recent submissions to look at
MAX_COMMENTS = 500
# Check reddit messages later to see if /r/redditdev replied

# TODO: Store the subreddit name from PHP call as SubredditName
SubredditName="russia"
# Need to make this actually the subreddit the user wants
subreddit = r.get_subreddit(SubredditName)

def upsert_to_subreddits(subredditname, subscribers):
	sqlstr1 = "update public.\"Subreddits\" set \"Subscribers\" = "+str(subscribers)+", \"TimeRecorded\" = (select now()) where \"SubredditName\" = '"+subredditname+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Subreddits\" (\"SubredditName\", \"Subscribers\", \"TimeRecorded\") select '"+subredditname+"', "+str(subscribers)+", now() where not exists (select 1 from public.\"Subreddits\" where \"SubredditName\" = '"+subredditname+"');"
	cursor.execute(sqlstr2)

def upsert_to_submissions(submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname):
	sqlstr1 = "update public.\"Submissions\" set \"Permalink\" = '"+permalink+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"Title\" = '"+title+"', \"LinkTo\" = '"+linkto+"', \"SelfText\" = '"+selftext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"SubredditName\" = '"+subredditname+"', \"TimeRecorded\" = (select now()) where \"SubmissionID\" = '"+submissionid+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Submissions\" (\"SubmissionID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"Title\", \"LinkTo\", \"SelfText\", \"TimeSubmitted\", \"SubredditName\", \"TimeRecorded\") select '"+submissionid+"', '"+permalink+"', "+str(upvotes)+", "+str(downvotes)+", '"+title+"', '"+linkto+"', '"+selftext+"', to_timestamp("+str(timesubmitted)+"), '"+subredditname+"', now() where not exists (select 1 from public.\"Submissions\" where \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute(sqlstr2)

def upsert_to_comments(commentid, permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore):
	sqlstr1 = "update public.\"Comments\" set \"Permalink\" = '"+permalink+"', \"SubmissionID\" = '"+submissionid+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"CommentText\" = '"+commenttext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"TimeRecorded\" = (select now()), \"EnthusiasmScore\" = "+str(enthusiasmscore)+", \"ProfanityScore\" = "+str(profanityscore)+" where \"CommentID\" = '"+commentid+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Comments\" (\"CommentID\", \"Permalink\", \"SubmissionID\", \"Upvotes\", \"Downvotes\", \"CommentText\", \"TimeSubmitted\", \"TimeRecorded\", \"EnthusiasmScore\", \"ProfanityScore\") select '"+commentid+"', '"+permalink+"', '"+submissionid+"', "+str(upvotes)+", "+str(downvotes)+", '"+commenttext+"', to_timestamp("+str(timesubmitted)+"), now(), "+str(enthusiasmscore)+", "+str(profanityscore)+" where not exists (select 1 from public.\"Comments\" where \"CommentID\" = '"+commentid+"');"
	cursor.execute(sqlstr2)

def upsert_to_users(username, linkkarma, commentkarma, timejoined):
	sqlstr1 = "update public.\"Users\" set \"LinkKarma\" = " +str(linkkarma)+ ", \"CommentKarma\" = " +str(commentkarma)+ ", \"TimeJoined\" = to_timestamp("+str(timejoined)+"), \"TimeRecorded\" = (select now()) where \"Username\" = '"+username+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select '"+username+"', "+str(linkkarma)+", "+str(commentkarma)+", to_timestamp("+str(timejoined)+"), now() where not exists (select 1 from public.\"Users\" where \"Username\" = '"+username+"');"
	cursor.execute(sqlstr2)

def insert_to_user_submitted(username, submissionid):
	sqlstr1 = "insert into public.\"User_submitted\" (\"Username\", \"SubmissionID\") select '"+username+"', '"+submissionid+"' where not exists (select 1 from public.\"User_submitted\" where \"Username\" = '"+username+"' and \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute(sqlstr1)

def insert_to_user_commented(username, commentid):
	sqlstr1 = "insert into public.\"User_commented\" (\"Username\", \"CommentID\") select '"+username+"', '"+commentid+"' where not exists (select 1 from public.\"User_commented\" where \"Username\" = '"+username+"' and \"CommentID\" = '"+commentid+"');"
	cursor.execute(sqlstr1)

# Attributes for table Subreddits
upsert_to_subreddits(subreddit.display_name, subreddit.subscribers)

badWords = ['idiot', 'stupid', 'moron', 'asshole', 'arsehold', 'bastard', 'bitch', 'clusterfuck', 'cock', 'cocks', 'cunt', 'dick', 
'faggot', 'fuck', 'goatcx', 'goatse', 'shit', 'damn', 'crap', 'piss', 'pussy', 'fag', 'slut', 'damn']
# Feel free to add to this list if you think of more...

# Attributes for table Submissions
for submission in subreddit.get_new(limit=MAX_SUBMISSIONS):
	upsert_to_submissions(submission.id, submission.permalink, submission.ups, submission.downs, submission.title, submission.url, submission.selftext, submission.created_utc, subreddit.display_name)
	# User who posted the submission
	user = r.get_redditor(submission.author)
	upsert_to_users(redditor.name, redditor.link_karma, redditor.comment_karma, redditor.created_utc)
	insert_to_user_submitted(redditor.name, submission.id) # Create user_submitted entry
	enthu = 0
	lower = 0
	# Attributes for table Comments
	#submission.replace_more_comments(limit=None, threshold=0)
	flat_comments = praw.helpers.flatten_tree(submission.comments)
	for comment in flat_comments:
		badWordCount = 0
		wordCount = 0
		CommentText = comment.body
		if not (isinstance(comment, praw.objects.MoreComments)) and any(string in comment.body.lower() for string in badWords):
    		insultingPosts = insultingPosts + 1
		enthu = CommentText.count('?') + CommentText.count('!') + sum(x.isupper() for x in CommentText)
		lower = sum(x.islower() for x in CommentText)
		EnthusiasmScore = (enthu+1)/float(lower+1)
		for w in re.findall(r"\w+", CommentText):
			if w in badWords:
    			badWordCount+=1
    		wordCount+=1
		ProfanityScore = (badWordCount+1)/float(wordCount+1)
		upsert_to_comments(comment.id, comment.permalink, comment._submission.id, comment.ups, comment.downs, comment.body, comment.created_utc, EnthusiasmScore, ProfanityScore)
		# User who posted the comment
		user = r.get_redditor(comment.author)
		upsert_to_users(redditor.name, redditor.link_karma, redditor.comment_karma, redditor.created_utc)
		insert_to_user_commented(redditor.name, comment.id) # Create user_commented entry

