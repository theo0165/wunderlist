<img src="https://i.giphy.com/media/26ufnwz3wDUli7GU0/giphy.webp" style="width:100%;" />

# Wunderlist

Small todo list website created in Laravel 8.

# Installation

1. Clone the repository
    - ```bash
      git clone https://github.com/theo0165/wunderlist
      cd ./wunderlist
      ```
2. Open the project in your favourite text editor.
3. Create an .env file
    - ```bash
      cp .env.example .env
      ```
    - Open the .env file and add the database name, username and password. You can also add mail host, username and password if you wish. Without this the reset password will not work.
    - Close the .env file
4. Install dependencies for php and node.js
    - ```bash
      composer install
      npm install
      ```
5. Generate application key.
    - ```bash
      php artisan key:generate
      ```
6. Create symbolic links for uploads.
    - ```bash
      php artisan storage:link
      ```
7. Run migrations to create database structure.
    - ```bash
      php artisan migrate:fresh
      ```
8. Build javascript and css files
    - ```bash
      npm run dev
      ```
    - You might have to do this twice the first time.
9. Run the php server.
    - ```bash
      php artisan serve
      ```
10. You´re all set, now visit the site at [http://localhost:8000](http://localhost:8000) and start creating tasks.

# Code Review

Code review written by [Patrik Staaf](https://github.com/patrikstaaf).

1. For readability it would be better to have middleware in routes rather than in each controller.
2. Avoid using get request for deleting data.
3. Instead of having “TaskController” and “NewTaskController” you could make use of the create and store method in the “TaskController”, maybe a resource controller next time?
4. Instead of having “ListController” and “NewListController” you could make use of the create and store method in the “ListController”, maybe a resource controller next time?
5. You could add logout as a destroy method in your LoginController (rename i.e. SessionController) to have one fewer controller.
6. Instead of using raw query in your ProfileController, could this be solved with eloquent?
7. Various imported namespaces not in use.
8. For some reason a few shorthand namespaces don't seem to work for me, I had to add a full path for DB, Storage (this might be on my end though).
9. Migrations: Instead of using unsignedBigInteger & index for fk you can use foreignId, $table->foreignId().
10. Migrations: Instead of looping through to delete user data/list data you can set cascadeOnDelete (can also use constrained() to make db more consistent).
11. profile/show.blade.php line 72, this route doesn't exist/abort 404 when trying to update password (use {{ route('profile.patch') }} instead).
12. You could let the user know the password requirements when register before displaying an error message.

Overall great job, some nifty features i.e. strikethrough and updating db with the checkbox, ability to change what list the task belongs to, although I’m not that fond of the use of vinkla/hashid (joking, amazing stuff).


# Testers

Tested by the following people:

1. Patrik Staaf
2. Albin Andersson
3. Neo Lejondahl

# Features

-   As a user I'm able to create an account.

-   As a user I'm able to login.

-   As a user I'm able to logout.

-   As a user I'm able to edit my account email and password.

-   As a user I'm able to upload a profile avatar image.

-   As a user I'm able to create new tasks with title, description and deadline date.

-   As a user I'm able to edit my tasks.

-   As a user I'm able to delete my tasks.

-   As a user I'm able to mark tasks as completed.

-   As a user I'm able to mark tasks as uncompleted.

-   As a user I'm able to create new task lists with title.

-   As a user I'm able to edit my task lists.

-   As a user I'm able to delete my task lists along with all tasks.

-   As a user I'm able to add a task to a list.

-   As a user I'm able to view all tasks.

-   As a user I'm able to view all tasks within a list.

-   As a user I'm able to view all tasks which should be completed today.

-   As a user I'm able to remove a task from a list.

-   As a user I'm able to delete my account along with all tasks and lists.

-   As a user I'm able to reset my password via email.
