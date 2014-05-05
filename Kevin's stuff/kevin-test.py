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
	
	
	
	
# http://stackoverflow.com/questions/1109061/insert-on-duplicate-update-in-postgresql

#username should be a string of length no greater than 20, linkkarma and commentkarma must be integers. timejoined should be passed in unix format, and the function will convert it to psql's timestamp format
def upsert_to_users(username, linkkarma, commentkarma, timejoined):
	sqlstr1 = "update public.\"Users\" set \"LinkKarma\" = " +str(linkkarma)+ ", \"CommentKarma\" = " +str(commentkarma)+ ", \"TimeJoined\" = to_timestamp("+str(timejoined)+"), \"TimeRecorded\" = (select now()) where \"Username\" = '"+username+"';"
	#print(sqlstr1)
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select '"+username+"', "+str(linkkarma)+", "+str(commentkarma)+", to_timestamp("+str(timejoined)+"), now() where not exists (select 1 from public.\"Users\" where \"Username\" = '"+username+"');"
	#print(sqlstr2)
	cursor.execute(sqlstr2)
#end of fn	

redditor = r.get_redditor("versere")
upsert_to_users(redditor.name, redditor.link_karma, redditor.comment_karma, redditor.created_utc)

def upsert_to_comments(commentid, permalink, upvotes, downvotes, commenttext, timesubmitted):
	sqlstr1 = "update public.\"Comments\" set \"Permalink\" = '"+permalink+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"CommentText\" = '"+commenttext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"TimeRecorded\" = (select now()) where \"CommentID\" = '"+commentid+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Comments\" (\"CommentID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"CommentText\", \"TimeSubmitted\", \"TimeRecorded\") select '"+commentid+"', '"+permalink+"', "+str(upvotes)+", "+str(downvotes)+", '"+commenttext+"', to_timestamp("+str(timesubmitted)+"), now() where not exists (select 1 from public.\"Comments\" where \"CommentID\" = '"+commentid+"');"
	cursor.execute(sqlstr2)
	
submission = r.get_submission(submission_id='21oxk0')
comment = submission.comments[1]
upsert_to_comments(comment.id, comment.permalink, comment.ups, comment.downs, comment.body, comment.created_utc)

#subredditname: a string. subscribers: integer >= 0
def upsert_to_subreddits(subredditname, subscribers):
	sqlstr1 = "update public.\"Subreddits\" set \"Subscribers\" = "+str(subscribers)+", \"TimeRecorded\" = (select now()) where \"SubredditName\" = '"+subredditname+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Subreddits\" (\"SubredditName\", \"Subscribers\", \"TimeRecorded\") select '"+subredditname+"', "+str(subscribers)+", now() where not exists (select 1 from public.\"Subreddits\" where \"SubredditName\" = '"+subredditname+"');"
	cursor.execute(sqlstr2)

	
subreddit = submission.subreddit	
upsert_to_subreddits(subreddit.display_name, subreddit.subscribers)


def upsert_to_submissions(submissionid, permalink, upvotes, downvotes, title, linkto, selftext, timesubmitted, subredditname):
	sqlstr1 = "update public.\"Submissions\" set \"Permalink\" = '"+permalink+"', \"Upvotes\" = "+str(upvotes)+", \"Downvotes\" = "+str(downvotes)+", \"Title\" = '"+title+"', \"LinkTo\" = '"+linkto+"', \"SelfText\" = '"+selftext+"', \"TimeSubmitted\" = to_timestamp("+str(timesubmitted)+"), \"SubredditName\" = '"+subredditname+"', \"TimeRecorded\" = (select now()) where \"SubmissionID\" = '"+submissionid+"';"
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Submissions\" (\"SubmissionID\", \"Permalink\", \"Upvotes\", \"Downvotes\", \"Title\", \"LinkTo\", \"SelfText\", \"TimeSubmitted\", \"SubredditName\", \"TimeRecorded\") select '"+submissionid+"', '"+permalink+"', "+str(upvotes)+", "+str(downvotes)+", '"+title+"', '"+linkto+"', '"+selftext+"', to_timestamp("+str(timesubmitted)+"), '"+subredditname+"', now() where not exists (select 1 from public.\"Submissions\" where \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute(sqlstr2)


upsert_to_submissions(submission.id, submission.permalink, submission.ups, submission.downs, submission.title, submission.url, submission.selftext, submission.created_utc, subreddit.display_name)
	


#here, i only insert a row if it doesn't exist already. no need to update anything
def insert_to_user_submitted(username, submissionid):
	sqlstr1 = "insert into public.\"User_submitted\" (\"Username\", \"SubmissionID\") select '"+username+"', '"+submissionid+"' where not exists (select 1 from public.\"User_submitted\" where \"Username\" = '"+username+"' and \"SubmissionID\" = '"+submissionid+"');"
	cursor.execute(sqlstr1)
	
def insert_to_user_commented(username, commentid):
	sqlstr1 = "insert into public.\"User_commented\" (\"Username\", \"CommentID\") select '"+username+"', '"+commentid+"' where not exists (select 1 from public.\"User_commented\" where \"Username\" = '"+username+"' and \"CommentID\" = '"+commentid+"');"
	cursor.execute(sqlstr1)
	
#it's possible for a user to be a subreddit moderator, then later no longer be a moderator. if we want to keep track of that, then each time we update a subreddit's moderators, we might want to just drop all the records for that subreddit in this table, and then re-insert
def insert_to_user_moderates(username, subredditname):
	sqlstr1 = "insert into public.\"User_moderates\" (\"Username\", \"SubredditName\") select '"+username+"', '"+subredditname+"' where not exists (select 1 from public.\"User_moderates\" where \"Username\" = '"+username+"' and \"SubredditName\" = '"+subredditname+"');"
	cursor.execute(sqlstr1)

#currently this is not implemented because we probably won't end up having a use for storing the tree structure. it is possible to extract parents and children from comments but it just takes a bit of effort	
#def insert_to_comment_tree_structure(childcommentid, parentcommentid):

#likewise, currently not implementing the insertion functions for the User_voted table since for most users, we can't see their vote history, which is private


