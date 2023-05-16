## Test todo list task

Based on Laravel 10.

Api calls examples:

- List of tasks:
```GET {{baseUrl}}/api/tasks?per_page=20&sort=-priority&status=todo&priority_to=4&title=Test2```

- Get 1 task
```GET {{baseUrl}}/api/tasks/1```

- Create task
```POST {{baseUrl}}/api/tasks```

    POST data example:
    ```
    title:Test38
    description:ttt23
    priority:5
    parent_id:1
    ```

- Update task
```PUT {{baseUrl}}/api/tasks/1```

    PUT data example:
    ```
    title:Test38
    description:ttt23
    priority:5
    parent_id:1
    ```

- Mark task as done
```PATCH {{baseUrl}}/api/tasks/1/done```

- Delete task
```DELETE {{baseUrl}}/api/tasks/1```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
