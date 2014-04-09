import pg8000

#jessica's username is jessicayang, and her password is testing09876
conn = pg8000.connect(user="michaelnestler", password="testing54321", database="thingamajig", host="cs437dbinstance.cpa1yidpzcc3.us-east-1.rds.amazonaws.com")
#note it is probable that when you try to run this python script, it fails for you because your IP address is not in the AWS security group, which defines which ip addresses can access the database from outside. if this happens to you, let me (kevin) know. i need to figure out the range of possible ip addresses we will be accessing the database from. i'm not sure if i want to let all ip addresses be able to access. if i do that, outsiders still wouldn't be able to log in though.

cursor = conn.cursor()
cursor.execute("select * from public.test;")
results = cursor.fetchall()

print(results)