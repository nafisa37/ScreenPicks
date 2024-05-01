<!-----



Conversion time: 1.142 seconds.


Using this HTML file:

1. Paste this output into your source file.
2. See the notes and action items below regarding this conversion run.
3. Check the rendered output (headings, lists, code blocks, tables) for proper
   formatting and use a linkchecker before you publish this page.

Conversion notes:

* Docs to Markdown version 1.0β36
* Tue Apr 30 2024 17:03:58 GMT-0700 (PDT)
* Source doc: Nafisa Ahmed - IT202  Project Proposal
* This is a partial selection. Check to make sure intra-doc links work.
----->


<p>
IT 202 Project Proposal
</p>
<p>
<strong>Project Name: Movies API Project</strong>
</p>
<p>
<strong>Project Summary: This project will primarily use data pull from an API that contains information about movies. It then allows users to create a watchlist with the movies of their choice. Users can create accounts on this website, create/edit watchlists and movie information, and have different roles that give them access to different pages and information.</strong>
</p>
<p>
<strong>Github Link:</strong>
</p>
<ul>

<li><strong>Milestone 1: <a href="https://github.com/nafisa37/na569-it202-008/pull/43">https://github.com/nafisa37/na569-it202-008/pull/43</a></strong>

<li><strong>Milestone 2: <a href="https://github.com/nafisa37/na569-it202-008/pull/63">https://github.com/nafisa37/na569-it202-008/pull/63</a></strong>

<li><strong>Milestone 3: <a href="https://github.com/nafisa37/na569-it202-008/pull/74">https://github.com/nafisa37/na569-it202-008/pull/74</a></strong>
</li>
</ul>
<p>
<strong>Website Link: <a href="https://na569-prod-74aadc581478.herokuapp.com/project/login.php">https://na569-prod-74aadc581478.herokuapp.com/project/login.php</a></strong>
</p>
<p>
<strong>API Link: <a href="https://rapidapi.com/gox-ai-gox-ai-default/api/ott-details">https://rapidapi.com/gox-ai-gox-ai-default/api/ott-details</a></strong>
</p>
<p>
<strong>Video Link: <a href="https://www.youtube.com/watch?v=jhZ8DJiK_xQ">https://www.youtube.com/watch?v=jhZ8DJiK_xQ</a></strong>
</p>
<p>
<strong>Your Name: Nafisa Ahmed</strong>
</p>
<p>
<strong>Important:</strong> Below is a list of generic core features for everyone’s project. Your desired scope/design may be slightly different but each of these requirements must be met to the best of your ability, even if it’s just to add an extraneous page to accomplish the feature. The goal of this proposal is to try to fit as many desired project types as possible (which may not be perfectly possible). Each feature is intended to be relatively generic and flexible and can be a little unclear in some cases in how it can fit your project; reach out to me at the earliest if there’s any confusion and I can help offer suggestions/guidelines. Due keep in mind your project scope/design/goal as you work towards these features.
</p>
<p>
<strong>Milestone Features:</strong>
</p>
<p>
<strong>	Milestone 1 (9):</strong>
</p>
<ul>

<li>User will be able to register a new account 
<ul>
 
<li>Form Fields  
<ul>
  
<li>Username, email, password, confirm password(other fields optional)
  
<li>Email is required and must be validated
  
<li>Username is required
  
<li>Confirm password’s match
</li>  
</ul>
 
<li><span style="text-decoration:underline;">Users</span> Table  
<ul>
  
<li>Id, username, email, password (60 characters), created, modified
</li>  
</ul>
 
<li>Password must be hashed (plain text passwords will lose points)
 
<li>Email should be unique
 
<li>Username should be unique
 
<li>System should let user know if username or email is taken and allow the user to correct the error without wiping/clearing the form  
<ul>
  
<li>The only fields that may be cleared are the password fields
</li>  
</ul>
</li>  
</ul>

<li>User will be able to login to their account (given they enter the correct credentials) 
<ul>
 
<li>Form  
<ul>
  
<li>User can login with <strong>email </strong>or <strong>username</strong>   
<ul>
   
<li>This can be done as a single field or as two separate fields
</li>   
</ul>
  
<li>Password is required
</li>  
</ul>
 
<li>User should see friendly error messages when an account either doesn’t exist or if passwords don’t match
 
<li>Logging in should fetch the user’s details (and roles) and save them into the session.
 
<li>User will be directed to a landing page upon login  
<ul>
  
<li>This is a protected page (non-logged in users shouldn’t have access)
  
<li>This can be home, profile, a dashboard, etc
</li>  
</ul>
</li>  
</ul>

