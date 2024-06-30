document.getElementById('loginForm').addEventListener('submit', function(event) {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    if (username === '' || password === '') {
        alert('Both fields are required');
        event.preventDefault();
    }
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var managerId = document.getElementById('manager_id').value;
    if (username === '' || password === '' || managerId === '') {
        alert('All fields are required');
        event.preventDefault();
    }
});
