// Add new task
function addTask() {
    const task = document.getElementById('task-input').value;
    const category = document.getElementById('category-input').value;
    const deadline = document.getElementById('deadline-input').value;
    const priority = document.getElementById('priority-input').value;

    if (task.trim() !== '') {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_task.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload(); // Reload the page to see the new task
            }
        };
        xhr.send('task=' + encodeURIComponent(task) + '&category=' + encodeURIComponent(category) + '&deadline=' + deadline + '&priority=' + priority);
    }
}

// Delete task
function deleteTask(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_task.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            location.reload();
        }
    };
    xhr.send('id=' + id);
}

// Mark task as completed
function markComplete(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_complete.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            location.reload();
        }
    };
    xhr.send('id=' + id);
}

// Add task with animation
function addTask() {
    const task = document.getElementById('task-input').value;
    const category = document.getElementById('category-input').value;
    const deadline = document.getElementById('deadline-input').value;
    const priority = document.getElementById('priority-input').value;

    if (task.trim() !== '') {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_task.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Add class to task element to trigger fade-in
                const taskElement = document.createElement('div');
                taskElement.classList.add('task-item', 'added');
                taskElement.textContent = task;
                document.getElementById('task-list').appendChild(taskElement);
                location.reload(); // Reload page or append dynamically without page reload
            }
        };
        xhr.send('task=' + encodeURIComponent(task) + '&category=' + encodeURIComponent(category) + '&deadline=' + deadline + '&priority=' + priority);
    }
}

// Delete task with animation
function deleteTask(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_task.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const taskElement = document.getElementById(id);
            taskElement.classList.add('deleted');
            setTimeout(function () {
                taskElement.remove();
            }, 500); // Wait for animation before removing element
        }
    };
    xhr.send('id=' + id);
}

// Mark task as completed
// Mark task as completed
function markComplete(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'mark_complete.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const taskElement = document.getElementById("task-" + id);
            if (taskElement) {
                taskElement.classList.add("completed-task"); // Add a class to change color
            }
        }
    };
    xhr.send('id=' + id);
}


// Filter tasks based on priority
function filterTasks() {
    const filterValue = document.getElementById('filter-priority').value;
    const tasks = document.querySelectorAll('.task-item');

    tasks.forEach(function (task) {
        if (filterValue === 'all' || task.dataset.priority === filterValue) {
            task.style.display = 'block';
        } else {
            task.style.display = 'none';
        }
    });
}

// Input validation function
function validateInput() {
    const inputs = document.querySelectorAll('.input-group input');
    let isValid = true;

    inputs.forEach(function (input) {
        if (input.value.trim() === '') {
            input.classList.add('invalid');
            isValid = false;
        } else {
            input.classList.remove('invalid');
        }
    });

    return isValid;
}