<li>User will be able to logout 
<ul>
 
<li>Logging out will redirect to login page
 
<li>User should see a message that they’ve successfully logged out
 
<li>Session should be destroyed (so the back button doesn’t allow them access back in)
</li> 
</ul>

<li>Basic security rules implemented 
<ul>
 
<li>Authentication:  
<ul>
  
<li>Function to check if user is logged in
  
<li>Function should be called on appropriate pages that only allow logged in users
</li>  
</ul>
 
<li>Roles/Authorization:  
<ul>
  
<li>Have a roles table (see below)
</li>  
</ul>
</li>  
</ul>

<li>Basic Roles implemented 
<ul>
 
<li>Have a <span style="text-decoration:underline;">Roles</span> table	(id, name, description, is_active, modified, created)
 
<li>Have a <span style="text-decoration:underline;">User Roles</span> table (id, user_id, role_id, is_active, created, modified)
 
<li>Include a function to check if a user has a specific role
</li> 
</ul>

<li>Site should have basic styles/theme applied; everything should be styled 
<ul>
 
<li>I.e., forms/input, navigation bar, etc
</li> 
</ul>

<li>Any output messages/errors should be “user friendly” 
<ul>
 
<li>Any technical errors or debug output displayed will result in a loss of points
</li> 
</ul>

<li>User will be able to see their profile 
<ul>
 
<li>Email, username, etc
</li> 
</ul>

<li>User will be able to edit their profile 
<ul>
 
<li>Changing username/email should properly check to see if it’s available before allowing the change
 
<li>Any other fields should be properly validated
 
<li>Allow password reset (only if the existing correct password is provided)  
<ul>
  
<li>Hint: logic for the password check would be similar to login
</li>  
</ul>
</li>  
</ul>
</li>  
</ul>
<p>
	<strong>Milestone 2 (7):</strong>
</p>
<ul>

<li>Define the appropriate table or tables for your API 
<ul>
 
<li>Note: It should have the 3 core columns we’ll always be using (id, created, modified) plus additional columns for the incoming API data  
<ul>
  
<li>It’s not a valid design decision to use just one column to store the API result as text/JSON/etc, you actually need to do some data mapping
</li>  
</ul>
</li>  
</ul>

<li>Data Creation Page 
<ul>
 
<li>Form should have the correct data types for each property being requested
 
<li>Form should have correct validation for each form field
 
<li>Consider how duplicate content is handled
 
<li>Successful creation should have an appropriate message shown
 
<li>Any errors should have user friendly messages shown
 
<li>Manually created entities should be in the same table as the one(s) that is/are populated from the API data  
<ul>
  
<li>Have some column indicator that’s used to distinguish between manual and API data (<strong>Hint:</strong> Typically the API will have its own identifier that you’d record as a separate column not directly related to the table’s own <strong>id</strong> column)
</li>  
</ul>
 
<li>Consider which roles will have access to this form (i.e., is it admin only or can anyone make an entity?)
</li> 
</ul>

<li>Data List Page (many items) 
<ul>
 
<li>Have a page that lists the primary non-user entities of your application  
<ul>
  
<li>Both API generated entities and custom entities must be shown together
</li>  
</ul>
 
<li>Consider which roles have access to this page and/or if a user must be logged in to view this page
 
<li>The list page should have logical options for filtering/sorting the results  
<ul>
  
<li>The filter/sort should include a field for the user to specify a limit of records between 1 and 100   
<ul>
   
<li>The server-side should ensure the chosen value is within range or default to 10
</li>   
</ul>
  
<li>A filter with no matching records should clearly mention “No results available” or a similar message of your choice
</li>  
</ul>
 
<li>Each list item should show a summary of the data you want to show in this view (i.e., likely it won’t be the entire entity)
 
<li>Each list item should contain the following links  
<ul>
  
<li>A link to a single view (i.e., a details page)
  
<li>A link to delete (this may be an admin-only thing, but it should be present for the respective role)
  
<li>A link to edit (this may be an admin-only thing, but it should be present for the respective role)
</li>  
</ul>
 
<li>Design/Style is your choice but must be applied (i.e., can’t just leave it as plaintext dump to the screen)
</li> 
</ul>

<li>View Data Details Page (single item) 
<ul>
 
<li>Entity should be fetched by id passed through query parameters in the URL
 
<li>Invalid id should be redirected back to the list page with an appropriate message
 
<li>Design/Style is your choice but must be applied (i.e., can’t just leave it as plaintext dump to the screen)
 
<li>Data shown should be more detailed than that of the list/summary view
 
