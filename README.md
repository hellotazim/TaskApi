## Design Pattern
Here I have followed MVC pattern for User registration, login, logout and Repository pattern for task management.


## Required Extensions
* ext-curl
* ext-json

## API Endpoint
> http://127.0.0.1:5000/api

### Register User
Endpoint:
> /register

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "POST",
"body": {
    "auth_id":"hellotazim@gmail.com",
    "password": "123456",
    "password_confirmation": "123456"
}
```
Response:
```
{
  "results": {
    "access_token": "1|qfirLB1LdZhcmm0DN61IlsNgdOsasCgYA7QLwep3",
    "id": 1
  },
  "status": "success",
  "error_type": "",
  "code": "",
  "message": "Registration successful"
}
```

### Login User
Endpoint:
> /login

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "POST",
"body": {
    "auth_id":"hellotazim@gmail.com",
    "password": "123456"
}
```
Response:
```
{
    "results": {
        "access_token": "2|vog2VzFVMxnRWgWzw1WkKr4MhAh13aStPOKozXpH",
        "id": 1
    },
    "status": "success",
    "error_type": "",
    "code": "",
    "message": "Login successful"
}
```



### Logout
Endpoint:
> /logout

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "POST",
"body": {}
```
Response:
```
{
    "results": true,
    "status": "success",
    "error_type": "",
    "code": "",
    "message": "Logout successful"
}
```


### Task Create
Endpoint:
> /tasks

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "POST",
"body": {
    "title":"Task 1",
    "description": "task 1 description",
    "status": "Todo",
    "priority": "Medium"
}
```
Response:
```
{
    "results": {
        "title": "Task 1",
        "description": "task 1 description",
        "status": "Todo",
        "priority": "Medium",
        "user_id": 1,
        "updated_at": "2025-05-15T09:50:03.000000Z",
        "created_at": "2025-05-15T09:50:03.000000Z",
        "id": 2
    },
    "status": "success",
    "error_type": "",
    "code": "",
    "message": "Task created successfully"
}
```


### Task Show
Endpoint:
> /tasks/2

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "GET"
```
Response:
```
{
    "results": {
        "id": 2,
        "user_id": 1,
        "title": "Task 2",
        "description": "task 2 description",
        "due_date": null,
        "status": "Todo",
        "priority": "Medium",
        "created_at": "2025-05-15T10:10:46.000000Z",
        "updated_at": "2025-05-15T10:10:46.000000Z",
        "assigned_users": []
    },
    "status": "success",
    "error_type": "",
    "code": "",
    "message": ""
}
```



### Task Update
Endpoint:
> /tasks/2

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "PUT",
"body": {
    "title":"Task 2 updated",
    "description": "task 2 description",
    "status": "Todo",
    "priority": "Medium"
}
```
Response:
```
{
    "results": {
        "id": 2,
        "user_id": 1,
        "title": "Task 2 updated",
        "description": "task 2 description",
        "due_date": null,
        "status": "Todo",
        "priority": "Medium",
        "created_at": "2025-05-15T10:10:46.000000Z",
        "updated_at": "2025-05-15T10:13:10.000000Z"
    },
    "status": "success",
    "error_type": "",
    "code": "",
    "message": ""
}
```


### Task List
Endpoint:
> /tasks

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "GET"
```
Response:
```
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "title": "Task 1",
                "description": "task 1 description",
                "due_date": null,
                "status": "Todo",
                "priority": "Medium",
                "created_at": "2025-05-15T10:10:28.000000Z",
                "updated_at": "2025-05-15T10:10:28.000000Z"
            },
            {
                "id": 2,
                "user_id": 1,
                "title": "Task 2 updated",
                "description": "task 2 description",
                "due_date": null,
                "status": "Todo",
                "priority": "Medium",
                "created_at": "2025-05-15T10:10:46.000000Z",
                "updated_at": "2025-05-15T10:13:10.000000Z"
            }
        ],
        "first_page_url": "http://127.0.0.1:5000/api/tasks?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:5000/api/tasks?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:5000/api/tasks?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:5000/api/tasks",
        "per_page": 10,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    },
    "status": "success",
    "error_type": "",
    "code": "",
    "message": ""
}
```



### Task Delete
Endpoint:
> /tasks/2

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "DELETE"
```
Response:
```
{
    "results": true,
    "status": "success",
    "error_type": "",
    "code": "",
    "message": "Task deleted successfully"
}
```


### Task Assign
Endpoint:
> /tasks/2/assign

Request Body:
```
"header": [
    "Content-Type: 'applicatiion/json'
]
"method": "DELETE",
"body": {
    "user_id":1
}
```
Response:
```
{
    "results": true,
    "status": "success",
    "error_type": "",
    "code": 200,
    "message": "Task assigned successfully."
}
```


### Note
No test code
