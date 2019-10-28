
Available commands:
* common
  * 'GET /' - this list
  * 'POST /login' - Authentification
                
* Users
  * 'GET /user' - Users list
  * 'POST /user' - Create user
  * 'GET /user/<id>' - View user
  * 'PUT /user/<id>' - Edit user
  * 'DELETE /user/<id>' - Delete user (mark as deleted)

* 'Tasks
  * 'GET /task' - Tasks list (available filter by query params)
  * 'POST /task' - Create task
  * 'GET /task/<id>' - View task
  * 'PUT /task/<id>' - Edit task
  * 'PUT /task/<id>/assign' - Edit task assignment
  * 'PUT /task/<id>/status' - Edit task status
  * 'DELETE /task/<id>' - Delete task (set status "deleted")
  * 'GET /task/<id>/comments' - View task\'s comments
  * 'POST /task/<id>/comments' - Add comment to this task
  * 'GET /task/<id>/history' - View task\'s history

* Comments
  * 'GET /comment/<id>' - View comment
  * 'PUT /comment/<id>' - Edit comment
  * 'DELETE /comment/<id>' - Delete comment