<li>This page should contain the following links  
<ul>
  
<li>A link to edit the single entity (this may be an admin-only thing, but it should be present for the respective role)
  
<li>A link to delete the single entity (this may be an admin-only thing, but it should be present for the respective role)
</li>  
</ul>
</li>  
</ul>

<li>Edit Data Page 
<ul>
 
<li>Entity should be fetch by id passed through query parameters in the URL
 
<li>Invalid id should be redirected back to the list page with an appropriate message
 
<li>Form should be similar to the Create page (<strong>Note</strong>: not all properties should be editable like id, created, modified)
 
<li>Form should prefill with the existing entity’s information for the respective fields
 
<li>Form should have the correct data types for each property being requested
 
<li>Form should have correct validation for each form field
 
<li>Successful update should have an appropriate message shown  
<ul>
  
<li>The updated data should be shown in the form
</li>  
</ul>
 
<li>Any errors should have user friendly messages shown
</li> 
</ul>

<li>Delete Handling 
<ul>
 
<li>Entity should be fetched by id passed through query parameters in the URL
 
<li>Invalid id should be redirected back to the list page with an appropriate message
 
<li>Handle any necessary roles/permissions (i.e., it’s not likely that anyone can delete any entity they choose)  
<ul>
  
<li>Examples:   
<ul>
   
<li>Can only delete entities they created
   
<li>Can only be done by admin
   
<li>Can only delete entities associated with themselves
</li>   
</ul>
</li>   
</ul>
 
<li>Consider if the deletion of the entity will be a hard delete or a soft delete
 
<li>On successful deletion redirect to the previous page the delete was triggered from with a user friendly success message
 
<li>If deleting from a list page with an active filter/sort applied, the redirect should apply the same filter/sort so the results are the same except without this item showing
</li> 
</ul>

<li>API Handling 
<ul>
 
<li>API data will be fetched from the server-side
 
<li>Consider how you’ll transform the data from the API to your table holding the API data  
<ul>
  
<li>Will you be using all the data or just a subset?
</li>  
</ul>
 
<li>Consider how duplicate entries are handled (even if unlikely as custom data shouldn’t be impacted)
 
<li>Consider how updates to existing entities are handled  
<ul>
  
<li>How will you handle manual updates to API data?
</li>  
</ul>
 
<li>Consider how your application will trigger this data fetch  
<ul>
  
<li>Examples:   
<ul>
   
<li>Periodic
   
<li>User triggered via some action
   
<li>Admin triggered via an explicitly click of a button or similar
</li>   
</ul>
</li>   
</ul>
</li>   
</ul>
</li>   
</ul>
<p>
	<strong>Milestone 3 (6):</strong>
</p>
<ul>

<li>API Data Association 
<ul>
 
<li>Consider how your API data will be associated with a user  
<ul>
  
<li>Examples:   
<ul>
   
<li>List of favorites
   
<li>Recipe Builder
   
<li>WatchList
   
<li>Purchases
   
<li>Assignment
   
<li>Etc
</li>   
</ul>
</li>   
</ul>
 
<li>Handling Data Changes  
<ul>
  
<li>When the entity is updated either manually or via the API how is the association affected?   
<ul>
   
<li>Examples:    
<ul>
    
<li>The user sees the old version of the data
    
<li>The user sees the new version of the data
    
<li>The user needs to re-associate the data
    
<li>Etc
</li>    
</ul>
</li>    
</ul>
</li>    
</ul>
</li>    
</ul>

<li>Handle the association of data to a user 
<ul>
 
<li>(Option 1) Update the necessary Pages to include the ability to associate the data with a User  
<ul>
  
<li><strong>Note:</strong> This is like favorites, shopping cart, wishlist, etc
</li>  
</ul>
 
<li>(Option 2) Create a page where the user will have data associated with them by someone else  
<ul>
  
<li> (i.e., in the case of a higher role assigning the association like the UserRoles/Roles logic)
  
<li><strong>Note: </strong>This is usually when the user can’t control their own data like our Roles system
</li>  
</ul>
</li>  
</ul>

<li>Logged in user’s associated entities page 
<ul>
 
<li>Each line item should summarize relevant information
 
<li>Each line item should include the following links  
<ul>
  
<li>A link to a single view (i.e., a details page)
  
<li>A link to delete (this may be an admin-only thing, but it should be present for the respective role)   
<ul>
   
<li><strong>Note</strong>: This is to delete the relationship and not the specific entity or user
</li>   
</ul>
</li>   
</ul>
 
<li>Outside of the list section there should be a link/button to remove all associations to this user  
<ul>
  
