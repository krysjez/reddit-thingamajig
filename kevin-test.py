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
def insert_to_users(username, linkkarma, commentkarma, timejoined):
	sqlstr1 = "update public.\"Users\" set \"LinkKarma\" = " +str(linkkarma)+ ", \"CommentKarma\" = " +str(commentkarma)+ ", \"TimeJoined\" = to_timestamp("+str(timejoined)+"), \"TimeRecorded\" = (select now()) where \"Username\" = '"+username+"';"
	#print(sqlstr1)
	cursor.execute(sqlstr1)
	sqlstr2 = "insert into public.\"Users\" (\"Username\", \"LinkKarma\", \"CommentKarma\", \"TimeJoined\", \"TimeRecorded\") select '"+username+"', "+str(linkkarma)+", "+str(commentkarma)+", to_timestamp("+str(timejoined)+"), now() where not exists (select 1 from public.\"Users\" where \"Username\" = '"+username+"');"
	#print(sqlstr2)
	cursor.execute(sqlstr2)
#end of fn	

redditor = r.get_redditor("versere")
	
insert_to_users(redditor.name, redditor.link_karma, redditor.comment_karma, redditor.created_utc)