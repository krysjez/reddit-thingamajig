#Kevin Liu
import praw
import pg8000

user_agent = ("Thingamajig437/experimental by /u/versere")
r = praw.Reddit(user_agent=user_agent)

conn = pg8000.connect(user="michaelnestler", password="testing54321", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")
conn.autocommit = True
cursor = conn.cursor()

#use select now(); to figure out the current time
#use select to_timestamp(); to convert from unix time to timestamp

cur_subreddit = 'thingamajig437'


#subreddit = r.get_subreddit(cur_subreddit)
#for submission in subreddit.get_hot(limit=None):
	#have to insert to submissions, users, user_submitted, comments, user_commented, subreddit
	#stub to be filled in
	
	
sid = 50505
first_name = "Barack"
last_name = "Obama"
enthusiasm_score = 0.5
cursor.execute("insert into \"test\" values (%s, %s, %s, null, %s)", (sid, first_name, last_name, enthusiasm_score))
	
# http://stackoverflow.com/questions/1109061/insert-on-duplicate-update-in-postgresql

#username should be a string of length no greater than 20, linkkarma and commentkarma must be integers. timejoined should be passed in unix format, and the function will convert it to psql's timestamp format
def upsert_to_users(username, linkkarma, commentkarma, timejoined):
	#sqlstr1 = "update public.\"Users\" set \"LinkKarma\" = " +str(linkkarma)+ ", \"CommentKarma\" = " +str(commentkarma)+ ", \"TimeJoined\" = to_timestamp("+str(timejoined)+"), \"TimeRecorded\" = (select now()) where \"Username\" = '"+str(username)+"';"
	cursor.execute("update public.\"Users\" set \"LinkKarma\" = %s, \"CommentKarma\" = %s, \"TimeJoined\" = to_timestamp(%s), \"TimeRecorded\" = (select now()) where \"Username\" = '%s';", (linkkarma, commentkarma, timejoined, str(username)))
	#sqlstr2 = "insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select '"+str(username)+"', "+str(linkkarma)+", "+str(commentkarma)+", to_timestamp("+str(timejoined)+"), now() where not exists (select 1 from public.\"Users\" where \"Username\" = '"+str(username)+"');"
	cursor.execute("insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select '%s', %s, %s, to_timestamp(%s), now() where not exists (select 1 from public.\"Users\" where \"Username\" = '%s');", (str(username), linkkarma, commentkarma, timejoined, str(username)))
#end of fn	

redditor = r.get_redditor("versere")
upsert_to_users(redditor.name, redditor.link_karma, redditor.comment_karma, redditor.created_utc)

def upsert_to_comments(commentid, permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore):
	#sqlstr1 = "update public.\"Comments\" set \"Permalink\" = '"+permalink+"', \"SubmissionID\" = '"+submissionid+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"CommentText\" = '"+commenttext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"TimeRecorded\" = (select now()), \"EnthusiasmScore\" = "+str(enthusiasmscore)+", \"ProfanityScore\" = "+str(profanityscore)+" where \"CommentID\" = '"+commentid+"';"
	cursor.execute("update public.\"Comments\" set \"Permalink\" = '%s', \"SubmissionID\" = '%s', \"Upvotes\" = %s, \"Downvotes\" = %s, \"CommentText\" = '%s', \"TimeSubmitted\" = to_timestamp(%s), \"TimeRecorded\" = (select now()), \"EnthusiasmScore\" = %s, \"ProfanityScore\" = %s where \"CommentID\" = '%s';", (permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore, commentid))
	#sqlstr2 = "insert into public.\"Comments\" (\"CommentID\", \"Permalink\", \"SubmissionID\", \"Upvotes\", \"Downvotes\", \"CommentText\", \"TimeSubmitted\", \"TimeRecorded\", \"EnthusiasmScore\", \"ProfanityScore\") select '"+commentid+"', '"+permalink+"', '"+submissionid+"', "+str(upvotes)+", "+str(downvotes)+", '"+commenttext+"', to_timestamp("+str(timesubmitted)+"), now(), "+str(enthusiasmscore)+", "+str(profanityscore)+" where not exists (select 1 from public.\"Comments\" where \"CommentID\" = '"+commentid+"');"
	cursor.execute("insert into public.\"Comments\" (\"CommentID\", \"Permalink\", \"SubmissionID\", \"Upvotes\", \"Downvotes\", \"CommentText\", \"TimeSubmitted\", \"TimeRecorded\", \"EnthusiasmScore\", \"ProfanityScore\") select '%s', '%s', '%s', %s, %s, '%s', to_timestamp(%s), now(), %s, %s where not exists (select 1 from public.\"Comments\" where \"CommentID\" = '%s');", (commentid, permalink, submissionid, upvotes, downvotes, commenttext, timesubmitted, enthusiasmscore, profanityscore, commentid))
	
submission = r.get_submission(submission_id='21oxk0')
comment = submission.comments[1]
upsert_to_comments(comment.id, comment.permalink, comment._submission.id, comment.ups, comment.downs, comment.body, comment.created_utc)

#subredditname: a string. subscribers: integer >= 0
def upsert_to_subreddits(subredditname, subscribers):
	#sqlstr1 = "update public.\"Subreddits\" set \"Subscribers\" = "+str(subscribers)+", \"TimeRecorded\" = (select now()) where \"SubredditName\" = '"+subredditname+"';"
	cursor.execute("update public.\"Subreddits\" set \"Subscribers\" = %s, \"TimeRecorded\" = (select now()) where \"SubredditName\" = '%s';", (subscribers, subredditname))
	#sqlstr2 = "insert into public.\"Subreddits\" (\"SubredditName\", \"Subscribers\", \"TimeRecorded\") select '"+subredditname+"', "+str(subscribers)+", now() where not exists (select 1 from public.\"Subreddits\" where \"SubredditName\" = '"+subredditname+"');"
	cursor.execute("insert into public.\"Subreddits\" (\"SubredditName\", \"Subscribers\", \"TimeRecorded\") select '%s', %s, now() where not exists (select 1 from public.\"Subreddits\" where \"SubredditName\" = '%s');", (subredditname, subscribers, subredditname))

	
subreddit = submission.subreddit	
upsert_to_subreddits(subreddit.display_name, subreddit.subscribers)


def upsert_to_submissions(submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname):
	#sqlstr1 = "update public.\"Submissions\" set \"Permalink\" = '"+permalink+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"Title\" = '"+title+"', \"LinkTo\" = '"+linkto+"', \"SelfText\" = '"+selftext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"SubredditName\" = '"+subredditname+"', \"TimeRecorded\" = (select now()) where \"SubmissionID\" = '"+submissionid+"';"
	cursor.execute("update public.\"Submissions\" set \"Permalink\" = '%s', \"Upvotes\" = %s, \"Downvotes\" = %s, \"Title\" = '%s', \"LinkTo\" = '%s', \"SelfText\" = '%s', \"TimeSubmitted\" = to_timestamp(%s), \"SubredditName\" = '%s', \"TimeRecorded\" = (select now()) where \"SubmissionID\" = '%s';", (permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname, submissionid))
	#sqlstr2 = "insert into public.\"Submissions\" (\"SubmissionID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"Title\", \"LinkTo\", \"SelfText\", \"TimeSubmitted\", \"SubredditName\", \"TimeRecorded\") select '"+submissionid+"', '"+permalink+"', "+str(upvotes)+", "+str(downvotes)+", '"+title+"', '"+linkto+"', '"+selftext+"', to_timestamp("+str(timesubmitted)+"), '"+subredditname+"', now() where not exists (select 1 from public.\"Submissions\" where \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute("insert into public.\"Submissions\" (\"SubmissionID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"Title\", \"LinkTo\", \"SelfText\", \"TimeSubmitted\", \"SubredditName\", \"TimeRecorded\") select '%s', '%s', %s, %s, '%s', '%s', '%s', to_timestamp(%s), '%s', now() where not exists (select 1 from public.\"Submissions\" where \"SubmissionID\" = '%s');", (submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname, submissionid))


upsert_to_submissions(submission.id, submission.permalink, submission.ups, submission.downs, submission.title, submission.url, submission.selftext, submission.created_utc, subreddit.display_name)
	

#here, i only insert a row if it doesn't exist already. no need to update anything
def insert_to_user_submitted(username, submissionid):
	#sqlstr1 = "insert into public.\"User_submitted\" (\"Username\", \"SubmissionID\") select '"+str(username)+"', '"+submissionid+"' where not exists (select 1 from public.\"User_submitted\" where \"Username\" = '"+str(username)+"' and \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute("insert into public.\"User_submitted\" (\"Username\", \"SubmissionID\") select '%s', '%s' where not exists (select 1 from public.\"User_submitted\" where \"Username\" = '%s' and \"SubmissionID\" = '%s');", (str(username), submissionid, str(username), submissionid))
	
def insert_to_user_commented(username, commentid):
	#sqlstr1 = "insert into public.\"User_commented\" (\"Username\", \"CommentID\") select '"+str(username)+"', '"+commentid+"' where not exists (select 1 from public.\"User_commented\" where \"Username\" = '"+str(username)+"' and \"CommentID\" = '"+commentid+"');"
	cursor.execute("insert into public.\"User_commented\" (\"Username\", \"CommentID\") select '%s', '%s' where not exists (select 1 from public.\"User_commented\" where \"Username\" = '%s' and \"CommentID\" = '%s');", (str(username), commentid, str(username), commentid))
	
#it's possible for a user to be a subreddit moderator, then later no longer be a moderator. if we want to keep track of that, then each time we update a subreddit's moderators, we might want to just drop all the records for that subreddit in this table, and then re-insert
def insert_to_user_moderates(username, subredditname):
	#sqlstr1 = "insert into public.\"User_moderates\" (\"Username\", \"SubredditName\") select '"+username+"', '"+subredditname+"' where not exists (select 1 from public.\"User_moderates\" where \"Username\" = '"+username+"' and \"SubredditName\" = '"+subredditname+"');"
	cursor.execute("insert into public.\"User_moderates\" (\"Username\", \"SubredditName\") select '%s', '%s' where not exists (select 1 from public.\"User_moderates\" where \"Username\" = '%s' and \"SubredditName\" = '%s');", (str(username), subredditname, str(username), subredditname))

#currently this is not implemented because we probably won't end up having a use for storing the tree structure. it is possible to extract parents and children from comments but it just takes a bit of effort	
#def insert_to_comment_tree_structure(childcommentid, parentcommentid):

#likewise, currently not implementing the insertion functions for the User_voted table since for most users, we can't see their vote history, which is private