<li>This may be an admin only thing, but it should still be present for the respective role
</li>  
</ul>
 
<li>The heading of the page should show the total count of items associated to this user
 
<li>The heading of this page should include the total number of items shown on the page  
<ul>
  
<li>This value should be changed based on any applied filter
</li>  
</ul>
 
<li>The list page should have logical options for filtering/sorting the results  
<ul>
  
<li>The filter/sort should include a field for the user to specify a limit of records between 1 and 100   
<ul>
   
<li>The server-side should ensure the chosen value is within range or default to 10
</li>   
</ul>
  
<li>A filter with no matching records should clearly mention “No results available” or a similar message of your choice
</li>  
</ul>
</li>  
</ul>

<li>All Users association page 
<ul>
 
<li><strong>Note: </strong>This will likely be an admin page and is not the same as the previous item  
<ul>
  
<li>This view is to show multiple associations between entities and many users
</li>  
</ul>
 
<li>Each line item should summarize relevant information  
<ul>
  
<li>Include the username this item is associated with
  
<li>Include a column that shows the total number of users the entity is associated with
</li>  
</ul>
 
<li>Each line item should include the following links  
<ul>
  
<li>A link to a single view of the entity (i.e., a details page)
  
<li>A link to delete (this may be an admin-only thing, but it should be present for the respective role)   
<ul>
   
<li><strong>Note</strong>: This is to delete the relationship and not the specific entity or user
</li>   
</ul>
  
<li>Clicking the username should redirect to the respective user’s profile
</li>  
</ul>
 
<li>The heading of the page should show the total count of items associated with users
 
<li>The heading of this page should include the total number of items shown on the page  
<ul>
  
<li>This value should be changed based on any applied filter
</li>  
</ul>
 
<li>Include a filter that can filter the result set by partial match of username to show only the matching users’ associated items  
<ul>
  
<li>When a filter is applied, there should be a link/button to remove all associations to the matching user(s)
</li>  
</ul>
 
<li>The list page should have other logical options for filtering/sorting the results in addition to the required username filter (it’s all one form)  
<ul>
  
<li>The filter/sort should include a field for the user to specify a limit of records between 1 and 100   
<ul>
   
<li>The server-side should ensure the chosen value is within range or default to 10
</li>   
</ul>
  
<li>A filter with no matching records should clearly mention “No results available” or a similar message of your choice
</li>  
</ul>
</li>  
</ul>

<li>Create a page that shows data <strong>not</strong> associated with any user 
<ul>
 
<li><strong>Note: </strong>This will likely be an admin page and is not the same as the previous item
 
<li>Each line item should summarize relevant information
 
<li>Each line item should include the following links  
<ul>
  
<li>A link to a single view of the entity (i.e., a details page)
</li>  
</ul>
 
<li>The heading of the page should show the total count of items not associated to anyone
 
<li>The heading of this page should include the total number of items shown on the page  
<ul>
  
<li>This value should be changed based on any applied filter
</li>  
</ul>
 
<li>The list page should have logical options for filtering/sorting the results  
<ul>
  
<li>The filter/sort should include a field for the user to specify a limit of records between 1 and 100   
<ul>
   
<li>The server-side should ensure the chosen value is within range or default to 10
</li>   
</ul>
  
<li>A filter with no matching records should clearly mention “No results available” or a similar message of your choice
</li>  
</ul>
</li>  
</ul>

<li>Admin can associate any entity with any users 
<ul>
 
<li><strong>Note:</strong> This may be a form on an existing association page if you rather not have a separate page for this
 
<li>This page should have a form with two fields  
<ul>
  
<li>Entity identifier field (name or some user-friendly property)   
<ul>
   
<li>Partial match
</li>   
</ul>
  
<li>Username field   
<ul>
   
<li>Partial match
</li>   
</ul>
</li>   
</ul>
 
<li>Submitting the form will result in a list of the following  
<ul>
  
<li>All partially matched entities (maximum of 25 results)
  
<li>All partially matched users (maximum of 25 results)
  
<li><strong>Note:</strong> It’s likely best to show this as two separate columns   
<ul>
   
<li><strong>Hint:</strong> similar to the User Role Assignment admin page
</li>   
</ul>
  
<li>Each entity and each user will have a checkbox next to them
  
<li>At the top and/or the bottom should be a button to apply the checked associations
</li>  
</ul>
 
<li>Clicking the association button should apply the associations of the relationship doesn’t exist or remove the association if the relationship exists  
<ul>
  
<li><strong>Hint</strong>: Similar to the User Roles Association admin page
