# Broad functionality (most important at top)

Most important at top. Stuff that requires back-end calculations/magic is *emphasized*. The rest can be done by front-end.
- *As a user I want to be able to submit a subreddit and get statistics on it*
- *As a user I want to be able to see how behavior in my subreddit compares to the average sub (that the service has information for)*
- *As a user I want to be able to log in to view more stats about myself, because I like hearing about myself*
- As a user I want to not be bored 
- As a moderator I want to be able to easily take action (e.g. messaging errant users, going to the mod panel etc.)
- As a user I want to be able to see what others have been looking at, to easily poke my nose in others' business
- As a user I want to be able to share the results easily with the people who need to see them the most
- As a user I want to be able to provide feedback on the service and contact the owners.
- As a user I want to be able to use this on mobile devices [this is pushed down lower for project timeline purposes, but keep it in mind while building]
- As a user I want to feel like I have done something useful by searching on this service

# Metrics 
## Required
- up:down ratio in entire sub
- comments:votes (and reverse) ratio in entire sub
- up:down ratio across all subs ever searched
- comments:votes ratio across all subs ever searched
- frequency of posts:frequency of comments in entire sub
- which recent posts deviate the most from usual behavior [need to define usual behavior]
- ratio of punctuation and CAPS to lowercase in post titles/comments
- mean/median/mode of karma for users of that subreddit [users being people who show up in our scraped data]

## When logged in
- How up/downvote-happy you have ever been
- How your trigger-happiness compares to other people who have used the service [we will store vote counts only, not associated with personally identifying data]
- comments:votes ratio

## Fun, easy extras
- number of searches ever made/page count LOL
- total karma belonging to all users who ever logged in
- basically fun extra crap

### Fun, hard extras
- Favorite searches (locally saved)

### Probably can't be implemented, here for archival purposes
- most upvote-happy users
- most downvote-happy users
- comments:votes ratio for these users
